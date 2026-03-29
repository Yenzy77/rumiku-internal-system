<div class="w-full space-y-8">

    <!-- BAGIAN 1: APPROVAL WORKFLOW -->
    <div class="bg-white dark:bg-zinc-900 p-8 rounded-3xl shadow-sm border border-gray-50/50 dark:border-zinc-800">
        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white uppercase tracking-widest tracking-tight">Menunggu Persetujuan</h2>
        <p class="text-[10px] text-gray-400 dark:text-zinc-500 font-bold uppercase tracking-wider mb-6 opacity-70">Review and approve scheduled content for publication.</p>
        
        @if (session()->has('message_approval'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm font-medium">
                {{ session('message_approval') }}
            </div>
        @endif

        <div class="overflow-x-auto text-[13px] custom-scrollbar">
            <table class="w-full text-left font-medium">
                <thead>
                    <tr class="text-gray-400 dark:text-zinc-500 border-b border-gray-100 dark:border-zinc-800 uppercase text-[11px] font-extrabold tracking-widest">
                        <th class="pb-4 px-4 w-1/4">Info Akun & Jadwal</th>
                        <th class="pb-4 px-4 w-1/3">Konten Topik / Caption</th>
                        <th class="pb-4 px-4 text-center">Status</th>
                        <th class="pb-4 px-4 text-center">Aksi Keputusan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50 dark:divide-zinc-800/50">
                    @forelse($postsToReview as $post)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/50 transition-colors duration-200 group">
                        <td class="py-5 px-4 text-gray-700 dark:text-zinc-300">
                            <div class="font-extrabold text-sm tracking-tight text-gray-900 dark:text-white">{{ optional($post->account)->account_name ?? 'Tanpa Akun' }}</div>
                            <div class="text-[10px] text-gray-400 dark:text-zinc-500 font-extrabold uppercase tracking-widest mt-0.5">{{ optional($post->account)->platform }}</div>
                            <div class="text-[#49f8f1] font-extrabold mt-2 flex items-center gap-1 text-[10px] bg-[#49f8f1]/10 w-max px-2.5 py-1 rounded-xl shadow-sm border border-[#49f8f1]/10 uppercase tracking-widest">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ \Carbon\Carbon::parse($post->scheduled_at)->translatedFormat('d M Y, H:i') }}
                            </div>
                        </td>
                        <td class="py-5 px-4 text-gray-700 dark:text-zinc-400">
                            <p class="font-medium line-clamp-3 leading-relaxed text-xs">{{ $post->content_body }}</p>
                            <span class="text-[10px] text-gray-400 dark:text-zinc-500 mt-2.5 block font-bold">Dibuat oleh: <span class="text-gray-600 dark:text-zinc-300">{{ optional($post->user)->name }}</span></span>
                        </td>
                        <td class="py-5 px-4 text-center">
                            <span class="px-3 py-1.5 rounded-xl inline-flex font-extrabold text-[10px] uppercase tracking-widest shadow-sm border
                                {{ $post->status == 'review' ? 'bg-[#f8f149]/10 text-gray-900 dark:text-[#f8f149] border-[#f8f149]/20' : 'bg-gray-50 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400 border-gray-100 dark:border-zinc-700' }}">
                                {{ $post->status }}
                            </span>
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex flex-col gap-2 items-center justify-center opacity-70 group-hover:opacity-100 transition-opacity">
                                <button onclick="confirm('Setujui post ini untuk tayang sesuai jadwal?') || event.stopImmediatePropagation()" wire:click="approvePost({{ $post->id }})" class="w-full max-w-[150px] px-4 py-2 bg-white dark:bg-zinc-800 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 border border-blue-200 dark:border-blue-900/40 hover:border-blue-400 rounded-2xl text-[11px] font-bold tracking-wide transition-all shadow-sm flex items-center justify-center gap-1.5 hover:-translate-y-0.5 relative overflow-hidden">
                                    <div class="absolute inset-0 bg-blue-50/50 dark:bg-blue-400/5 translate-y-full hover:translate-y-0 transition-transform duration-300"></div>
                                    <svg class="w-3.5 h-3.5 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="relative z-10">Setujui (Sch)</span>
                                </button>
                                <button onclick="confirm('Langsung tayangkan post ini sekarang secara manual?') || event.stopImmediatePropagation()" wire:click="publishPost({{ $post->id }})" class="w-full max-w-[150px] px-4 py-2 bg-[#D0F849] text-gray-900 hover:bg-[#bceb23] border border-[#D0F849] rounded-2xl text-[11px] font-extrabold tracking-wide transition-all shadow-sm hover:shadow-md flex items-center justify-center gap-1.5 hover:-translate-y-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    Publish Now
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-10 text-center text-gray-400 dark:text-zinc-600">
                            Semua draft/review sudah diproses. Tidak ada konten menunggu persetujuan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- BAGIAN 2: HISTORI ANALYTICS -->
    <div class="bg-white dark:bg-zinc-900 p-8 rounded-3xl shadow-sm border border-gray-50/50 dark:border-zinc-800 relative">
        <h2 class="text-xl font-extrabold text-gray-900 dark:text-white uppercase tracking-widest mb-6 border-b border-gray-100/50 dark:border-zinc-800/50 pb-4">2. Rekap Performa Konten (Analytics)</h2>

        @if (session()->has('message_analytics'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm font-medium">
                {{ session('message_analytics') }}
            </div>
        @endif

        <div class="overflow-x-auto text-[13px] custom-scrollbar">
            <table class="w-full text-left font-medium">
                <thead>
                    <tr class="text-gray-400 dark:text-zinc-500 border-b border-gray-100 dark:border-zinc-800 uppercase text-[11px] font-extrabold tracking-widest bg-gray-50/80 dark:bg-zinc-800/40">
                        <th class="py-4 px-5 rounded-tl-2xl">Info Post Published</th>
                        <th class="py-4 px-2 text-center border-l border-white dark:border-zinc-800">Total Reach</th>
                        <th class="py-4 px-2 text-center border-l border-white dark:border-zinc-800">Engagement</th>
                        <th class="py-4 px-2 text-center border-l border-white dark:border-zinc-800">Impressions</th>
                        <th class="py-4 px-4 text-center border-l border-white dark:border-zinc-800 rounded-tr-2xl">Update Metrik</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50 dark:divide-zinc-800/50 border-x border-b border-gray-100/50 dark:border-zinc-800/50 rounded-b-2xl">
                    @forelse($publishedPosts as $post)
                    @php
                        // For simplicity in this UI, we just aggregate the sum of metrics currently,
                        // or display the latest metric entry. We'll show the sum here.
                        $totalReach = $post->metrics->sum('reach');
                        $totalEng = $post->metrics->sum('engagement');
                        $totalImp = $post->metrics->sum('impressions');
                    @endphp
                    <tr class="hover:bg-gray-50/30 dark:hover:bg-zinc-800/20 transition-colors duration-200 group">
                        <td class="py-5 px-5">
                            <div class="flex items-start gap-4">
                                <span class="bg-gray-50 dark:bg-zinc-800 rounded-2xl flex items-center justify-center p-3 text-gray-400 dark:text-zinc-500 border border-gray-100 dark:border-zinc-700 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                                <div>
                                    <span class="block font-extrabold text-gray-900 dark:text-white tracking-tight text-sm mb-1">{{ optional($post->account)->account_name }} ({{ optional($post->account)->platform }})</span>
                                    <span class="block text-gray-400 dark:text-zinc-500 text-[10px] uppercase font-extrabold tracking-widest mb-1.5">Date: {{ \Carbon\Carbon::parse($post->scheduled_at)->format('d M y H:i') }}</span>
                                    <span class="block text-gray-600 dark:text-zinc-400 text-xs w-56 truncate font-medium">{{ $post->content_body }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-2 text-center align-middle font-extrabold text-gray-700 dark:text-zinc-300 text-sm bg-gray-50/10 dark:bg-zinc-800/10 border-l border-gray-50/50 dark:border-zinc-800/50">
                            {{ number_format($totalReach, 0, ',', '.') }}
                        </td>
                        <td class="py-5 px-2 text-center align-middle font-extrabold text-[#a589fb] text-sm bg-[#a589fb]/5 dark:bg-[#a589fb]/10 border-l border-gray-50/50 dark:border-zinc-800/50">
                            {{ number_format($totalEng, 0, ',', '.') }}
                        </td>
                        <td class="py-5 px-2 text-center align-middle font-extrabold text-gray-700 dark:text-zinc-300 text-sm bg-gray-50/10 dark:bg-zinc-800/10 border-l border-gray-50/50 dark:border-zinc-800/50">
                            {{ number_format($totalImp, 0, ',', '.') }}
                        </td>
                        <td class="py-5 px-4 text-center align-middle border-l border-gray-50/50 dark:border-zinc-800/50">
                            <button wire:click="openMetricForm({{ $post->id }})" class="px-5 py-2.5 bg-gray-900 text-lime-400 rounded-2xl text-[11px] uppercase tracking-widest font-extrabold shadow-sm hover:bg-gray-800 hover:shadow-md hover:-translate-y-0.5 transition-all opacity-80 group-hover:opacity-100">
                                + Input Data
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center text-gray-400">
                            Belum ada konten dengan status "Published".
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pop-up Overlay for Metric Input -->
        @if($metrics_post_id)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 dark:bg-zinc-950/80 backdrop-blur-sm shadow-2xl">
            <div class="bg-white dark:bg-zinc-900 rounded-3xl shadow-2xl p-8 w-full max-w-md border border-gray-100 dark:border-zinc-800 transform scale-100 transition-all">
                <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100/50 dark:border-zinc-800/50">
                    <h3 class="font-extrabold text-gray-900 dark:text-white uppercase tracking-widest text-sm">Input Metrik Performa</h3>
                    <button wire:click="closeMetricForm" class="text-gray-400 hover:text-red-500 bg-gray-100 dark:bg-zinc-800 hover:bg-red-50 dark:hover:bg-red-900/40 p-2 rounded-full transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveMetrics" class="space-y-5">
                    <div>
                        <label class="block text-xs font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Tanggal Pencatatan</label>
                        <input type="date" wire:model="date_recorded" class="w-full border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 rounded-2xl px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white focus:border-[#D0F849] focus:ring-[#D0F849] shadow-sm transition-all dark:[color-scheme:dark]">
                        @error('date_recorded') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Angka Reach</label>
                            <input type="number" wire:model="reach" class="w-full border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 rounded-2xl px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white focus:border-[#D0F849] focus:ring-[#D0F849] shadow-sm transition-all" placeholder="0">
                            @error('reach') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Engagement</label>
                            <input type="number" wire:model="engagement" class="w-full border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 rounded-2xl px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white focus:border-[#D0F849] focus:ring-[#D0F849] shadow-sm transition-all" placeholder="0">
                            @error('engagement') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-gray-400 dark:text-zinc-500 uppercase tracking-widest mb-2">Total Impressions</label>
                        <input type="number" wire:model="impressions" class="w-full border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 rounded-2xl px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white focus:border-[#D0F849] focus:ring-[#D0F849] shadow-sm transition-all" placeholder="0">
                        @error('impressions') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-6 flex justify-end gap-3">
                        <button type="button" wire:click="closeMetricForm" class="px-6 py-3 bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 rounded-2xl font-extrabold text-gray-500 dark:text-zinc-400 hover:bg-gray-50 dark:hover:bg-zinc-700 hover:text-gray-700 dark:hover:text-zinc-200 tracking-wide transition-colors shadow-sm">Batal</button>
                        <button type="submit" class="px-6 py-3 bg-[#D0F849] text-gray-900 rounded-2xl font-extrabold hover:bg-[#bceb23] tracking-wide transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5">+ Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
