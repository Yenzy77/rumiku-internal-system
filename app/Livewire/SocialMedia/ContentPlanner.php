<?php

namespace App\Livewire\SocialMedia;

use Livewire\Component;
use App\Models\SocialAccount;
use App\Models\SocialPost;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ContentPlanner extends Component
{
    // Form Input
    public $social_account_id = '';
    public $content_body = '';
    public $scheduled_at = '';
    public $status = 'draft';

    // Kalender State
    public $currentMonth;
    public $currentYear;

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
    }

    public function selectDate($date)
    {
        // Pre-fill datetime-local format: YYYY-MM-DDThh:mm
        $this->scheduled_at = Carbon::parse($date)->format('Y-m-d\T12:00');
    }

    public function changeMonth($action)
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        if ($action === 'next') {
            $date->addMonth();
        } elseif ($action === 'prev') {
            $date->subMonth();
        }

        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
    }

    public function save()
    {
        $this->validate([
            'social_account_id' => 'required|exists:social_accounts,id',
            'content_body' => 'required|string',
            'scheduled_at' => 'required|date',
            'status' => 'required|in:draft,review,scheduled,published',
        ]);

        SocialPost::create([
            'social_account_id' => $this->social_account_id,
            'user_id' => Auth::id() ?? 1,
            'content_body' => $this->content_body,
            'scheduled_at' => $this->scheduled_at,
            'status' => $this->status,
        ]);

        $this->reset(['social_account_id', 'content_body', 'scheduled_at']);
        $this->status = 'draft';
        session()->flash('message', 'Konten berhasil dijadwalkan.');
    }

    public function delete($id)
    {
        $post = SocialPost::find($id);
        if ($post) {
            $post->delete();
            session()->flash('message', 'Konten berhasil dihapus.');
        }
    }

    public function render()
    {
        // Referensi tanggal untuk kalender
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $date->daysInMonth;
        // Kita gunakan hari Senin sebagai awal (1), Minggu (7)
        $firstDayOfWeek = $date->dayOfWeekIso;

        // Ambil postingan di bulan ini
        $posts = SocialPost::with('account')
            ->whereYear('scheduled_at', $this->currentYear)
            ->whereMonth('scheduled_at', $this->currentMonth)
            ->get();

        // Mengelompokkan post berdasarkan hari untuk mempermudah render di kalender
        $postsByDay = [];
        foreach ($posts as $post) {
            $day = Carbon::parse($post->scheduled_at)->day;
            if (!isset($postsByDay[$day])) {
                $postsByDay[$day] = [];
            }
            $postsByDay[$day][] = $post;
        }

        return view('livewire.social-media.content-planner', [
            'accounts' => SocialAccount::all(),
            'monthName' => $date->translatedFormat('F Y'),
            'daysInMonth' => $daysInMonth,
            'firstDayOfWeek' => $firstDayOfWeek,
            'postsByDay' => $postsByDay,
        ]);
    }
}
