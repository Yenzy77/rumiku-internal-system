@extends('layouts.app')

@section('header_title', 'Overview')

@section('content')
    <!-- Alerts / Notifications -->
    <div x-data="{ show: true }" x-show="show" x-transition.duration.300ms class="mb-8 bg-[#f8f149]/10 border border-[#f8f149]/20 rounded-2xl p-5 flex items-start justify-between shadow-sm backdrop-blur-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-gray-900 dark:text-zinc-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-extrabold text-gray-900 dark:text-white uppercase tracking-tight">Welcome back to RUMIKU Internal System</h3>
                <p class="text-xs text-gray-600 dark:text-zinc-400 mt-1 font-medium">This is your centralized hub for team management, bookkeeping, and operations.</p>
            </div>
        </div>
        <button @click="show = false" class="ml-4 flex-shrink-0 text-gray-400 dark:text-zinc-500 hover:text-gray-900 dark:hover:text-white transition-colors">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Integrated Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-24 items-stretch auto-rows-fr">
        
        <!-- Card 1: Active Tasks (1x1) -->
        <div class="md:col-span-1 bg-white dark:bg-zinc-900 rounded-[32px] border border-gray-100 dark:border-zinc-800 p-8 flex flex-col justify-between shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#D0F849]/5 rounded-full blur-2xl group-hover:bg-[#D0F849]/10 transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-[0.2em] mb-3">Active Tasks</p>
                    <h3 class="text-5xl font-extrabold text-gray-900 dark:text-white tracking-tighter group-hover:text-[#D0F849] transition-colors duration-300">{{ $activeTasksCount ?? 0 }}</h3>
                </div>
                <div class="p-4 bg-[#D0F849]/10 text-gray-900 dark:text-[#D0F849] rounded-3xl border border-[#D0F849]/20 shadow-sm transition-transform group-hover:rotate-12">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 00(2 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-400 dark:text-zinc-400 mt-8 flex items-center font-bold uppercase tracking-widest relative z-10">
                <span class="text-[#49f8b4] flex items-center mr-2 bg-[#49f8b4]/10 px-2 py-1 rounded-xl">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    3
                </span> 
                New today
            </p>
        </div>

        <!-- Card 2: Pending Approvals (2x2) - HERO IMAGE STYLE -->
        <div class="md:col-span-2 md:row-span-2 bg-white dark:bg-zinc-900 rounded-[40px] border border-gray-100 dark:border-zinc-800 p-12 flex flex-col justify-between shadow-xl ring-4 ring-[#D0F849]/5 relative overflow-hidden group hover:shadow-2xl hover:ring-[#D0F849]/20 dark:hover:ring-[#D0F849]/10 transition-all duration-700">
            <!-- Decorative Elements -->
            <div class="absolute -right-10 -top-10 w-64 h-64 bg-gradient-to-br from-[#D0F849]/20 to-transparent rounded-full blur-3xl opacity-50 transition-transform group-hover:scale-150 duration-700"></div>
            <div class="absolute left-0 bottom-0 w-full h-1/2 bg-gradient-to-t from-gray-50/50 dark:from-zinc-900/80 to-transparent pointer-events-none"></div>

            <div class="relative z-20 flex justify-between items-start">
                <div class="max-w-[70%]">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-[#D0F849] text-gray-900 text-[10px] font-black uppercase tracking-[0.2em] shadow-md mb-8">
                        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span> 
                        Priority Needed
                    </span>
                    <h1 class="text-8xl font-black text-gray-900 dark:text-white tracking-tighter leading-none mb-6">
                        {{ $pendingApprovalsCount ?? 0 }}
                    </h1>
                    <p class="text-2xl font-black text-gray-400 dark:text-zinc-500 tracking-tight leading-tight uppercase max-w-sm">
                        Menunggu <br><span class="text-gray-900 dark:text-zinc-100">Persetujuan Anda</span>
                    </p>
                </div>
                <div class="p-10 bg-[#D0F849] text-gray-900 rounded-[45px] shadow-2xl transition-all duration-500 group-hover:scale-110 group-hover:-rotate-12">
                    <svg class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="relative z-20 mt-12 flex items-center justify-between">
                <button class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 px-8 py-4 rounded-[20px] font-black uppercase tracking-widest text-xs hover:bg-black dark:hover:bg-gray-200 transition-all hover:px-10">
                    Review Now
                </button>
                <div class="flex -space-x-3">
                    <div class="w-10 h-10 rounded-full border-4 border-white dark:border-zinc-900 bg-gray-200 dark:bg-zinc-700"></div>
                    <div class="w-10 h-10 rounded-full border-4 border-white dark:border-zinc-900 bg-gray-300 dark:bg-zinc-600"></div>
                    <div class="w-10 h-10 rounded-full border-4 border-white dark:border-zinc-900 bg-[#D0F849] flex items-center justify-center text-[10px] font-black text-gray-900">+{{ ($pendingApprovalsCount ?? 0) > 2 ? ($pendingApprovalsCount ?? 0) - 2 : 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Card 3: Completed (1x1) -->
        <div class="md:col-span-1 bg-white dark:bg-zinc-900 rounded-[32px] border border-gray-100 dark:border-zinc-800 p-8 flex flex-col justify-between shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#49f8b4]/5 rounded-full blur-2xl group-hover:bg-[#49f8b4]/10 transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-[0.2em] mb-3">Goal Reached</p>
                    <h3 class="text-5xl font-extrabold text-gray-900 dark:text-white tracking-tighter group-hover:text-[#49f8b4] transition-colors duration-300">{{ $completedThisWeekCount ?? 0 }}</h3>
                </div>
                <div class="p-4 bg-[#49f8b4]/10 text-gray-900 dark:text-[#49f8b4] rounded-3xl border border-[#49f8b4]/20 shadow-sm transition-transform group-hover:scale-110">
                    <svg class="w-6 h-6 text-[#49f8b4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-400 dark:text-zinc-400 mt-8 flex items-center font-bold uppercase tracking-widest relative z-10">
                <span class="text-[#49f8b4] flex items-center mr-2 bg-[#49f8b4]/10 px-2 py-1 rounded-xl">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                    +15%
                </span> 
                Weekly growth
            </p>
        </div>

        <!-- Card 4: Omnichannel (1x1) -->
        <div class="md:col-span-1 bg-white dark:bg-zinc-900 rounded-[32px] border border-gray-100 dark:border-zinc-800 p-8 flex flex-col justify-between shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#D0F849]/5 rounded-full blur-2xl group-hover:bg-[#D0F849]/10 transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-[0.2em] mb-3">Live Chats</p>
                    <h3 class="text-5xl font-extrabold text-gray-900 dark:text-white tracking-tighter group-hover:text-[#D0F849] transition-colors duration-300">{{ $activeConversationsCount ?? 0 }}</h3>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-zinc-800/80 text-gray-900 dark:text-zinc-100 rounded-3xl border border-gray-100 dark:border-zinc-700 group-hover:bg-[#D0F849] dark:group-hover:bg-[#D0F849] group-hover:text-gray-900 group-hover:border-[#D0F849] transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
            </div>
            <div class="mt-8">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#D0F849]/10 text-gray-900 dark:text-[#D0F849] text-[9px] font-black uppercase tracking-widest border border-[#D0F849]/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#D0F849] animate-pulse"></span> Multi-Channel
                </span>
            </div>
        </div>

        <!-- Card 5: Social Media (1x1) -->
        <div class="md:col-span-1 bg-white dark:bg-zinc-900 rounded-[32px] border border-gray-100 dark:border-zinc-800 p-8 flex flex-col justify-between shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#49f8f1]/5 rounded-full blur-2xl group-hover:bg-[#49f8f1]/10 transition-colors"></div>
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-[10px] font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-[0.2em] mb-3">Content Queue</p>
                    <h3 class="text-5xl font-extrabold text-gray-900 dark:text-white tracking-tighter group-hover:text-[#49f8f1] transition-colors duration-300">{{ $scheduledPostsCount ?? 0 }}</h3>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-zinc-800/80 text-gray-900 dark:text-zinc-100 rounded-3xl border border-gray-100 dark:border-zinc-700 group-hover:bg-[#49f8f1] dark:group-hover:bg-[#49f8f1] group-hover:text-gray-900 group-hover:border-[#49f8f1] transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
            </div>
            <div class="mt-8">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-[#49f8f1]/10 text-[#49f8f1] text-[9px] font-black uppercase tracking-widest border border-[#49f8f1]/20">
                    Ready to schedule
                </span>
            </div>
        </div>

        <!-- Card 6: Balance (2x1) -->
        <div class="md:col-span-2 bg-gray-900 rounded-[40px] border border-gray-800 p-12 flex flex-col justify-between shadow-2xl relative overflow-hidden group hover:shadow-[#D0F849]/10 transition-all duration-500">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-[#D0F849]/10 to-transparent opacity-40 rounded-bl-full z-0 transition-transform group-hover:scale-125 duration-700"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <p class="text-[10px] font-black text-[#D0F849] uppercase tracking-[0.3em] mb-4">Total Balance (Sisa)</p>
                    <h3 class="text-6xl font-black text-white tracking-tighter">Rp {{ number_format($balance ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="p-8 bg-[#D0F849] text-gray-900 rounded-[35px] shadow-lg transition-transform group-hover:scale-110">
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center justify-between mt-10 relative z-10">
                <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest italic">Net Profit: Rp {{ number_format($netProfit ?? 0, 0, ',', '.') }}</p>
                <span class="text-gray-900 bg-[#D0F849] px-6 py-2.5 rounded-2xl border border-[#D0F849] shadow-md font-black tracking-widest text-[10px] uppercase">Holding Cash Flow</span>
            </div>
        </div>

        <!-- Card 7: Revenue (1x1) -->
        <div class="md:col-span-1 bg-white dark:bg-zinc-900 rounded-[32px] border border-gray-100 dark:border-zinc-800 p-10 flex flex-col justify-between shadow-sm relative overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500">
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-[#49f8b4]/10 to-transparent opacity-60 rounded-bl-full z-0 transition-transform group-hover:scale-125 duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[10px] font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-[0.25em] mb-4">Gross Revenue</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">Rp {{ number_format($currentMonthIncomes ?? 0, 0, ',', '.') }}</h3>
                    <p class="text-[9px] text-gray-400 dark:text-zinc-500 mt-2 font-bold italic tracking-wide">Excl. Capital: Rp {{ number_format($currentMonthCapital ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-[#49f8b4]/10 text-[#49f8b4] rounded-2xl border border-[#49f8b4]/20 shadow-sm transition-transform group-hover:rotate-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Card 8: Expenses (1x1) -->
        <div class="md:col-span-1 bg-white dark:bg-zinc-900 rounded-[32px] border border-gray-100 dark:border-zinc-800 p-10 flex flex-col justify-between shadow-sm relative overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-500">
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-[#fb8c89]/10 to-transparent opacity-60 rounded-bl-full z-0 transition-transform group-hover:scale-125 duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-[10px] font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-[0.25em] mb-4">Total Burn</p>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">Rp {{ number_format($currentMonthExpenses ?? 0, 0, ',', '.') }}</h3>
                    <p class="text-[9px] text-gray-400 dark:text-zinc-500 mt-2 font-bold tracking-wide italic leading-tight">Monthly Operational Cost</p>
                </div>
                <div class="p-4 bg-[#fb8c89]/10 text-[#fb8c89] rounded-2xl border border-[#fb8c89]/20 shadow-sm transition-transform group-hover:-rotate-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <style>
        .pulse-animation {
            animation: pulse-dot 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .5; transform: scale(1.5); }
        }
    </style>
@endsection
