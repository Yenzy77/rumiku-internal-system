<div 
    x-data="{ showPeekForm: @entangle('isOpen') }" 
    x-show="showPeekForm" 
    @keydown.escape.window="showPeekForm = false" 
    class="fixed inset-0 z-50 overflow-hidden" 
    style="display: none;"
    x-cloak
>
    <div class="absolute inset-0 overflow-hidden">
        <!-- Overlay -->
        <div 
            x-show="showPeekForm" 
            x-transition:enter="ease-in-out duration-500" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in-out duration-500" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" 
            @click="showPeekForm = false"
        ></div>

        <!-- Slide-over panel -->
        <div class="fixed inset-y-0 right-0 flex max-w-full pl-10 pointer-events-none">
            <div 
                x-show="showPeekForm" 
                x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" 
                x-transition:enter-start="translate-x-full" 
                x-transition:enter-end="translate-x-0" 
                x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" 
                x-transition:leave-start="translate-x-0" 
                x-transition:leave-end="translate-x-full" 
                class="w-screen max-w-md pointer-events-auto"
            >
                <div class="flex flex-col h-full bg-white dark:bg-zinc-900 shadow-2xl rounded-l-[40px] border-l border-gray-100 dark:border-zinc-800 overflow-hidden">
                    <!-- Header -->
                    <div class="px-8 py-10 bg-[#F8F9FA]/50 dark:bg-zinc-900/50 border-b border-gray-100 dark:border-zinc-800">
                        <div class="flex items-start justify-between">
                            <div>
                                <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                                    {{ $taskId ? 'Update Task' : 'New Task' }}
                                </h2>
                                <p class="text-[12px] text-gray-400 dark:text-zinc-500 mt-2 font-medium tracking-wide uppercase">
                                    {{ $taskId ? 'Modify details for task #'.$taskId : 'Create a new team assignment' }}
                                </p>
                            </div>
                            <button @click="showPeekForm = false" class="text-gray-400 dark:text-zinc-500 hover:text-gray-900 dark:hover:text-white transition-colors p-2 bg-white dark:bg-zinc-800 rounded-2xl border border-gray-100 dark:border-zinc-700 shadow-sm hover:bg-gray-50 dark:hover:bg-zinc-700">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Form Content -->
                    <div class="flex-1 px-8 py-10 overflow-y-auto custom-scrollbar">
                        <form wire:submit.prevent="save" class="space-y-8">
                            
                            <!-- Title -->
                            <div>
                                <label class="block text-[11px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.2em] mb-3 ml-1">Task Title</label>
                                <input type="text" wire:model="title" class="w-full rounded-2xl border-gray-100 dark:border-zinc-700 outline-none focus:ring-4 focus:ring-[#D0F849]/20 focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all px-6 py-4 text-gray-900 dark:text-white font-bold bg-[#F8F9FA] dark:bg-zinc-800/70 border shadow-sm placeholder:text-gray-300 dark:placeholder:text-zinc-600" placeholder="e.g. Design meeting">
                                @error('title') <p class="mt-2 text-xs text-red-500 font-bold italic tracking-wide ml-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-[11px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.2em] mb-3 ml-1">Description</label>
                                <textarea wire:model="description" rows="4" class="w-full rounded-2xl border-gray-100 dark:border-zinc-700 outline-none focus:ring-4 focus:ring-[#D0F849]/20 focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all px-6 py-4 text-gray-900 dark:text-white font-medium bg-[#F8F9FA] dark:bg-zinc-800/70 border shadow-sm placeholder:text-gray-300 dark:placeholder:text-zinc-600 custom-scrollbar" placeholder="What needs to be done?"></textarea>
                                @error('description') <p class="mt-2 text-xs text-red-500 font-bold italic tracking-wide ml-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Assignee & Due Date -->
                            <div class="grid grid-cols-1 gap-8">
                                <div>
                                    <label class="block text-[11px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.2em] mb-3 ml-1">Assign To</label>
                                    @php
                                        $userOptions = [];
                                        foreach($users as $user) {
                                            $userOptions[$user->id] = $user->name;
                                        }
                                    @endphp
                                    <x-custom-select 
                                        wire:model="assigned_to"
                                        :options="$userOptions"
                                        placeholder="-- Unassigned --"
                                    />
                                    @error('assigned_to') <p class="mt-2 text-xs text-red-500 font-bold italic tracking-wide ml-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.2em] mb-3 ml-1">Due Date</label>
                                    <input type="date" wire:model="due_date" class="w-full rounded-2xl border-gray-100 dark:border-zinc-700 outline-none focus:ring-4 focus:ring-[#D0F849]/20 focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all px-6 py-4 text-gray-900 dark:text-white font-bold bg-[#F8F9FA] dark:bg-zinc-800/70 border shadow-sm [color-scheme:light] dark:[color-scheme:dark]">
                                    @error('due_date') <p class="mt-2 text-xs text-red-500 font-bold italic tracking-wide ml-1">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-[11px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.2em] mb-3 ml-1">Status</label>
                                    <x-custom-select
                                        wire:model="status"
                                        :options="['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed']"
                                        placeholder="Select Status"
                                    />
                                    @error('status') <p class="mt-2 text-xs text-red-500 font-bold italic tracking-wide ml-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="px-8 py-8 bg-gray-50/50 dark:bg-zinc-900/80 border-t border-gray-100 dark:border-zinc-800 flex flex-col gap-4">
                        <button 
                            type="button" 
                            wire:click="save" 
                            class="w-full bg-[#D0F849] mb-2 hover:bg-[#bceb23] text-gray-900 py-5 text-xs font-black rounded-2xl shadow-lg border border-[#D0F849] hover:shadow-[#D0F849]/20 hover:-translate-y-1 transition-all uppercase tracking-[0.2em]"
                        >
                            {{ $taskId ? 'Save Changes' : 'Publish Task' }}
                        </button>
                        
                        @if($taskId)
                            <button 
                                type="button" 
                                wire:click="delete" 
                                onclick="confirm('Are you strictly sure to delete this task?') || event.stopImmediatePropagation()"
                                class="w-full bg-white dark:bg-zinc-800 hover:bg-red-50 dark:hover:bg-red-500/10 text-red-500 py-4 text-[10px] font-black rounded-2xl border border-red-100 dark:border-zinc-700 transition-all uppercase tracking-widest"
                            >
                                Delete Task
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
