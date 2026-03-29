<?php

namespace App\Livewire\OmniChannel;

use Livewire\Component;
use App\Models\Channel;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UnifiedInbox extends Component
{
    public $activeProject = 'All'; // Filter by project (e.g. Creedigo, ROKU, All)
    public $activeConversationId = null;
    public $replyMessage = '';
    public $lastCheckedMessageId = 0;

    public function mount()
    {
        $latest = Message::latest('id')->first();
        $this->lastCheckedMessageId = $latest ? $latest->id : 0;
    }

    public function checkNewMessages()
    {
        $latest = Message::where('direction', 'inbound')->latest('id')->first();
        if ($latest && $latest->id > $this->lastCheckedMessageId && $this->lastCheckedMessageId > 0) {
            // New message arrived!
            $this->dispatch('notify', message: 'New message from ' . $latest->conversation->contact_name);
        }
        if ($latest) {
            $this->lastCheckedMessageId = $latest->id;
        }
    }

    public function sendMessage()
    {
        $this->validate([
            'replyMessage' => 'required|string|max:1000'
        ]);

        if (!$this->activeConversationId) return;

        $conv = Conversation::with('channel')->find($this->activeConversationId);
        
        $msg = $conv->messages()->create([
            'body' => $this->replyMessage,
            'type' => 'text',
            'direction' => 'outbound',
            // Default user_id to 1 if not logged in (e.g. testing)
            'user_id' => auth()->id() ?? 1, 
        ]);

        $conv->update(['last_message' => $this->replyMessage]);

        try {
            $webhookUrl = env('N8N_OUTBOUND_WEBHOOK', 'https://n8n.rumiku.com/webhook/outbound');
            Http::timeout(5)->post($webhookUrl, [
                'channel_id' => $conv->channel_id,
                'platform' => $conv->channel->platform,
                'provider_id' => $conv->channel->provider_id,
                'external_contact_id' => $conv->external_contact_id,
                'message_body' => $this->replyMessage,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send outbound message to n8n', ['error' => $e->getMessage()]);
        }

        $this->replyMessage = '';
        $this->dispatch('notify', message: 'Message sent successfully!');
    }

    public function getProjectsProperty()
    {
        return Channel::select('project')->distinct()->pluck('project');
    }

    public function getConversationsProperty()
    {
        $query = Conversation::with(['channel', 'messages' => function($q) {
            $q->latest()->limit(1); // load last message
        }]);

        if ($this->activeProject !== 'All') {
            $query->whereHas('channel', function($q) {
                $q->where('project', $this->activeProject);
            });
        }

        return $query->orderBy('updated_at', 'desc')->get();
    }

    public function getActiveConversationProperty()
    {
        if (!$this->activeConversationId) return null;
        return Conversation::with(['channel', 'messages.user'])->find($this->activeConversationId);
    }

    public function selectConversation($id)
    {
        $this->activeConversationId = $id;
    }

    public function setProject($project)
    {
        $this->activeProject = $project;
        $this->activeConversationId = null; // reset selection when changing filter
    }

    public function render()
    {
        return view('livewire.omni-channel.unified-inbox')
             ->layout('layouts.app');
    }
}
