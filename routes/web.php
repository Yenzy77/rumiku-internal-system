<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Models\Task;
use Carbon\Carbon;
use App\Livewire\Bookkeeping\CashFlowManager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Dashboard Route
Route::get('/', function () {
    $activeTasksCount = Task::where('status', 'in_progress')->count();
    $pendingApprovalsCount = Task::where('status', 'pending')->count();
    $completedThisWeekCount = Task::where('status', 'completed')
        ->where('updated_at', '>=', Carbon::now()->subDays(7))
        ->count();

    // Social Media & Omni-channel Stats
    $scheduledPostsCount = \App\Models\SocialPost::where('status', 'scheduled')->count();
    $activeConversationsCount = \App\Models\Conversation::count();

    // Bookkeeping & Analytics Stats
    $currentMonthCapital = \App\Models\Transaction::where('type', 'capital')
        ->whereMonth('transaction_date', Carbon::now()->month)
        ->whereYear('transaction_date', Carbon::now()->year)
        ->sum('amount');

    $currentMonthIncomes = \App\Models\Transaction::where('type', 'revenue')
        ->whereMonth('transaction_date', Carbon::now()->month)
        ->whereYear('transaction_date', Carbon::now()->year)
        ->sum('amount');

    $currentMonthExpenses = \App\Models\Transaction::where('type', 'operational_expense')
        ->whereMonth('transaction_date', Carbon::now()->month)
        ->whereYear('transaction_date', Carbon::now()->year)
        ->sum('amount');

    $netProfit = $currentMonthIncomes - $currentMonthExpenses;
    $balance = ($currentMonthCapital + $currentMonthIncomes) - $currentMonthExpenses;

    return view('dashboard', compact(
        'activeTasksCount', 'pendingApprovalsCount', 'completedThisWeekCount',
        'scheduledPostsCount', 'activeConversationsCount',
        'currentMonthCapital', 'currentMonthIncomes', 'currentMonthExpenses', 'netProfit', 'balance'
    ));
})->name('dashboard');

// Task Management Routes (Modul 1)
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

// Bookkeeping Route (Modul 2)
Route::get('/bookkeeping', CashFlowManager::class)->name('bookkeeping');

// Social Media Routes (Modul 4)
Route::get('/social-media/accounts', \App\Livewire\SocialMedia\AccountManager::class)->name('social-media.accounts');
Route::get('/social-media/planner', \App\Livewire\SocialMedia\ContentPlanner::class)->name('social-media.planner');
Route::get('/social-media/analytics', \App\Livewire\SocialMedia\ApprovalAnalytics::class)->name('social-media.analytics');

// Omni-channel Routes (Modul 5)
Route::get('/omni-channel/inbox', \App\Livewire\OmniChannel\UnifiedInbox::class)->name('omni-channel.inbox');