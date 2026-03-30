<?php

namespace App\Livewire\Marketing;

use Livewire\Component;
use App\Models\Customer;
use Livewire\WithPagination;

class CrmDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $projectFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingProjectFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Customer::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->projectFilter) {
            $query->where('project_origin', $this->projectFilter);
        }

        $customers = $query->latest()->paginate(10);
        
        $metrics = [
            'total' => Customer::count(),
            'creedigo' => Customer::where('project_origin', 'creedigo')->count(),
            'roku' => Customer::where('project_origin', 'roku')->count(),
            'kyoomi' => Customer::where('project_origin', 'kyoomi')->count(),
            'glocult' => Customer::where('project_origin', 'glocult')->count(),
        ];

        return view('livewire.marketing.crm-dashboard', [
            'customers' => $customers,
            'metrics' => $metrics
        ])->layout('layouts.app');
    }
}
