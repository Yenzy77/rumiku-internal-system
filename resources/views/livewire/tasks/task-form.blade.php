<div 
    x-data="{ showPeekForm: @entangle('isOpen'), showAddProperty: @entangle('showAddProperty') }" 
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
                class="w-screen max-w-lg pointer-events-auto"
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
                        <form wire:submit.prevent="save" class="space-y-6">
                            
                            <!-- Title (always fixed) -->
                            <div>
                                <label class="block text-[11px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.2em] mb-3 ml-1">Task Title</label>
                                <input type="text" wire:model="title" class="w-full rounded-2xl border-gray-100 dark:border-zinc-700 outline-none focus:ring-4 focus:ring-[#D0F849]/20 focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all px-6 py-4 text-gray-900 dark:text-white font-bold bg-[#F8F9FA] dark:bg-zinc-800/70 border shadow-sm placeholder:text-gray-300 dark:placeholder:text-zinc-600" placeholder="e.g. Design meeting">
                                @error('title') <p class="mt-2 text-xs text-red-500 font-bold italic tracking-wide ml-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Divider -->
                            <div class="border-t border-gray-100 dark:border-zinc-800"></div>

                            <!-- Dynamic Properties -->
                            @foreach($properties as $property)
                                <div class="group/prop relative">
                                    <div class="flex items-center justify-between mb-3">
                                        <label class="block text-[11px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.2em] ml-1 flex items-center gap-2">
                                            @if($property->icon)
                                                <x-icon name="{{ $property->icon }}" class="w-4 h-4 mr-1 text-gray-400 dark:text-zinc-500" />
                                            @endif
                                            {{ $property->name }}
                                            <span class="text-[9px] font-bold text-gray-300 dark:text-zinc-600 normal-case tracking-wide">({{ $propertyTypes[$property->type] ?? $property->type }})</span>
                                        </label>
                                        @if(!$property->is_default)
                                            <button type="button" onclick="rumikuConfirm('Delete property \'{{ $property->name }}\'? All values will be lost.', () => @this.deleteProperty({{ $property->id }}))" class="opacity-0 group-hover/prop:opacity-100 transition-opacity text-gray-300 dark:text-zinc-600 hover:text-red-500 dark:hover:text-red-400 p-1">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        @endif
                                    </div>

                                    @switch($property->type)
                                        {{-- TEXT --}}
                                        @case('text')
                                            @if($property->slug === 'description')
                                                <textarea wire:model="propertyValues.{{ $property->id }}" rows="3" class="w-full rounded-2xl border-gray-100 dark:border-zinc-700 outline-none focus:ring-4 focus:ring-[#D0F849]/20 focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all px-6 py-4 text-gray-900 dark:text-white font-medium bg-[#F8F9FA] dark:bg-zinc-800/70 border shadow-sm placeholder:text-gray-300 dark:placeholder:text-zinc-600 custom-scrollbar" placeholder="Enter {{ strtolower($property->name) }}..."></textarea>
                                            @else
                                                <input type="text" wire:model="propertyValues.{{ $property->id }}" class="w-full rounded-2xl border-gray-100 dark:border-zinc-700 outline-none focus:ring-4 focus:ring-[#D0F849]/20 focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all px-6 py-4 text-gray-900 dark:text-white font-bold bg-[#F8F9FA] dark:bg-zinc-800/70 border shadow-sm placeholder:text-gray-300 dark:placeholder:text-zinc-600" placeholder="Enter {{ strtolower($property->name) }}...">
                                            @endif
                                            @break

                                        {{-- NUMBER --}}
                                        @case('number')
                                            <input type="number" wire:model="propertyValues.{{ $property->id }}" class="w-full rounded-2xl border-gray-100 dark:border-zinc-700 outline-none focus:ring-4 focus:ring-[#D0F849]/20 focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all px-6 py-4 text-gray-900 dark:text-white font-bold bg-[#F8F9FA] dark:bg-zinc-800/70 border shadow-sm placeholder:text-gray-300 dark:placeholder:text-zinc-600" placeholder="0">
                                            @break

                                        {{-- SELECT --}}
                                        @case('select')
                                            <div x-data="{ open: false }" @click.away="open = false" class="relative">
                                                <button type="button" @click="open = !open" class="w-full flex items-center justify-between rounded-2xl border-gray-100 dark:border-zinc-700 bg-[#F8F9FA] dark:bg-zinc-800/70 border shadow-sm px-6 py-4 font-bold text-left transition-all hover:border-[#D0F849]/50 text-[13px] text-gray-900 dark:text-zinc-100">
                                                    <span>{{ ($propertyValues[$property->id] ?? '') ?: 'Select '.$property->name.'...' }}</span>
                                                    <x-icon name="chevron-down" class="w-5 h-5 text-gray-400 dark:text-zinc-500 transform transition-transform duration-300 origin-center" x-bind:class="open ? 'rotate-180' : ''" />
                                                </button>
                                                <div x-show="open" x-transition class="absolute z-50 w-full mt-2 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-700 rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[250px]" style="display: none;">
                                                    <div class="overflow-y-auto custom-scrollbar p-2">
                                                        @foreach(($property->options ?? []) as $opt)
                                                            <div wire:click="$set('propertyValues.{{ $property->id }}', '{{ $opt }}')" @click="open = false" class="w-full text-left px-6 py-3 text-[13px] font-bold rounded-xl transition-colors flex items-center justify-between group/opt cursor-pointer {{ ($propertyValues[$property->id] ?? '') === $opt ? 'bg-[#D0F849]/10 text-gray-900 dark:text-zinc-100' : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-50 dark:hover:bg-zinc-800' }}">
                                                                <div class="flex items-center gap-2">
                                                                    <span>{{ $opt }}</span>
                                                                    @if(($propertyValues[$property->id] ?? '') === $opt)
                                                                        <x-icon name="check-circle" class="w-4 h-4 text-[#D0F849]" />
                                                                    @endif
                                                                </div>
                                                                <button type="button" onclick="rumikuConfirm('Delete option \'{{ $opt }}\'?', () => @this.deleteOption({{ $property->id }}, '{{ $opt }}'))" class="opacity-0 group-hover/opt:opacity-100 p-1 text-gray-300 hover:text-red-500 transition-all rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10">
                                                                    <x-icon name="trash" class="w-3.5 h-3.5" />
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="p-2 border-t border-gray-100 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/50">
                                                        <div class="flex gap-2 relative">
                                                            <input type="text" wire:model="newOptions.{{ $property->id }}" wire:keydown.enter.prevent="addNewOption({{ $property->id }})" class="w-full rounded-xl border-gray-200 dark:border-zinc-700 outline-none focus:ring-2 focus:ring-[#D0F849]/20 focus:border-[#D0F849] px-3 py-2 text-[12px] font-medium bg-white dark:bg-zinc-900 placeholder:text-gray-400" placeholder="Type new option...">
                                                            <button type="button" wire:click="addNewOption({{ $property->id }})" class="absolute right-2 top-2 text-gray-400 hover:text-[#D0F849]">
                                                                <x-icon name="plus" class="w-4 h-4" />
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @break

                                        {{-- MULTI SELECT & PERSON --}}
                                        @case('multi_select')
                                        @case('person')
                                            <div x-data="{ msOpen: false }" @click.away="msOpen = false" class="relative">
                                                <!-- Selected pills -->
                                                <div @click="msOpen = !msOpen" class="w-full rounded-2xl border-gray-100 dark:border-zinc-700 bg-[#F8F9FA] dark:bg-zinc-800/70 border shadow-sm px-6 py-3 flex flex-wrap gap-2 min-h-[56px] items-center cursor-pointer hover:border-[#D0F849]/50 transition-colors">
                                                    @php
                                                        $sourceOptions = [];
                                                        if ($property->type === 'person') {
                                                            foreach($users as $user) {
                                                                $sourceOptions[$user->id] = ['label' => $user->name, 'id' => $user->id];
                                                            }
                                                        } else {
                                                            foreach(($property->options ?? []) as $opt) {
                                                                $sourceOptions[$opt] = ['label' => $opt, 'id' => $opt];
                                                            }
                                                        }
                                                        $selectedVals = $propertyValues[$property->id] ?? [];
                                                        if (!is_array($selectedVals)) {
                                                            $selectedVals = !empty($selectedVals) ? [ (string)$selectedVals ] : [];
                                                        }
                                                        $selectedVals = array_filter($selectedVals);
                                                    @endphp

                                                    @foreach($selectedVals as $val)
                                                        @php
                                                            $displayLabel = $val;
                                                            if ($property->type === 'person') {
                                                                $user = $users->firstWhere('id', $val);
                                                                $displayLabel = $user ? $user->name : $val;
                                                            }
                                                        @endphp
                                                        <span class="inline-flex items-center gap-1 bg-[#D0F849]/20 text-gray-900 dark:text-[#D0F849] px-3 py-1.5 rounded-xl text-[11px] font-black tracking-wide border border-[#D0F849]/30">
                                                            {{ $displayLabel }}
                                                            <button type="button" wire:click.stop="toggleMultiSelect({{ $property->id }}, '{{ $val }}')" class="ml-1 hover:text-red-500 transition-colors">
                                                                <x-icon name="x" class="w-3 h-3" />
                                                            </button>
                                                        </span>
                                                    @endforeach

                                                    @if(empty($selectedVals))
                                                        <span class="text-gray-400 dark:text-zinc-500 text-[13px] font-medium ml-2">Select {{ strtolower($property->name) }}...</span>
                                                    @endif
                                                    
                                                    <x-icon name="chevron-down" class="ml-auto flex-shrink-0 w-5 h-5 text-gray-400 dark:text-zinc-500 transform transition-transform duration-300 origin-center" x-bind:class="msOpen ? 'rotate-180' : ''" />
                                                </div>
                                                
                                                <!-- Dropdown -->
                                                <div x-show="msOpen" x-transition class="absolute z-50 w-full mt-2 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-700 rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[250px]" style="display: none;">
                                                    <div class="overflow-y-auto custom-scrollbar p-2">
                                                        @foreach($sourceOptions as $optId => $optData)
                                                            @php
                                                                $isSelected = in_array((string)$optId, array_map('strval', $selectedVals)); // string match
                                                            @endphp
                                                            <div wire:click="toggleMultiSelect({{ $property->id }}, '{{ $optId }}')" class="w-full text-left px-6 py-3 text-[13px] font-bold rounded-xl transition-colors flex items-center justify-between group/opt cursor-pointer {{ $isSelected ? 'bg-[#D0F849]/10 text-gray-900 dark:text-zinc-100' : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-50 dark:hover:bg-zinc-800' }}">
                                                                <span class="flex items-center gap-2">
                                                                    @if($property->type === 'person')
                                                                        <div class="w-5 h-5 rounded-full bg-zinc-200 dark:bg-zinc-700 flex items-center justify-center overflow-hidden">
                                                                            <x-boring-avatar :name="$optData['label']" size="20" class="w-full h-full object-cover" />
                                                                        </div>
                                                                    @endif
                                                                    {{ $optData['label'] }}
                                                                </span>
                                                                <div class="flex items-center gap-2">
                                                                    @if($isSelected)
                                                                        <x-icon name="check-circle" class="w-4 h-4 text-[#D0F849]" />
                                                                    @endif
                                                                    <button type="button" onclick="rumikuConfirm('Delete option \'{{ $optData['label'] }}\'?', () => @this.deleteOption({{ $property->id }}, '{{ $optId }}'))" class="opacity-0 group-hover/opt:opacity-100 p-1 text-gray-300 hover:text-red-500 transition-all rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10">
                                                                        <x-icon name="trash" class="w-3.5 h-3.5" />
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    @if($property->type !== 'person')
                                                    <div class="p-2 border-t border-gray-100 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/50">
                                                        <div class="flex gap-2 relative">
                                                            <input type="text" wire:model="newOptions.{{ $property->id }}" wire:keydown.enter.prevent="addNewOption({{ $property->id }})" class="w-full rounded-xl border-gray-200 dark:border-zinc-700 outline-none focus:ring-2 focus:ring-[#D0F849]/20 focus:border-[#D0F849] px-3 py-2 text-[12px] font-medium bg-white dark:bg-zinc-900 placeholder:text-gray-400" placeholder="Add new option...">
                                                            <button type="button" wire:click="addNewOption({{ $property->id }})" class="absolute right-2 top-2 text-gray-400 hover:text-[#D0F849]">
                                                                <x-icon name="plus" class="w-4 h-4" />
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @break

                                        {{-- STATUS --}}
                                        @case('status')
                                            <div x-data="{ statusOpen: false }" @click.away="statusOpen = false" class="relative">
                                                @php
                                                    $currentStatus = $propertyValues[$property->id] ?? '';
                                                    $currentColor = '#9CA3AF';
                                                    foreach(($property->options ?? []) as $opt) {
                                                        if(is_array($opt) && ($opt['label'] ?? '') === $currentStatus) {
                                                            $currentColor = $opt['color'] ?? '#9CA3AF';
                                                        }
                                                    }
                                                @endphp
                                                <button type="button" @click="statusOpen = !statusOpen" class="w-full flex items-center justify-between rounded-2xl border-gray-100 dark:border-zinc-700 bg-[#F8F9FA] dark:bg-zinc-800/70 border shadow-sm px-6 py-4 font-bold text-left transition-all hover:border-[#D0F849]/50">
                                                    <span class="flex items-center gap-3">
                                                        <span class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $currentColor }}"></span>
                                                        <span class="text-gray-900 dark:text-zinc-100 text-[13px]">{{ ($propertyValues[$property->id] ?? '') ?: 'Select status...' }}</span>
                                                    </span>
                                                    <x-icon name="chevron-down" class="w-5 h-5 text-gray-400 dark:text-zinc-500 transform transition-transform duration-300 origin-center" x-bind:class="statusOpen ? 'rotate-180' : ''" />
                                                </button>
                                                <div x-show="statusOpen" x-transition class="absolute z-50 w-full mt-2 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-700 rounded-2xl shadow-xl overflow-hidden flex flex-col max-h-[250px]" style="display: none;">
                                                    <div class="overflow-y-auto custom-scrollbar p-2">
                                                        @foreach(($property->options ?? []) as $opt)
                                                            @php
                                                                $label = is_array($opt) ? ($opt['label'] ?? '') : $opt;
                                                                $color = is_array($opt) ? ($opt['color'] ?? '#9CA3AF') : '#9CA3AF';
                                                            @endphp
                                                            <div wire:click="$set('propertyValues.{{ $property->id }}', '{{ $label }}')" @click="statusOpen = false" class="w-full text-left px-6 py-3 text-[13px] font-bold rounded-xl transition-colors flex items-center group/opt cursor-pointer {{ ($propertyValues[$property->id] ?? '') === $label ? 'bg-[#D0F849]/10 text-gray-900 dark:text-zinc-100' : 'hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-zinc-300' }}">
                                                                <span class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $color }}"></span>
                                                                <span class="ml-3">{{ $label }}</span>
                                                                <div class="flex items-center gap-2 ml-auto">
                                                                    @if(($propertyValues[$property->id] ?? '') === $label)
                                                                        <x-icon name="check-circle" class="w-4 h-4 text-[#D0F849]" />
                                                                    @endif
                                                                    <button type="button" onclick="rumikuConfirm('Delete status \'{{ $label }}\'?', () => @this.deleteOption({{ $property->id }}, '{{ $label }}'))" class="opacity-0 group-hover/opt:opacity-100 p-1 text-gray-300 hover:text-red-500 transition-all rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10">
                                                                        <x-icon name="trash" class="w-3.5 h-3.5" />
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="p-2 border-t border-gray-100 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/50">
                                                        <div class="flex gap-2 relative">
                                                            <input type="text" wire:model="newOptions.{{ $property->id }}" wire:keydown.enter.prevent="addNewOption({{ $property->id }})" class="w-full rounded-xl border-gray-200 dark:border-zinc-700 outline-none focus:ring-2 focus:ring-[#D0F849]/20 focus:border-[#D0F849] px-3 py-2 text-[12px] font-medium bg-white dark:bg-zinc-900 placeholder:text-gray-400" placeholder="New status...">
                                                            <button type="button" wire:click="addNewOption({{ $property->id }})" class="absolute right-2 top-2 text-gray-400 hover:text-[#D0F849]">
                                                                <x-icon name="plus" class="w-4 h-4" />
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @break

                                        {{-- DATE --}}
                                        @case('date')
                                            <input type="date" wire:model="propertyValues.{{ $property->id }}" class="w-full rounded-2xl border-gray-100 dark:border-zinc-700 outline-none focus:ring-4 focus:ring-[#D0F849]/20 focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all px-6 py-4 text-gray-900 dark:text-white font-bold bg-[#F8F9FA] dark:bg-zinc-800/70 border shadow-sm [color-scheme:light] dark:[color-scheme:dark]">
                                            @break

                                        {{-- CHECKBOX --}}
                                        @case('checkbox')
                                            <div class="flex items-center gap-4 px-2 py-2">
                                                <button type="button" wire:click="$set('propertyValues.{{ $property->id }}', '{{ ($propertyValues[$property->id] ?? '0') === '1' ? '0' : '1' }}')" class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-4 focus:ring-[#D0F849]/20 {{ ($propertyValues[$property->id] ?? '0') === '1' ? 'bg-[#D0F849]' : 'bg-gray-200 dark:bg-zinc-700' }}">
                                                    <span class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ ($propertyValues[$property->id] ?? '0') === '1' ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                                </button>
                                                <span class="text-[13px] font-bold text-gray-600 dark:text-zinc-400">{{ ($propertyValues[$property->id] ?? '0') === '1' ? 'Yes' : 'No' }}</span>
                                            </div>
                                            @break

                                        {{-- URL --}}
                                        @case('url')
                                            <input type="url" wire:model="propertyValues.{{ $property->id }}" class="w-full rounded-2xl border-gray-100 dark:border-zinc-700 outline-none focus:ring-4 focus:ring-[#D0F849]/20 focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all px-6 py-4 text-gray-900 dark:text-white font-bold bg-[#F8F9FA] dark:bg-zinc-800/70 border shadow-sm placeholder:text-gray-300 dark:placeholder:text-zinc-600" placeholder="https://...">
                                            @break
                                    @endswitch
                                </div>
                            @endforeach

                            <!-- Add Property Button -->
                            <div class="border-t border-gray-100 dark:border-zinc-800 pt-6">
                                @if(!$showAddProperty)
                                    <button type="button" wire:click="$set('showAddProperty', true)" class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl border-2 border-dashed border-gray-200 dark:border-zinc-700 text-gray-400 dark:text-zinc-500 hover:border-[#D0F849]/50 hover:text-[#D0F849] transition-all text-[12px] font-black uppercase tracking-[0.15em]">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        Add Property
                                    </button>
                                @else
                                    <div class="bg-[#F8F9FA] dark:bg-zinc-800/50 rounded-2xl p-6 space-y-4 border border-gray-100 dark:border-zinc-700">
                                        <h4 class="text-[11px] font-black text-gray-500 dark:text-zinc-400 uppercase tracking-[0.2em]">New Property</h4>
                                        <input type="text" wire:model="newPropertyName" class="w-full rounded-xl border-gray-100 dark:border-zinc-700 outline-none focus:ring-4 focus:ring-[#D0F849]/20 focus:border-[#D0F849] transition-all px-4 py-3 text-gray-900 dark:text-white font-bold bg-white dark:bg-zinc-800/70 border shadow-sm text-[13px] placeholder:text-gray-300 dark:placeholder:text-zinc-600" placeholder="Property name...">
                                        @error('newPropertyName') <p class="text-xs text-red-500 font-bold italic">{{ $message }}</p> @enderror
                                        
                                        <div>
                                            <label class="block text-[10px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.15em] mb-2">Type</label>
                                            @php
                                                $typeOptions = [];
                                                foreach($propertyTypes as $key => $label) {
                                                    $typeOptions[$key] = $label;
                                                }
                                            @endphp
                                            <x-custom-select 
                                                wire:model="newPropertyType"
                                                :options="$typeOptions"
                                                placeholder="Select type..."
                                            />
                                        </div>
                                        
                                        @if(in_array($newPropertyType, ['select', 'multi_select', 'status']))
                                            <div>
                                                <label class="block text-[10px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.15em] mb-2">Options (comma-separated)</label>
                                                <input type="text" wire:model="newPropertyOptions" class="w-full rounded-xl border-gray-100 dark:border-zinc-700 outline-none focus:ring-4 focus:ring-[#D0F849]/20 focus:border-[#D0F849] transition-all px-4 py-3 text-gray-900 dark:text-white font-bold bg-white dark:bg-zinc-800/70 border shadow-sm text-[13px] placeholder:text-gray-300 dark:placeholder:text-zinc-600" placeholder="Option 1, Option 2, Option 3">
                                            </div>
                                        @endif

                                        <div class="flex gap-3 pt-2">
                                            <button type="button" wire:click="addProperty" class="flex-1 bg-[#D0F849] hover:bg-[#bceb23] text-gray-900 py-3 text-[11px] font-black rounded-xl shadow-sm transition-all uppercase tracking-widest">Save</button>
                                            <button type="button" wire:click="$set('showAddProperty', false)" class="flex-1 bg-white dark:bg-zinc-800 text-gray-500 dark:text-zinc-400 py-3 text-[11px] font-black rounded-xl border border-gray-200 dark:border-zinc-700 transition-all uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-zinc-700">Cancel</button>
                                        </div>
                                    </div>
                                @endif
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
                                onclick="rumikuConfirm('Are you strictly sure to delete this task?', () => @this.delete())"
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
