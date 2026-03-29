<div class="w-full">
    <div class="mb-8 flex justify-between items-center bg-white dark:bg-zinc-900 p-5 rounded-3xl shadow-sm border border-gray-50/50 dark:border-zinc-800">
        <div class="flex flex-col">
            <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight ml-2">Content Planner & Scheduler</h2>
            <p class="text-xs text-gray-400 dark:text-zinc-500 mt-1 font-medium tracking-wide ml-2">Plan, schedule, and visualize your content strategy across all platforms.</p>
        </div>
        
        <div class="flex space-x-2 items-center">
            <button wire:click="changeMonth('prev')" class="p-3 bg-gray-50 dark:bg-zinc-800/50 hover:bg-gray-100 dark:hover:bg-zinc-800 text-gray-700 dark:text-zinc-300 rounded-2xl transition border border-gray-100 dark:border-zinc-700 shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <span class="px-6 py-3 bg-gray-900 dark:bg-zinc-800 text-[#D0F849] font-extrabold rounded-2xl border border-gray-800 dark:border-zinc-700 min-w-[170px] text-center uppercase tracking-widest shadow-lg">
                {{ $monthName }}
            </span>
            <button wire:click="changeMonth('next')" class="p-3 bg-gray-50 dark:bg-zinc-800/50 hover:bg-gray-100 dark:hover:bg-zinc-800 text-gray-700 dark:text-zinc-300 rounded-2xl transition border border-gray-100 dark:border-zinc-700 shadow-sm">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-100 text-green-700 px-5 py-4 rounded-2xl mb-6 text-sm font-medium shadow-sm flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Kolom Kiri: Kalender -->
        <div class="xl:col-span-2 bg-white dark:bg-zinc-900 rounded-3xl shadow-sm border border-gray-50/50 dark:border-zinc-800 overflow-hidden">
            <div class="grid grid-cols-7 gap-px bg-gray-100 dark:bg-zinc-800/80 border-b border-gray-100/80 dark:border-zinc-800 text-center">
                @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $dayName)
                    <div class="bg-gray-50/80 dark:bg-zinc-800 py-4 text-xs font-extrabold text-gray-400 dark:text-zinc-400 uppercase tracking-widest">{{ $dayName }}</div>
                @endforeach
            </div>
            <div class="grid grid-cols-7 gap-px bg-gray-100/60 dark:bg-zinc-800/50">
                <!-- Blank days at start of month -->
                @for ($i = 1; $i < $firstDayOfWeek; $i++)
                    <div class="bg-gray-50/30 dark:bg-zinc-900/40 min-h-[140px] p-2"></div>
                @endfor

                <!-- Days of the month -->
                @for ($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        // Format date to pass to the form
                        $dt = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, $day)->format('Y-m-d');
                        $isToday = \Carbon\Carbon::today()->isSameDay(\Carbon\Carbon::parse($dt));
                    @endphp
                    <div wire:click="selectDate('{{ $dt }}')" class="bg-white dark:bg-zinc-900 min-h-[140px] p-3 hover:bg-[#D0F849]/5 dark:hover:bg-[#D0F849]/5 transition-colors cursor-pointer relative group border-t-2 {{ $isToday ? 'border-[#D0F849] bg-gray-50/50 dark:bg-zinc-800/50' : 'border-transparent' }}">
                        <span class="text-sm font-bold {{ $isToday ? 'text-gray-900 bg-[#D0F849] rounded-full w-7 h-7 inline-flex items-center justify-center shadow-sm' : 'text-gray-500 dark:text-zinc-500' }} group-hover:text-gray-900 dark:group-hover:text-white transition">{{ $day }}</span>
                        
                        <!-- List Posts on this Day -->
                        <div class="mt-3 space-y-1.5">
                            @if(isset($postsByDay[$day]))
                                @foreach($postsByDay[$day] as $post)
                                    <div class="text-[10px] py-1.5 px-2 rounded-xl bg-gray-50 dark:bg-zinc-800/80 border border-gray-100/80 dark:border-zinc-700/80 truncate shadow-sm flex items-center justify-between group/post tooltip hover:shadow-md transition-shadow" title="{{ $post->content_body }}">
                                        <div class="flex items-center gap-1.5 overflow-hidden">
                                            <span class="w-2 h-2 rounded-full flex-shrink-0
                                                {{ $post->status == 'published' ? 'bg-[#49f8b4]' : ($post->status == 'scheduled' ? 'bg-[#49f8f1]' : ($post->status == 'review' ? 'bg-[#f8f149]' : 'bg-gray-300')) }}">
                                            </span>
                                            <span class="font-extrabold tracking-wide text-gray-700 dark:text-zinc-300 truncate capitalize text-[9px]">{{ optional($post->account)->platform }}</span>
                                        </div>
                                        <button wire:click.stop="delete({{ $post->id }})" onclick="confirm('Hapus konten dari kalender ini?') || event.stopImmediatePropagation()" class="text-gray-300 dark:text-zinc-500 hover:text-red-500 dark:hover:text-red-400 opacity-0 group-hover/post:opacity-100 transition-all bg-white dark:bg-zinc-700 rounded-full p-0.5 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endfor

                <!-- Blank days at end of month to fill grid -->
                @php
                    $totalCells = ($firstDayOfWeek - 1) + $daysInMonth;
                    $remainingCells = 42 - $totalCells; // Always 6 rows for consistency
                    if($remainingCells >= 7 && $totalCells <= 35) $remainingCells = 35 - $totalCells;
                @endphp
                @for ($i = 0; $i < $remainingCells; $i++)
                    <div class="bg-gray-50/30 dark:bg-zinc-900/40 min-h-[140px] p-2"></div>
                @endfor
            </div>
        </div>

        <!-- Kolom Kanan: Form & Preview -->
        <div class="flex flex-col gap-8" x-data="{ 
            previewCaption: @entangle('content_body').live,
            socialAccountId: @entangle('social_account_id'),
            accountsData: {{ $accounts->map(fn($a) => ['id' => $a->id, 'name' => $a->account_name, 'platform' => strtolower($a->platform)])->toJson() }},
            get previewAccount() {
                let acc = this.accountsData.find(a => a.id == this.socialAccountId);
                return acc ? acc.name : '';
            },
            get previewPlatform() {
                let acc = this.accountsData.find(a => a.id == this.socialAccountId);
                return acc ? acc.platform : '';
            }
        }">
            
            <!-- FOrm -->
            <div class="bg-white dark:bg-zinc-900 p-8 rounded-3xl shadow-sm border border-gray-50/50 dark:border-zinc-800">
                <h3 class="font-extrabold text-gray-900 dark:text-white mb-6 border-b border-gray-100/50 dark:border-zinc-800/50 pb-4 text-xl tracking-tight">Buat Postingan Baru</h3>
                <form wire:submit.prevent="save" class="space-y-5">
                    
                    <div>
                        <label class="block text-xs font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Pilih Akun</label>
                        @php
                            $accOptions = [];
                            foreach($accounts as $acc) {
                                $accOptions[$acc->id] = '[' . strtoupper($acc->project) . '] ' . $acc->platform . ' - ' . $acc->account_name;
                            }
                        @endphp
                        <x-custom-select 
                            wire:model="social_account_id"
                            :options="$accOptions"
                            placeholder="-- Pilih Akun Media Sosial --"
                            class="border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70"
                        />
                        @error('social_account_id') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Tgl Posting</label>
                            <input type="datetime-local" wire:model="scheduled_at" class="w-full border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 text-gray-900 dark:text-white rounded-2xl text-sm font-semibold focus:border-[#D0F849] focus:ring-[#D0F849] px-6 py-4 shadow-sm transition-all [color-scheme:light] dark:[color-scheme:dark]">
                            @error('scheduled_at') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Status</label>
                            <x-custom-select 
                                wire:model="status"
                                :options="['draft' => 'Draft', 'review' => 'Butuh Review', 'scheduled' => 'Terjadwal', 'published' => 'Published']"
                                placeholder="Pilih Status"
                                class="border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70"
                            />
                            @error('status') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Caption Konten</label>
                        <textarea wire:model="content_body" rows="4" class="w-full border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 text-gray-900 dark:text-white rounded-2xl text-sm font-medium focus:border-[#D0F849] focus:ring-[#D0F849] px-6 py-4 shadow-sm transition-all custom-scrollbar placeholder:text-gray-400 dark:placeholder:text-zinc-600" placeholder="Tulis caption di sini..."></textarea>
                        @error('content_body') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-[#D0F849] text-gray-900 py-3.5 rounded-2xl font-extrabold tracking-wide hover:bg-[#bceb23] hover:-translate-y-0.5 transition-all shadow-sm hover:shadow-md focus:ring-4 focus:ring-[#D0F849]/30 outline-none">
                            + Simpan ke Kalender
                        </button>
                    </div>
                </form>
            </div>

            <!-- Visual Live Preview -->
            <div class="bg-gray-50/50 dark:bg-zinc-900/80 p-8 rounded-3xl border border-gray-100 dark:border-zinc-800 flex flex-col items-center relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-gray-100/50 to-white dark:from-zinc-800/50 dark:to-zinc-900 pointer-events-none"></div>
                <h3 class="font-extrabold text-gray-400 dark:text-zinc-400 mb-6 text-center text-[10px] uppercase tracking-widest relative z-10 bg-white dark:bg-zinc-800 px-4 py-1.5 rounded-full border border-gray-100 dark:border-zinc-700 shadow-sm">Live Preview</h3>
                
                <!-- Mock Smartphone Casing -->
                <div class="w-[280px] bg-white dark:bg-zinc-950 rounded-[30px] border-[6px] border-gray-800 shadow-xl overflow-hidden relative">
                    <!-- Top Notch -->
                    <div class="absolute top-0 inset-x-0 h-4 bg-gray-800 rounded-b-xl mx-auto w-24 z-10"></div>
                    
                    <!-- App Interface -->
                    <div class="bg-white dark:bg-zinc-950 h-[450px] flex flex-col pt-6">
                        <!-- App Header -->
                        <div class="px-3 py-2 border-b border-gray-100 dark:border-zinc-800 flex items-center justify-between">
                            <span class="font-bold text-sm tracking-tight capitalize dark:text-white" x-text="previewPlatform || 'Social App'"></span>
                            <svg class="w-4 h-4 text-gray-500 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                        </div>
                        
                        <!-- Post Header -->
                        <div class="px-3 py-2 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold">
                                <span x-text="previewAccount ? previewAccount.charAt(0).toUpperCase() : 'U'"></span>
                            </div>
                            <div class="flex-1">
                                <p class="text-[12px] font-bold text-gray-900 dark:text-white leading-tight" x-text="previewAccount || 'Nama Akun'"></p>
                                <p class="text-[9px] text-gray-500 dark:text-zinc-500" x-text="previewPlatform ? 'Sponsored' : 'Just now'"></p>
                            </div>
                        </div>

                        <!-- Post Media Mock -->
                        <div class="w-full aspect-square bg-gray-100 dark:bg-zinc-900 flex flex-col justify-center items-center text-gray-400 dark:text-zinc-600 border-y border-gray-50 dark:border-zinc-800">
                            <svg class="w-8 h-8 mb-1 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-[10px] font-medium uppercase tracking-wider">Image / Video</span>
                        </div>

                        <!-- Post Caption -->
                        <div class="px-3 py-2 flex-1 overflow-y-auto custom-scrollbar">
                            <div class="flex gap-2 mb-1">
                                <svg class="w-5 h-5 text-gray-800 dark:text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <svg class="w-5 h-5 text-gray-800 dark:text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <svg class="w-5 h-5 text-gray-800 dark:text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </div>
                            <p class="text-[11px] text-gray-800 dark:text-zinc-300 whitespace-pre-wrap leading-snug mt-1"><span class="font-bold mr-1" x-text="previewAccount || 'Nama Akun'"></span><span x-text="previewCaption || 'Tulis caption di form untuk melihat preview di sini...'"></span></p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
