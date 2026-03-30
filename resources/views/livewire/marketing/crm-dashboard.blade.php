<div class="w-full">
    <div class="bg-white dark:bg-zinc-900 p-10 rounded-[40px] shadow-sm mb-10 border border-gray-100 dark:border-zinc-800">
        <div class="flex justify-between items-center mb-10">
            <div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">CRM & Marketing</h2>
                <p class="text-[13px] text-gray-400 dark:text-zinc-500 mt-2 font-medium tracking-wide">Kelola segmentasi pelanggan dan optimasi kampanye promosi Omni-channel.</p>
            </div>
            <div>
                <button 
                    @click="$dispatch('open-campaign-form')"
                    class="bg-[#D0F849] text-gray-900 px-6 py-3 rounded-2xl font-extrabold tracking-wide hover:bg-[#bceb23] hover:shadow-md hover:-translate-y-0.5 transition-all focus:ring-4 focus:ring-[#D0F849]/30 outline-none flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                    Buat Campaign
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-gray-50 dark:bg-zinc-800/50 border border-gray-100 dark:border-zinc-800/80 p-5 rounded-3xl shadow-sm">
                <p class="text-[10px] text-gray-500 dark:text-zinc-400 font-extrabold uppercase tracking-widest mb-2">Total Audience</p>
                <p class="text-2xl font-black text-gray-900 dark:text-white">{{ $metrics['total'] }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-zinc-800/50 border border-gray-100 dark:border-zinc-800/80 p-5 rounded-3xl shadow-sm">
                <p class="text-[10px] text-gray-500 dark:text-zinc-400 font-extrabold uppercase tracking-widest mb-2 text-[#49f8f1]">Creedigo</p>
                <p class="text-xl font-black text-gray-900 dark:text-white">{{ $metrics['creedigo'] }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-zinc-800/50 border border-gray-100 dark:border-zinc-800/80 p-5 rounded-3xl shadow-sm">
                <p class="text-[10px] text-gray-500 dark:text-zinc-400 font-extrabold uppercase tracking-widest mb-2 text-[#fb8c89]">ROKU</p>
                <p class="text-xl font-black text-gray-900 dark:text-white">{{ $metrics['roku'] }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-zinc-800/50 border border-gray-100 dark:border-zinc-800/80 p-5 rounded-3xl shadow-sm">
                <p class="text-[10px] text-gray-500 dark:text-zinc-400 font-extrabold uppercase tracking-widest mb-2 text-[#D0F849]">Kyoomi</p>
                <p class="text-xl font-black text-gray-900 dark:text-white">{{ $metrics['kyoomi'] }}</p>
            </div>
            <div class="bg-gray-50 dark:bg-zinc-800/50 border border-gray-100 dark:border-zinc-800/80 p-5 rounded-3xl shadow-sm">
                <p class="text-[10px] text-gray-500 dark:text-zinc-400 font-extrabold uppercase tracking-widest mb-2 text-[#c084fc]">Glocult</p>
                <p class="text-xl font-black text-gray-900 dark:text-white">{{ $metrics['glocult'] }}</p>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input wire:model.live.debounce.500ms="search" type="text" placeholder="Cari kontak, email, atau no telepon..." class="w-full pl-11 pr-4 py-3 bg-gray-50 dark:bg-zinc-800/70 border border-gray-200 dark:border-zinc-700 rounded-2xl text-sm font-semibold text-gray-900 dark:text-white focus:ring-[#D0F849] focus:border-[#D0F849] transition-all shadow-sm">
                </div>
            </div>
            <div class="w-full md:w-64">
                <x-custom-select 
                    wire:model.live="projectFilter" 
                    :options="['' => 'Semua Segmen', 'creedigo' => 'Creedigo', 'roku' => 'ROKU', 'kyoomi' => 'Kyoomi', 'glocult' => 'Glocult']" 
                    placeholder="Semua Segmen"
                    class="bg-gray-50 dark:bg-zinc-800/70 border border-gray-200 dark:border-zinc-700 rounded-2xl text-sm font-bold"
                />
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900/50 rounded-3xl border border-gray-100 dark:border-zinc-800/80 overflow-hidden">
            <div class="overflow-x-auto text-sm">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-zinc-800/80 text-gray-500 dark:text-zinc-400 text-xs font-extrabold uppercase tracking-widest">
                            <th class="py-4 px-6">Nama Kontak</th>
                            <th class="py-4 px-6">Email Address</th>
                            <th class="py-4 px-6">Phone (WA)</th>
                            <th class="py-4 px-6">Project Segmen</th>
                            <th class="py-4 px-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-zinc-800/80">
                        @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">{{ $customer->name }}</td>
                            <td class="py-4 px-6 text-gray-500 dark:text-zinc-400 font-medium">{{ $customer->email ?? '-' }}</td>
                            <td class="py-4 px-6 text-gray-500 dark:text-zinc-400 font-medium">{{ $customer->phone ?? '-' }}</td>
                            <td class="py-4 px-6 font-semibold">
                                @if($customer->project_origin == 'creedigo')
                                    <span class="text-[#49f8f1]">{{ ucfirst($customer->project_origin) }}</span>
                                @elseif($customer->project_origin == 'roku')
                                    <span class="text-[#fb8c89]">{{ ucfirst($customer->project_origin) }}</span>
                                @elseif($customer->project_origin == 'kyoomi')
                                    <span class="text-[#D0F849]">{{ ucfirst($customer->project_origin) }}</span>
                                @elseif($customer->project_origin == 'glocult')
                                    <span class="text-[#c084fc]">{{ ucfirst($customer->project_origin) }}</span>
                                @else
                                    <span class="text-gray-400">Umum</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($customer->status == 'active')
                                    <span class="inline-flex py-1 px-3 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-[10px] font-extrabold uppercase tracking-widest rounded-xl">Active</span>
                                @else
                                    <span class="inline-flex py-1 px-3 bg-gray-100 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400 text-[10px] font-extrabold uppercase tracking-widest rounded-xl">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400 dark:text-zinc-500 italic font-medium">
                                <svg class="w-10 h-10 mx-auto text-gray-200 dark:text-zinc-700 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Tidak ada data target audience.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($customers->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-zinc-800">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Include Livewire Component CampaignForm here -->
    <livewire:marketing.campaign-form />
</div>
