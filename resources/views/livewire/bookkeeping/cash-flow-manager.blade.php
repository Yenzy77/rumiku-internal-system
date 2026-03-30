<div class="w-full">
    <div class="bg-white dark:bg-zinc-900 p-10 rounded-[40px] shadow-sm mb-10 border border-gray-100 dark:border-zinc-800">
        <div class="mb-10">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Pencatatan Arus Kas Holding</h2>
            <p class="text-[13px] text-gray-400 dark:text-zinc-500 mt-2 font-medium tracking-wide">Track every transaction, monitor cash flow, and manage your business finances.</p>
        </div>
        
        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-100 text-green-700 px-5 py-4 rounded-2xl mb-6 font-medium shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('message') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-[#49f8f1]/5 dark:bg-[#49f8f1]/10 border border-[#49f8f1]/20 p-6 rounded-3xl shadow-sm hover:shadow-md transition-shadow">
                <p class="text-xs text-gray-500 dark:text-zinc-400 font-extrabold uppercase tracking-widest mb-3">Total Modal</p>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Rp {{ number_format($summary['capital'], 0, ',', '.') }}</p>
                <div class="w-12 h-1 bg-[#49f8f1] rounded-full mt-3 opacity-30"></div>
            </div>
            <div class="bg-[#fb8c89]/5 dark:bg-[#fb8c89]/10 border border-[#fb8c89]/20 p-6 rounded-3xl shadow-sm hover:shadow-md transition-shadow">
                <p class="text-xs text-gray-500 dark:text-zinc-400 font-extrabold uppercase tracking-widest mb-3">Total Biaya</p>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Rp {{ number_format($summary['expense'], 0, ',', '.') }}</p>
                <div class="w-12 h-1 bg-[#fb8c89] rounded-full mt-3 opacity-30"></div>
            </div>
            <div class="bg-[#49f8b4]/5 dark:bg-[#49f8b4]/10 border border-[#49f8b4]/20 p-6 rounded-3xl shadow-sm hover:shadow-md transition-shadow">
                <p class="text-xs text-gray-500 dark:text-zinc-400 font-extrabold uppercase tracking-widest mb-3">Pendapatan</p>
                <p class="text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</p>
                <div class="w-12 h-1 bg-[#49f8b4] rounded-full mt-3 opacity-30"></div>
            </div>
            <div class="bg-gray-900 border border-gray-800 p-6 rounded-3xl shadow-lg relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/5 rounded-full blur-xl group-hover:bg-white/10 transition-colors"></div>
                <p class="text-xs text-gray-400 font-extrabold uppercase tracking-widest mb-1.5 relative z-10">Saldo Sisa</p>
                <p class="text-2xl font-extrabold text-white tracking-tight relative z-10">Rp {{ number_format($summary['balance'], 0, ',', '.') }}</p>
            </div>
        </div>

        <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Tanggal</label>
                <input type="date" wire:model="transaction_date" class="w-full border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 text-gray-900 dark:text-white rounded-2xl px-6 py-4 focus:ring-[#D0F849] focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all shadow-sm [color-scheme:light] dark:[color-scheme:dark]">
                @error('transaction_date') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Jenis</label>
                <x-custom-select 
                    wire:model="type" 
                    :options="['capital' => 'Modal (Capital In)', 'operational_expense' => 'Biaya Operasional (OpEx)', 'revenue' => 'Pendapatan (Revenue)']" 
                    placeholder="Pilih Jenis"
                    class="border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70"
                />
                @error('type') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Project</label>
                <x-custom-select 
                    wire:model="project" 
                    :options="['umum' => 'Umum (Holding)', 'creedigo' => 'Creedigo', 'roku' => 'ROKU Project', 'kyoomi' => 'Kyoomi', 'glocult' => 'Glocult']" 
                    placeholder="Pilih Project"
                    class="border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70"
                />
                @error('project') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div x-data="{ 
                visual: '', 
                update(val) {
                    let clean = val.replace(/\D/g, '');
                    this.visual = clean.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    @this.set('amount', clean);
                }
            }" @reset-mask.window="visual = ''">
                <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Nominal (Rp)</label>
                <input 
                    type="text" 
                    x-model="visual" 
                    @input="update($event.target.value)"
                    placeholder="Contoh: 1.000.000"
                    class="w-full border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 text-gray-900 dark:text-white rounded-2xl px-6 py-4 shadow-sm focus:ring-[#D0F849] focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all placeholder:text-gray-400 dark:placeholder:text-zinc-600"
                >
                @error('amount') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Keterangan</label>
                <input type="text" wire:model="description" placeholder="Biaya Beli Domain" class="w-full border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 text-gray-900 dark:text-white rounded-2xl px-6 py-4 focus:ring-[#D0F849] focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all shadow-sm placeholder:text-gray-400 dark:placeholder:text-zinc-600">
                @error('description') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 text-right mt-2">
                <button type="submit" class="bg-[#D0F849] text-gray-900 px-8 py-3.5 rounded-2xl font-extrabold tracking-wide hover:bg-[#bceb23] hover:shadow-md hover:-translate-y-0.5 transition-all focus:ring-4 focus:ring-[#D0F849]/30 outline-none">
                    + Simpan Catatan Keuangan
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-zinc-900 p-8 rounded-3xl shadow-sm border border-gray-50/50 dark:border-zinc-800">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-xl font-extrabold text-gray-900 dark:text-white uppercase tracking-widest">Riwayat Transaksi</h2>
            
            <div class="flex items-center gap-4 text-xs mt-4 md:mt-0">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-400 dark:text-zinc-500">KATEGORI:</span>
                    <div class="w-48">
                        <x-custom-select 
                            wire:model.live="filterType" 
                            :options="['' => 'SEMUA KATEGORI', 'capital' => 'MODAL', 'operational_expense' => 'BIAYA (OPEX)', 'revenue' => 'PENDAPATAN']" 
                            placeholder="SEMUA KATEGORI"
                            class="border-gray-100 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 rounded-xl"
                        />
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-400 dark:text-zinc-500">PROJECT:</span>
                    <div class="w-48">
                        <x-custom-select 
                            wire:model.live="filterProject" 
                            :options="['' => 'SEMUA PROJECT', 'umum' => 'UMUM', 'creedigo' => 'CREEDIGO', 'roku' => 'ROKU', 'kyoomi' => 'KYOOMI', 'glocult' => 'GLOCULT']" 
                            placeholder="SEMUA PROJECT"
                            class="border-gray-100 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 rounded-xl"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto text-xs">
            <table class="w-full text-left uppercase font-bold">
                <thead>
                    <tr class="text-gray-400 dark:text-zinc-500 border-b border-gray-100 dark:border-zinc-800 tracking-wider">
                        <th class="pb-4 px-2">Tanggal</th>
                        <th class="pb-4 px-2">Kategori</th>
                        <th class="pb-4 px-2 text-center">Project</th>
                        <th class="pb-4 px-2">Keterangan</th>
                        <th class="pb-4 px-2 text-right">Jumlah</th>
                        <th class="pb-4 px-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50 dark:divide-zinc-800/50">
                    @forelse($transactions as $item)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/50 transition-colors">
                        <td class="py-4 px-2 text-gray-500 dark:text-zinc-400 font-semibold">{{ \Carbon\Carbon::parse($item->transaction_date)->format('d M Y') }}</td>
                        <td class="py-4 px-2">
                            <span class="px-3 py-1.5 rounded-xl text-[10px] tracking-wider font-extrabold uppercase
                                {{ $item->type == 'capital' ? 'bg-[#49f8f1]/10 text-gray-900 dark:text-white border border-[#49f8f1]/20' : ($item->type == 'operational_expense' ? 'bg-[#fb8c89]/10 text-gray-900 dark:text-white border border-[#fb8c89]/20' : 'bg-[#49f8b4]/10 text-gray-900 dark:text-white border border-[#49f8b4]/20') }}">
                                {{ str_replace('_', ' ', $item->type) }}
                            </span>
                        </td>
                        <td class="py-4 px-2 text-gray-400 dark:text-zinc-500 text-center tracking-wider">{{ $item->project }}</td>
                        <td class="py-4 px-2 normal-case text-gray-800 dark:text-zinc-200 font-bold">{{ $item->description }}</td>
                        <td class="py-4 px-2 text-right text-sm tracking-tight font-extrabold {{ $item->type == 'operational_expense' ? 'text-[#fb8c89]' : 'text-[#49f8b4]' }}">
                             {{ $item->type == 'operational_expense' ? '-' : '+' }} Rp {{ number_format($item->amount, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-2 text-center">
                            <button onclick="rumikuConfirm('Hapus pencatatan ini?', () => @this.delete({{ $item->id }}))" class="text-gray-300 dark:text-zinc-600 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 p-2 rounded-xl transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400 dark:text-zinc-500 italic font-medium">
                            <div class="flex justify-center mb-2">
                                <svg class="w-8 h-8 text-gray-200 dark:text-zinc-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            Tidak ada data transaksi yang sesuai.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>