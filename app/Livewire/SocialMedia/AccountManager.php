<?php

namespace App\Livewire\SocialMedia;

use Livewire\Component;
use App\Models\SocialAccount;

class AccountManager extends Component
{
    public $project = 'umum';
    public $platform = '';
    public $account_name = '';
    public $account_handle = '';
    public $access_token = '';

    // Filter
    public $filterProject = '';

    public function mount()
    {
        // Set default
        $this->project = 'umum';
    }

    public function save()
    {
        $this->validate([
            'project' => 'required|string',
            'platform' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',
            'account_handle' => 'required|string|max:255',
            'access_token' => 'nullable|string',
        ]);

        SocialAccount::create([
            'project' => $this->project,
            'platform' => $this->platform,
            'account_name' => $this->account_name,
            'account_handle' => str_starts_with($this->account_handle, '@') ? $this->account_handle : '@' . $this->account_handle,
            'access_token' => $this->access_token ?: null,
        ]);

        $this->reset(['platform', 'account_name', 'account_handle', 'access_token']);
        session()->flash('message', 'Akun sosial media berhasil ditambahkan.');
    }

    public function delete($id)
    {
        $account = SocialAccount::find($id);
        if ($account) {
            $account->delete();
            session()->flash('message', 'Akun sosial media berhasil dihapus.');
        }
    }

    public function render()
    {
        $query = SocialAccount::query();

        if ($this->filterProject) {
            $query->where('project', $this->filterProject);
        }

        return view('livewire.social-media.account-manager', [
            'accounts' => $query->latest()->get()
        ]);
    }
}
