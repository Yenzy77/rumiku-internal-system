<div>
    <div x-data="{ open: @entangle('showForm') }" x-cloak>
        <!-- Overlay -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/40 dark:bg-black/80 backdrop-blur-sm z-40"
             @click="open = false">
        </div>

        <!-- Slide-over Panel -->
        <div x-show="open"
             x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed inset-y-0 right-0 max-w-xl w-full flex z-50">
            
            <div class="h-full w-full bg-white dark:bg-zinc-900 shadow-2xl overflow-y-auto flex flex-col pt-6 pb-20 sm:py-8 px-6 sm:px-10 border-l border-white/20 dark:border-zinc-800">
                <!-- Header -->
                <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-100 dark:border-zinc-800/80">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Buat Campaign</h2>
                        <p class="text-[13px] text-gray-400 dark:text-zinc-500 font-medium tracking-wide mt-1">Konfigurasi pesan promosi pelanggan.</p>
                    </div>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-500 bg-gray-50 dark:bg-zinc-800 dark:hover:bg-zinc-700 hover:bg-gray-100 p-2 rounded-full transition-colors">
                        <span class="sr-only">Close panel</span>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <!-- Form Data -->
                <div class="space-y-6 flex-1">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Internal Title</label>
                        <input wire:model="title" type="text" placeholder="Promo Gajian Glocult" class="w-full bg-gray-50 dark:bg-zinc-800/70 border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white rounded-2xl px-5 py-3.5 focus:ring-[#D0F849] focus:border-[#D0F849] transition-all shadow-sm">
                        @error('title') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Tipe Pengiriman</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="type" value="wa" class="peer sr-only">
                                <div class="rounded-2xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50 p-4 hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors peer-checked:border-[#49f8b4] peer-checked:bg-[#49f8b4]/10 peer-checked:ring-1 peer-checked:ring-[#49f8b4]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#49f8b4]/20 flex items-center justify-center text-[#49f8b4]">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2  2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">WhatsApp</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" wire:model.live="type" value="email" class="peer sr-only">
                                <div class="rounded-2xl border border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/50 p-4 hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors peer-checked:border-[#fb8c89] peer-checked:bg-[#fb8c89]/10 peer-checked:ring-1 peer-checked:ring-[#fb8c89]">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#fb8c89]/20 flex items-center justify-center text-[#fb8c89]">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white">Email</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Filter Segmentation -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Segmen Target</label>
                        <x-custom-select 
                            wire:model="project_segment" 
                            :options="['' => 'Berdasarkan Semua Segmen', 'creedigo' => 'Creedigo (Software)', 'roku' => 'ROKU (Clothing)', 'kyoomi' => 'Kyoomi (Beauty)', 'glocult' => 'Glocult (Agency)']" 
                            placeholder="Pilih Project Base"
                            class="bg-gray-50 dark:bg-zinc-800/70 py-3.5 border-gray-200 dark:border-zinc-700 rounded-2xl"
                        />
                        @error('project_segment') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                        <p class="text-[11px] text-gray-400 dark:text-zinc-500 mt-2 font-medium">Kosongkan untuk menyebarkan kampanye ke semua pelanggan.</p>
                    </div>

                    <!-- Content -->
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300">Konten Pesan</label>
                            <span class="text-[10px] bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400 font-extrabold uppercase px-2 py-0.5 rounded-lg border border-gray-200 dark:border-zinc-700">Variabel: {name}</span>
                        </div>
                        <textarea wire:model="content_body" rows="6" placeholder="Halo {name}, dapatkan diskon 50% untuk..." class="w-full bg-gray-50 dark:bg-zinc-800/70 border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white rounded-3xl px-5 py-4 focus:ring-[#D0F849] focus:border-[#D0F849] transition-all shadow-sm resize-y"></textarea>
                        @error('content_body') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Schedule -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Jadwal Blast <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input wire:model="scheduled_at" type="datetime-local" class="w-full bg-gray-50 dark:bg-zinc-800/70 border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white rounded-2xl px-5 py-3.5 focus:ring-[#D0F849] focus:border-[#D0F849] transition-all shadow-sm [color-scheme:light] dark:[color-scheme:dark]">
                        <p class="text-[11px] text-gray-400 dark:text-zinc-500 mt-2 font-medium">Pilih jadwal jika tidak ingin dikirim langsung saat ini.</p>
                    </div>

                </div>

                <!-- Footer Actions -->
                <div class="mt-10 pt-6 border-t border-gray-100 dark:border-zinc-800 flex items-center justify-end gap-3">
                    <button wire:click="saveDraft" class="px-6 py-3 rounded-2xl font-bold bg-white dark:bg-zinc-800 text-gray-700 dark:text-zinc-300 border border-gray-200 dark:border-zinc-700 hover:bg-gray-50 dark:hover:bg-zinc-700 transition">
                        Simpan Draft
                    </button>
                    <button wire:click="sendCampaign" class="px-6 py-3 rounded-2xl font-extrabold bg-[#D0F849] text-gray-900 text-sm tracking-wide hover:bg-[#bceb23] hover:shadow-md transition focus:ring-4 focus:ring-[#D0F849]/30">
                        Buat & Eksekusi n8n
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
