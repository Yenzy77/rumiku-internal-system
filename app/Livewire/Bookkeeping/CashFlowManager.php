<?php

namespace App\Livewire\Bookkeeping;

use Livewire\Component;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class CashFlowManager extends Component
{
    public $transaction_date, $type, $amount, $description;
    public $project = 'umum';
    
    // Properti untuk Filter
    public $filterProject = '';
    public $filterType = ''; 

    public function save()
    {
        if (!empty($this->amount)) {
            $this->amount = preg_replace('/\D/', '', $this->amount);
        }

        $this->validate([
            'transaction_date' => 'required|date',
            'type' => 'required|in:capital,operational_expense,revenue',
            'project' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        Transaction::create([
            'transaction_date' => $this->transaction_date,
            'type' => $this->type,
            'project' => $this->project,
            'amount' => (float) $this->amount,
            'description' => $this->description,
            'user_id' => Auth::id() ?? \App\Models\User::first()?->id ?? 1,
        ]);

        $this->reset(['transaction_date', 'type', 'project', 'amount', 'description']);
        $this->project = 'umum'; 
        
        $this->dispatch('reset-mask'); 
        session()->flash('message', 'Transaksi berhasil dicatat.');
    }

    public function delete($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->delete();
            session()->flash('message', 'Transaksi berhasil dihapus.');
        }
    }

    public function render()
    {
        $totalCapital = Transaction::where('type', 'capital')->sum('amount');
        $totalExpense = Transaction::where('type', 'operational_expense')->sum('amount');
        $totalRevenue = Transaction::where('type', 'revenue')->sum('amount');
        $balance = ($totalCapital + $totalRevenue) - $totalExpense;

        // Logika Filter Ganda (Bisa Project saja, Kategori saja, atau Keduanya)
        $query = Transaction::query();
        
        if ($this->filterProject) {
            $query->where('project', $this->filterProject);
        }
        
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        return view('livewire.bookkeeping.cash-flow-manager', [
            'transactions' => $query->latest()->get(),
            'summary' => [
                'capital' => $totalCapital,
                'expense' => $totalExpense,
                'revenue' => $totalRevenue,
                'balance' => $balance
            ]
        ]);
    }
}