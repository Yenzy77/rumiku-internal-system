<div x-data="{ notification: '', show: false }" x-on:notify.window="notification = $event.detail.message; show = true; setTimeout(() => show = false, 3000)" class="h-[calc(100vh-8rem)] flex bg-white/60 dark:bg-zinc-900/60 backdrop-blur-xl rounded-3xl shadow-sm overflow-hidden border border-gray-50/50 dark:border-zinc-800 relative" wire:poll.5s="checkNewMessages">

    <!-- Toast Notification -->
    <div x-show="show" x-transition.opacity.duration.300ms class="absolute top-4 inset-x-0 flex justify-center z-50">
        <div class="bg-gray-900 dark:bg-zinc-800 dark:border dark:border-zinc-700 text-[#D0F849] px-6 py-3 rounded-2xl shadow-lg text-sm font-extrabold tracking-wide flex items-center gap-2">
            <svg class="w-5 h-5 text-[#D0F849]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span x-text="notification"></span>
        </div>
    </div>
    
    <!-- Sidebar: Conversations List -->
    <div class="w-1/3 bg-gray-50/50 dark:bg-zinc-950/50 border-r border-gray-100/50 dark:border-zinc-800/50 flex flex-col">
        <!-- Header & Filters -->
        <div class="p-5 border-b border-gray-100/50 dark:border-zinc-800/50 bg-white/50 dark:bg-zinc-950/50 backdrop-blur-sm z-10">
            <h2 class="text-xl font-extrabold text-gray-900 dark:text-white uppercase tracking-widest">Inbox</h2>
            <p class="text-[10px] text-gray-400 dark:text-zinc-500 font-bold uppercase tracking-wider mb-4 opacity-70">Unified messaging for all platforms</p>
            
            <!-- Project Filters -->
            <div class="flex space-x-2 overflow-x-auto pb-2 custom-scrollbar">
                <button wire:click="setProject('All')" class="px-4 py-1.5 rounded-2xl text-[11px] font-extrabold tracking-wide whitespace-nowrap transition-all duration-300 {{ $activeProject === 'All' ? 'bg-gray-900 dark:bg-zinc-800/80 text-[#D0F849] shadow-sm' : 'bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 text-gray-500 dark:text-zinc-400 hover:bg-gray-50 dark:hover:bg-zinc-800 hover:text-gray-700 dark:hover:text-zinc-300 shadow-sm' }}">
                    All Projects
                </button>
                @foreach($this->projects as $project)
                <button wire:click="setProject('{{ $project }}')" class="px-4 py-1.5 rounded-2xl text-[11px] font-extrabold tracking-wide whitespace-nowrap transition-all duration-300 {{ $activeProject === $project ? 'bg-gray-900 dark:bg-zinc-800/80 text-[#D0F849] shadow-sm' : 'bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 text-gray-500 dark:text-zinc-400 hover:bg-gray-50 dark:hover:bg-zinc-800 hover:text-gray-700 dark:hover:text-zinc-300 shadow-sm' }}">
                    {{ $project }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Conversations -->
        <div class="flex-1 overflow-y-auto w-full custom-scrollbar">
            @forelse($this->conversations as $conv)
            <div wire:click="selectConversation({{ $conv->id }})" class="p-4 border-b border-gray-50/50 dark:border-zinc-800/50 cursor-pointer transition-colors duration-200 hover:bg-gray-50/50 dark:hover:bg-zinc-900/50 flex items-start gap-3 {{ $activeConversationId === $conv->id ? 'bg-gray-50 dark:bg-zinc-900 border-l-4 border-l-[#D0F849]' : 'border-l-4 border-l-transparent' }}">
                
                <div class="relative flex-shrink-0">
                    <div class="w-12 h-12 rounded-2xl bg-gray-900 dark:bg-zinc-800 flex items-center justify-center text-[#D0F849] font-extrabold text-lg shadow-sm">
                        {{ substr($conv->contact_name, 0, 1) }}
                    </div>
                    <!-- Platform Icon Indicator -->
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-white dark:bg-zinc-900 flex items-center justify-center shadow-sm">
                        @if($conv->channel->platform == 'wa')
                            <span class="text-[#49f8b4] text-xs font-extrabold uppercase">W</span>
                        @elseif($conv->channel->platform == 'ig')
                            <span class="text-[#f849ed] text-xs font-extrabold uppercase">I</span>
                        @else
                            <span class="text-gray-500 dark:text-zinc-500 text-xs font-bold">{{ substr(strtoupper($conv->channel->platform), 0, 1) }}</span>
                        @endif
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-baseline mb-1">
                        <h4 class="text-sm font-extrabold text-gray-900 dark:text-white truncate tracking-tight">{{ $conv->contact_name }}</h4>
                        <span class="text-[10px] text-gray-400 dark:text-zinc-500 font-bold">{{ $conv->updated_at->format('H:i') }}</span>
                    </div>
                    <p class="text-[11px] font-medium text-gray-500 dark:text-zinc-400 truncate mt-0.5">
                        {{ $conv->last_message ?? 'No messages yet.' }}
                    </p>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-extrabold uppercase tracking-widest bg-gray-100 dark:bg-zinc-800 text-gray-600 dark:text-zinc-300 shadow-sm border border-gray-200 dark:border-zinc-700">{{ $conv->channel->project }}</span>
                    </div>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center h-full p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4 text-gray-400 dark:text-zinc-500">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                </div>
                <p class="text-gray-500 dark:text-zinc-400 text-sm">No conversations found.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Main Content: Message Window -->
    <div class="w-2/3 bg-white/90 dark:bg-zinc-900/90 flex flex-col relative rounded-r-3xl">
        @if($activeConversationId && $this->activeConversation)
            <!-- Header -->
            <div class="p-5 border-b border-gray-50/50 dark:border-zinc-800/50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md flex items-center justify-between z-10 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gray-900 dark:bg-zinc-800 flex items-center justify-center text-[#D0F849] font-extrabold shadow-sm">
                        {{ substr($this->activeConversation->contact_name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-extrabold text-lg text-gray-900 dark:text-white tracking-tight leading-none mb-1.5">{{ $this->activeConversation->contact_name }}</h3>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-zinc-400 flex items-center gap-1.5 uppercase tracking-widest">
                            <span class="w-2 h-2 rounded-full bg-[#D0F849] shadow-sm"></span> Active via {{ strtoupper($this->activeConversation->channel->platform) }}
                        </p>
                    </div>
                </div>
                <div>
                    <span class="px-3 py-1.5 rounded-xl bg-gray-50 dark:bg-zinc-800 text-gray-900 dark:text-zinc-300 text-[10px] uppercase font-extrabold tracking-widest border border-gray-200 dark:border-zinc-700 shadow-sm">
                        {{ $this->activeConversation->channel->project }}
                    </span>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-6 bg-gray-50/30 dark:bg-zinc-950/30" id="messages-container">
                @foreach($this->activeConversation->messages as $msg)
                    @if($msg->direction === 'inbound')
                        <!-- Inbound (Left) -->
                        <div class="flex gap-3 max-w-[80%]">
                            <div class="w-8 h-8 rounded-full bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 shadow-sm flex-shrink-0 flex items-center justify-center text-gray-500 dark:text-zinc-400 text-xs font-extrabold mt-1">
                                {{ substr($this->activeConversation->contact_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="bg-white dark:bg-zinc-800 px-5 py-3.5 rounded-3xl rounded-tl-sm shadow-sm border border-gray-100 dark:border-zinc-700 text-gray-700 dark:text-zinc-200 text-[13px] font-medium leading-relaxed">
                                    {{ $msg->body }}
                                </div>
                                <span class="text-[10px] text-gray-400 dark:text-zinc-500 mt-1.5 ml-1 block font-bold">{{ $msg->created_at->format('H:i') }}</span>
                            </div>
                        </div>
                    @else
                        <!-- Outbound (Right) -->
                        <div class="flex gap-3 max-w-[80%] ml-auto justify-end">
                            <div class="text-right">
                                <div class="bg-[#D0F849] px-5 py-3.5 rounded-3xl rounded-tr-sm shadow-sm text-gray-900 text-[13px] font-medium leading-relaxed border border-[#D0F849]">
                                    {{ $msg->body }}
                                </div>
                                <span class="text-[10px] text-gray-400 dark:text-zinc-500 mt-1.5 mr-1 block font-bold">{{ $msg->created_at->format('H:i') }}</span>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-gray-900 dark:bg-zinc-800 flex-shrink-0 flex items-center justify-center text-[#D0F849] text-xs font-extrabold mt-1 shadow-sm">
                                {{ $msg->user ? substr($msg->user->name, 0, 1) : 'Me' }}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Reply Box -->
            <div class="p-5 bg-white dark:bg-zinc-900 border-t border-gray-50/50 dark:border-zinc-800 rounded-br-3xl">
                <form wire:submit.prevent="sendMessage" class="relative flex items-center">
                    <div class="absolute left-4 text-gray-400 dark:text-zinc-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                    </div>
                    <input type="text" wire:model="replyMessage" placeholder="Ketik balasan Anda..." class="w-full bg-gray-50 dark:bg-zinc-950 border border-gray-200 dark:border-zinc-700 text-gray-900 dark:text-white dark:placeholder:text-zinc-600 text-sm rounded-2xl font-medium focus:ring-2 focus:ring-[#D0F849] focus:border-[#D0F849] block pl-12 pr-24 py-4 transition-all shadow-inner" required>
                    <button type="submit" class="absolute right-2 top-2 bottom-2 bg-[#D0F849] hover:bg-[#bceb23] text-gray-900 rounded-xl px-5 text-xs uppercase tracking-widest font-extrabold transition-all shadow-sm hover:shadow-md hover:-translate-y-0.5 disabled:opacity-50" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="sendMessage" class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg></span>
                        <span wire:loading wire:target="sendMessage" class="animate-pulse">...</span>
                    </button>
                </form>
            </div>

        @else
            <!-- Empty State -->
            <div class="flex-1 flex flex-col items-center justify-center p-12 text-center bg-gray-50/30 dark:bg-zinc-950/30 rounded-br-3xl">
                <div class="w-24 h-24 bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700 rounded-3xl flex items-center justify-center mb-6 shadow-sm">
                    <svg class="w-10 h-10 text-gray-300 dark:text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                </div>
                <h3 class="text-xl font-extrabold text-gray-900 dark:text-white tracking-tight mb-2 uppercase">Unified Inbox</h3>
                <p class="text-xs font-medium text-gray-500 dark:text-zinc-400 max-w-sm mb-8 leading-relaxed">Pilih percakapan dari sidebar untuk mulai membaca dan membalas pesan dari berbagai channel integrasi.</p>
                <div class="flex gap-4">
                    <span class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-widest text-gray-500 dark:text-zinc-400 bg-white dark:bg-zinc-800 px-4 py-2 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-700"><span class="w-2.5 h-2.5 rounded-full bg-[#49f8b4] shadow-sm"></span> WhatsApp</span>
                    <span class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-widest text-gray-500 dark:text-zinc-400 bg-white dark:bg-zinc-800 px-4 py-2 rounded-xl shadow-sm border border-gray-100 dark:border-zinc-700"><span class="w-2.5 h-2.5 rounded-full bg-[#f849ed] shadow-sm"></span> Instagram</span>
                </div>
            </div>
        @endif
    </div>
</div>
