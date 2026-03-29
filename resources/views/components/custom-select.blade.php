@props([
    'options' => [], 
    'placeholder' => 'Select an option', 
    'name' => null, 
    'value' => null,
    'submit' => false
])

@php
    $wireModelName = $attributes->get('wire:model') ?? $attributes->get('wire:model.live');
    $isLivewire = (bool) $wireModelName;
@endphp

<div 
    x-data="{ 
        open: false, 
        selected: {{ $isLivewire ? "\$wire.entangle('$wireModelName')" : "'$value'" }},
        options: {{ json_encode($options) }},
        init() {
            if({{ $submit ? 'true' : 'false' }}) {
                this.$watch('selected', value => {
                    this.$nextTick(() => {
                        if (this.$el.closest('form')) this.$el.closest('form').submit();
                    });
                });
            }
        },
        get selectedLabel() {
            if (!this.selected) return '{{ $placeholder }}';
            return this.options[this.selected] || '{{ $placeholder }}';
        }
    }" 
    @click.away="open = false"
    class="relative w-full"
>
    <!-- Hidden input for standard form submission without Livewire -->
    @if(!$isLivewire && $name)
        <input type="hidden" name="{{ $name }}" :value="selected">
    @endif

    <button 
        @click="open = !open" 
        type="button" 
        {{ $attributes->except(['wire:model', 'wire:model.live', 'class']) }}
        class="w-full flex items-center justify-between border-gray-100 dark:border-zinc-700 bg-[#F8F9FA] dark:bg-zinc-800/70 rounded-2xl px-6 py-4 focus:outline-none focus:ring-4 focus:ring-[#D0F849]/20 focus:border-[#D0F849] dark:focus:border-[#D0F849] transition-all shadow-sm text-left font-bold {{ $attributes->get('class') }}"
    >
        <span x-text="selectedLabel" class="pr-4 truncate" :class="selected ? 'text-gray-900 dark:text-zinc-100' : 'text-gray-400 dark:text-zinc-500 font-medium'"></span>
        
        <svg class="flex-shrink-0 w-5 h-5 text-gray-400 dark:text-zinc-500 transition-transform duration-200 ml-3" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        style="display: none;"
        class="absolute z-50 w-full mt-2 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-700 rounded-2xl shadow-xl overflow-y-auto max-h-60 py-2 custom-scrollbar"
    >
        @if($placeholder)
        <button 
            type="button"
            @click="selected = ''; open = false;"
            class="w-full text-left px-6 py-3 text-[13px] font-bold transition-colors text-gray-400 hover:bg-gray-50 dark:hover:bg-zinc-800 hover:text-gray-900 dark:hover:text-zinc-100"
        >
            {{ $placeholder }}
        </button>
        @endif
        
        <template x-for="(label, val) in options" :key="val">
            <button 
                type="button"
                @click="selected = val; open = false;"
                class="w-full text-left px-6 py-3 text-[13px] font-bold transition-colors"
                :class="selected == val ? 'bg-[#D0F849]/20 text-gray-900 dark:text-zinc-100 shadow-sm' : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-50 dark:hover:bg-zinc-800 hover:text-gray-900 dark:hover:text-zinc-100'"
            >
                <span x-text="label"></span>
            </button>
        </template>
    </div>
</div>
