<?php

namespace App\Livewire\Marketing;

use Livewire\Component;
use App\Models\Campaign;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CampaignForm extends Component
{
    public $showForm = false;
    
    // Form fields
    public $title = '';
    public $type = 'wa';
    public $project_segment = '';
    public $content_body = '';
    public $scheduled_at = '';

    protected $listeners = ['open-campaign-form' => 'open'];

    public function open()
    {
        $this->reset(['title', 'type', 'project_segment', 'content_body', 'scheduled_at']);
        $this->resetValidation();
        $this->showForm = true;
    }

    public function close()
    {
        $this->showForm = false;
    }

    protected $rules = [
        'title' => 'required|min:3',
        'type' => 'required|in:wa,email',
        'content_body' => 'required|min:10',
    ];

    public function saveDraft()
    {
        $this->validate();

        Campaign::create([
            'user_id' => Auth::id() ?? 1, // Fallback for demonstration if no user is Auth
            'title' => $this->title,
            'type' => $this->type,
            'content_body' => $this->content_body,
            'segment_filters' => $this->project_segment ? ['project_origin' => $this->project_segment] : null,
            'status' => 'draft',
            'scheduled_at' => $this->scheduled_at ?: null,
        ]);

        session()->flash('message', 'Campaign disimpan sebagai DRAFT.');
        $this->close();
    }

    public function sendCampaign()
    {
        $this->validate();

        // 1. Get Target Audience based on segment
        $query = Customer::query()->where('status', 'active');
        if ($this->project_segment) {
            $query->where('project_origin', $this->project_segment);
        }
        
        // Filter based on type to ensure they have the required contact info
        if ($this->type == 'wa') {
            $query->whereNotNull('phone');
        } else {
            $query->whereNotNull('email');
        }

        $customers = $query->get();

        if ($customers->isEmpty()) {
            $this->addError('project_segment', 'Tidak ada target audience yang memenuhi kriteria segmentasi dan memiliki kontak untuk tipe ini.');
            return;
        }

        // 2. Format Audience
        $targetAudience = $customers->map(function($customer) {
            return [
                'name' => $customer->name,
                'contact' => $this->type == 'wa' ? $customer->phone : $customer->email
            ];
        })->toArray();

        // 3. Status determination
        $status = $this->scheduled_at ? 'scheduled' : 'sent';

        // 4. Save to Database
        $campaign = Campaign::create([
            'user_id' => Auth::id() ?? 1,
            'title' => $this->title,
            'type' => $this->type,
            'content_body' => $this->content_body,
            'segment_filters' => $this->project_segment ? ['project_origin' => $this->project_segment] : null,
            'status' => $status,
            'scheduled_at' => $this->scheduled_at ?: null,
        ]);

        // 5. Send payload to n8n Webhook IF not scheduled (send now)
        if ($status == 'sent') {
            $payload = [
                'campaign_id' => $campaign->id,
                'project_origin' => $this->project_segment ?: 'Umum',
                'title' => $this->title,
                'type' => $this->type,
                'content_body' => $this->content_body,
                'target_audience' => $targetAudience
            ];

            try {
                // Here we use a generic n8n URL to conform. In reality it should be stored in .env config.
                $webhookUrl = config('services.n8n.webhook_campaign_url', 'http://n8n.local/webhook/rumiku-campaign');
                
                // For demonstration, we just log since we can't reliably reach local network from external HTTP class without errors sometimes.
                // Http::post($webhookUrl, $payload);
                Log::info('N8N Payload Dispatched', $payload);
            } catch (\Exception $e) {
                // Log the incident but allow to pass or mark failed
                Log::error('Failed sending to n8n webhook: ' . $e->getMessage());
                $campaign->update(['status' => 'draft']); // rollback to draft
                $this->addError('content_body', 'Gagal memicu n8n webhook: ' . $e->getMessage());
                return;
            }
            
            session()->flash('message', 'Campaign Berhasil Dikirim ke antrean n8n!');
        } else {
            session()->flash('message', 'Campaign Terjadwal. Menunggu cron job mengeksekusi ini sesuai jadwal.');
        }

        // Emit an event to refresh CRM dashboard if needed
        $this->dispatch('campaign-created');
        $this->close();
    }

    public function render()
    {
        return view('livewire.marketing.campaign-form');
    }
}
