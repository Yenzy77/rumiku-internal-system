<?php

namespace App\Livewire\SocialMedia;

use Livewire\Component;
use App\Models\SocialPost;
use App\Models\PostMetric;
use Carbon\Carbon;

class ApprovalAnalytics extends Component
{
    // Form Inputs for Metrics
    public $metrics_post_id = null;
    public $reach = 0;
    public $engagement = 0;
    public $impressions = 0;
    public $date_recorded;

    public function mount()
    {
        $this->date_recorded = now()->format('Y-m-d');
    }

    public function approvePost($id)
    {
        $post = SocialPost::find($id);
        if ($post && in_array($post->status, ['draft', 'review'])) {
            $post->update(['status' => 'scheduled']);
            session()->flash('message_approval', 'Konten berhasil disetujui (Scheduled).');
        }
    }

    public function publishPost($id)
    {
        $post = SocialPost::find($id);
        if ($post) {
            $post->update(['status' => 'published']);
            session()->flash('message_approval', 'Konten berhasil diterbitkan (Published).');
        }
    }

    public function openMetricForm($id)
    {
        $this->metrics_post_id = $id;
        $this->date_recorded = now()->format('Y-m-d');
        
        // Coba cari metrik hari ini, jika ada pre-fill
        $existing = PostMetric::where('social_post_id', $id)
            ->where('date_recorded', $this->date_recorded)
            ->first();
            
        if ($existing) {
            $this->reach = $existing->reach;
            $this->engagement = $existing->engagement;
            $this->impressions = $existing->impressions;
        } else {
            $this->reach = 0;
            $this->engagement = 0;
            $this->impressions = 0;
        }
    }

    public function closeMetricForm()
    {
        $this->reset(['metrics_post_id', 'reach', 'engagement', 'impressions']);
        $this->date_recorded = now()->format('Y-m-d');
    }

    public function saveMetrics()
    {
        $this->validate([
            'metrics_post_id' => 'required|exists:social_posts,id',
            'reach' => 'required|integer|min:0',
            'engagement' => 'required|integer|min:0',
            'impressions' => 'required|integer|min:0',
            'date_recorded' => 'required|date',
        ]);

        PostMetric::updateOrCreate(
            [
                'social_post_id' => $this->metrics_post_id,
                'date_recorded' => $this->date_recorded,
            ],
            [
                'reach' => $this->reach,
                'engagement' => $this->engagement,
                'impressions' => $this->impressions,
            ]
        );

        $this->closeMetricForm();
        session()->flash('message_analytics', 'Metrik performa berhasil disimpan.');
    }

    public function render()
    {
        // Posts needing review
        $postsToReview = SocialPost::with('account', 'user')
            ->whereIn('status', ['draft', 'review'])
            ->orderBy('scheduled_at', 'asc')
            ->get();

        // Published posts for analytics (with their latest metric or sum)
        $publishedPosts = SocialPost::with('account', 'metrics')
            ->where('status', 'published')
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return view('livewire.social-media.approval-analytics', [
            'postsToReview' => $postsToReview,
            'publishedPosts' => $publishedPosts,
        ]);
    }
}
