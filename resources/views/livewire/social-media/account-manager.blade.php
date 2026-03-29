<div class="w-full">
    <div class="bg-white dark:bg-zinc-900 p-10 rounded-[40px] shadow-sm mb-10 border border-gray-100 dark:border-zinc-800">
        <div class="mb-10">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Tambah Akun Media Sosial</h2>
            <p class="text-[13px] text-gray-400 dark:text-zinc-500 mt-2 font-medium tracking-wide">Connect and manage all your brand's social media profiles securely.</p>
        </div>
        
        @if (session()->has('message'))
            <div class="bg-green-50 border border-green-100 text-green-700 px-5 py-4 rounded-2xl mb-6 text-sm font-medium shadow-sm flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-3">Project</label>
                <x-custom-select 
                    wire:model="project" 
                    :options="['umum' => 'Umum (Holding)', 'creedigo' => 'Creedigo', 'roku' => 'ROKU Project', 'kyoomi' => 'Kyoomi', 'glocult' => 'Glocult']" 
                    placeholder="Pilih Project" 
                    class="border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70"
                />
                @error('project') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-3">Platform</label>
                <x-custom-select 
                    wire:model="platform" 
                    :options="['Instagram' => 'Instagram', 'TikTok' => 'TikTok', 'Twitter/X' => 'Twitter / X', 'YouTube' => 'YouTube', 'LinkedIn' => 'LinkedIn', 'Facebook' => 'Facebook']" 
                    placeholder="Pilih Platform"
                    class="border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70"
                />
                @error('platform') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-3">Nama Akun / Display Name</label>
                <input type="text" wire:model="account_name" placeholder="Contoh: RUMIKU Official" class="w-full border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 text-gray-900 dark:text-white dark:placeholder:text-zinc-600 rounded-2xl px-6 py-4 shadow-sm focus:border-[#D0F849] focus:ring-[#D0F849] transition-all">
                @error('account_name') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-3">Username / Handle</label>
                <input type="text" wire:model="account_handle" placeholder="Contoh: @rumiku.id" class="w-full border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 text-gray-900 dark:text-white dark:placeholder:text-zinc-600 rounded-2xl px-6 py-4 shadow-sm focus:border-[#D0F849] focus:ring-[#D0F849] transition-all">
                @error('account_handle') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-zinc-300 mb-2">Access Token (Opsional)</label>
                <input type="password" wire:model="access_token" placeholder="Biarkan kosong jika integrasi API belum diperlukan" class="w-full border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70 text-gray-900 dark:text-white dark:placeholder:text-zinc-600 rounded-2xl px-6 py-4 shadow-sm focus:border-[#D0F849] focus:ring-[#D0F849] transition-all">
                <p class="text-xs text-gray-400 dark:text-zinc-500 mt-2 font-medium">Token akan dienkripsi secara aman di database.</p>
                @error('access_token') <span class="text-red-500 text-xs font-medium mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="md:col-span-2 text-right mt-4">
                <button type="submit" class="bg-[#D0F849] text-gray-900 px-8 py-3.5 rounded-2xl font-extrabold tracking-wide hover:bg-[#bceb23] hover:shadow-md hover:-translate-y-0.5 transition-all focus:ring-4 focus:ring-[#D0F849]/30 outline-none">
                    + Simpan Akun
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white dark:bg-zinc-900 p-10 rounded-[40px] shadow-sm border border-gray-100 dark:border-zinc-800">
        <div class="flex flex-col md:flex-row justify-between items-center mb-10">
            <h2 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-widest">Daftar Akun Terdaftar</h2>
            
            <div class="flex items-center gap-3 mt-4 md:mt-0 text-sm">
                <span class="font-extrabold text-xs text-gray-400 dark:text-zinc-500">FILTER PROJECT:</span>
                <x-custom-select 
                    wire:model.live="filterProject" 
                    class="rounded-xl border-gray-100 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-800/70" 
                    :options="['' => 'SEMUA PROJECT', 'umum' => 'UMUM', 'creedigo' => 'CREEDIGO', 'roku' => 'ROKU', 'kyoomi' => 'KYOOMI', 'glocult' => 'GLOCULT']" 
                    placeholder="Pilih Project" 
                />
            </div>
        </div>

        <div class="overflow-x-auto text-[13px]">
            <table class="w-full text-left font-medium">
                <thead>
                    <tr class="text-gray-400 dark:text-zinc-500 border-b border-gray-100 dark:border-zinc-800 uppercase text-[11px] font-extrabold tracking-widest">
                        <th class="pb-4 px-4">Project</th>
                        <th class="pb-4 px-4">Platform</th>
                        <th class="pb-4 px-4">Info Akun</th>
                        <th class="pb-4 px-4 text-center">API Token</th>
                        <th class="pb-4 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50/50 dark:divide-zinc-800/50">
                    @forelse($accounts as $account)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-zinc-800/30 transition-colors duration-200 group">
                        <td class="py-5 px-4 text-gray-500 dark:text-zinc-400 uppercase font-extrabold tracking-wider text-[11px]">{{ $account->project }}</td>
                        <td class="py-5 px-4">
                            <span class="px-3 py-1.5 rounded-xl inline-flex items-center gap-1.5 text-[11px] font-extrabold tracking-wider shadow-sm uppercase
                                {{ strtolower($account->platform) == 'instagram' ? 'bg-[#f849ed]/10 text-gray-900 dark:text:white border border-[#f849ed]/20' : 
                                  (strtolower($account->platform) == 'tiktok' ? 'bg-gray-900 text-white' : 
                                  (strtolower($account->platform) == 'youtube' ? 'bg-[#fb8c89]/10 text-gray-900 dark:text-white border border-[#fb8c89]/20' : 
                                  'bg-[#49f8f1]/10 text-gray-900 dark:text-white border border-[#49f8f1]/20')) }}">
                                {{ $account->platform }}
                            </span>
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex flex-col">
                                <span class="text-gray-900 dark:text-white font-extrabold tracking-tight text-sm">{{ $account->account_name }}</span>
                                <span class="text-gray-500 dark:text-zinc-400 text-xs font-medium">{{ $account->account_handle }}</span>
                            </div>
                        </td>
                        <td class="py-5 px-4 text-center">
                            @if($account->access_token)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-bold tracking-wider uppercase bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 border border-green-100 dark:border-green-900/50 shadow-sm">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    Tersimpan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-bold tracking-wider uppercase bg-gray-50 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400 border border-gray-200 dark:border-zinc-700 shadow-sm">
                                    Kosong
                                </span>
                            @endif
                        </td>
                        <td class="py-5 px-4 text-center">
                            <button onclick="confirm('Yakin ingin menghapus akun {{ $account->account_handle }}?') || event.stopImmediatePropagation()" wire:click="delete({{ $account->id }})" class="text-gray-300 dark:text-zinc-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 p-2 rounded-xl transition tooltip" title="Hapus Akun">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400 dark:text-zinc-500">
                                <svg class="w-12 h-12 mb-3 text-gray-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="italic font-normal">Belum ada akun media sosial yang terdaftar.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
