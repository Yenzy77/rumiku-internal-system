@extends('layouts.app')

@section('header_title', 'Tasks Management')

@section('content')
<div x-data="{}">
    <div class="mb-10 flex flex-col md:flex-row md:justify-between md:items-center gap-6">
        {{-- Quick Stats Pills --}}
        <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar">
            <div class="flex items-center gap-3 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 px-4 py-3 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-gray-50 dark:bg-zinc-800 flex items-center justify-center text-gray-400">
                    <x-icon name="document-text" class="w-4 h-4" />
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-widest">Total</span>
                    <span class="text-[13px] font-black text-gray-900 dark:text-zinc-100 leading-tight">{{ $stats['total'] }}</span>
                </div>
            </div>
            
            <div class="flex items-center gap-3 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 px-4 py-3 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-500/10 flex items-center justify-center text-blue-500">
                    <x-icon name="cursor-click" class="w-4 h-4" />
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-widest">Active</span>
                    <span class="text-[13px] font-black text-gray-900 dark:text-zinc-100 leading-tight">{{ $stats['active'] }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 px-4 py-3 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-[#D0F849]/10 flex items-center justify-center text-[#D0F849]">
                    <x-icon name="check-circle" class="w-4 h-4" />
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-widest">Done</span>
                    <span class="text-[13px] font-black text-gray-900 dark:text-zinc-100 leading-tight">{{ $stats['completed'] }}</span>
                </div>
            </div>

            <div class="flex items-center gap-3 bg-white dark:bg-zinc-900 border border-gray-100 dark:border-zinc-800 px-4 py-3 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-red-50 dark:bg-red-500/10 flex items-center justify-center text-red-500">
                    <x-icon name="calendar" class="w-4 h-4" />
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-widest">Overdue</span>
                    <span class="text-[13px] font-black text-gray-900 dark:text-zinc-100 leading-tight">{{ $stats['overdue'] }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 mt-6 md:mt-0">
            <form action="{{ route('tasks.index') }}" method="GET" class="flex items-center w-64">
                <x-custom-select 
                    name="sort" 
                    :submit="true"
                    value="{{ request('sort', 'deadline_asc') }}"
                    :options="[
                        'deadline_asc' => ['label' => 'Deadline: Paling Dekat', 'icon' => 'calendar'],
                        'deadline_desc' => ['label' => 'Deadline: Paling Jauh', 'icon' => 'calendar'],
                        'title_asc' => ['label' => 'Judul: A-Z', 'icon' => 'document-text'],
                        'title_desc' => ['label' => 'Judul: Z-A', 'icon' => 'document-text'],
                        'newest' => ['label' => 'Terbaru', 'icon' => 'sparkles'],
                        'oldest' => ['label' => 'Terlama', 'icon' => 'archive-box']
                    ]" 
                    class="bg-white dark:bg-zinc-900 border-gray-100 dark:border-zinc-800 text-[13px] font-bold rounded-2xl px-5 py-4 shadow-sm"
                />
            </form>
            <button @click="Livewire.dispatch('openTaskForm')" class="w-14 h-14 flex items-center justify-center bg-[#D0F849] hover:bg-[#bceb23] text-gray-900 rounded-2xl shadow-md hover:shadow-[0_8px_30px_rgb(208,248,73,0.3)] hover:-translate-y-1 transition-all active:scale-95 group" title="Create New Task">
                <x-icon name="plus" class="w-7 h-7 transform group-hover:rotate-90 transition-transform duration-300" />
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-green-50 border border-green-100 text-green-800 text-sm px-6 py-4 rounded-2xl flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-3 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Kanban Board View --}}
    <div class="w-full flex gap-6 overflow-x-auto pb-8 pt-2 items-start custom-scrollbar h-[calc(100vh-280px)]">
        @php
            // Status color map
            $statusColors = [];
            if(isset($statusProperty) && $statusProperty) {
                foreach(($statusProperty->options ?? []) as $opt) {
                    if(is_array($opt)) {
                        $statusColors[$opt['label'] ?? ''] = $opt['color'] ?? '#9CA3AF';
                    }
                }
            }
            $statusColors['No Status'] = '#9CA3AF';
            $fallbackColors = ['#f8f149', '#49f8f1', '#49f8b4', '#fb8c89', '#D0F849'];
            
            // Get properties for card display
            $descProp = $properties->firstWhere('slug', 'description');
            $priorityProp = $properties->firstWhere('slug', 'priority');
            $dueDateProp = $properties->firstWhere('slug', 'due_date');
            $assigneeProp = $properties->firstWhere('slug', 'assignee');
        @endphp
        
        @foreach($tasks as $statusLabel => $groupTasks)
            @php
                $accent = $statusColors[$statusLabel] ?? $fallbackColors[array_rand($fallbackColors)];
            @endphp
            <div class="flex-1 min-w-[320px] max-w-full bg-gray-50/50 dark:bg-zinc-900/30 rounded-[32px] border border-gray-100/50 dark:border-zinc-800/50 flex flex-col max-h-full transition-all">
                {{-- Column Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-zinc-800 sticky top-0 bg-gray-50/80 dark:bg-zinc-900/80 backdrop-blur-md z-10 rounded-t-[32px]">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full shadow-sm" style="background-color: {{ $accent }}"></span>
                        <h3 class="text-[12px] font-black uppercase tracking-[0.2em] text-gray-900 dark:text-zinc-100">{{ $statusLabel }}</h3>
                        <span class="px-2.5 py-1 bg-white dark:bg-zinc-800 rounded-lg text-[10px] font-bold text-gray-500 shadow-sm border border-gray-100 dark:border-zinc-700">{{ $groupTasks->count() }}</span>
                    </div>
                    <button @click="Livewire.dispatch('openTaskForm')" class="w-7 h-7 flex items-center justify-center rounded-xl bg-white dark:bg-zinc-800 text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all shadow-sm border border-gray-100 dark:border-zinc-700 hover:border-[#D0F849]/50">
                        <x-icon name="plus" class="w-4 h-4" />
                    </button>
                </div>

                {{-- Column Cards Area --}}
                <div class="p-4 flex flex-col gap-4 overflow-y-auto custom-scrollbar flex-1">
                    @foreach($groupTasks as $task)
                        {{-- Task Card --}}
                        <div @click="Livewire.dispatch('openTaskForm', { id: {{ $task->id }} })" class="bg-white dark:bg-zinc-900 p-5 rounded-3xl shadow-sm border border-gray-100 dark:border-zinc-800 cursor-pointer hover:border-[#D0F849]/50 hover:shadow-md transition-all group flex flex-col gap-4 active:scale-[0.98]">
                            
                            {{-- Tags (Priority / Add others if needed) --}}
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $priorityValue = $priorityProp ? ($task->values->firstWhere('property_id', $priorityProp->id)?->value) : null;
                                @endphp
                                @if($priorityValue)
                                    @php
                                        $prioColors = [
                                            'Low' => 'text-gray-500 bg-gray-100 dark:bg-zinc-800 border-gray-200 dark:border-zinc-700',
                                            'Medium' => 'text-blue-500 bg-blue-50 dark:bg-blue-500/10 border-blue-100 dark:border-blue-500/20',
                                            'High' => 'text-orange-500 bg-orange-50 dark:bg-orange-500/10 border-orange-100 dark:border-orange-500/20',
                                            'Urgent' => 'text-red-500 bg-red-50 dark:bg-red-500/10 border-red-100 dark:border-red-500/20'
                                        ];
                                        $pColor = $prioColors[$priorityValue] ?? 'text-gray-500 bg-gray-100 border-gray-200';
                                    @endphp
                                    <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-wider rounded-lg border {{ $pColor }}">
                                        {{ $priorityValue }}
                                    </span>
                                @endif
                            </div>

                            {{-- Title & Desc --}}
                            <div>
                                <h4 class="text-[14px] font-black text-gray-900 dark:text-zinc-100 group-hover:text-[#D0F849] transition-colors line-clamp-2 leading-tight mb-2">
                                    {{ $task->title }}
                                </h4>
                                @php
                                    $descValue = $descProp ? ($task->values->firstWhere('property_id', $descProp->id)?->value) : null;
                                @endphp
                                @if($descValue)
                                    <p class="text-[11px] text-gray-400 dark:text-zinc-500 font-bold line-clamp-2 leading-relaxed tracking-wide">
                                        {{ $descValue }}
                                    </p>
                                @endif
                            </div>

                            {{-- Footer: Date and Assignee --}}
                            <div class="mt-auto pt-4 border-t border-gray-50 dark:border-zinc-800/50 flex items-center justify-between">
                                @php
                                    $dateValue = $dueDateProp ? ($task->values->firstWhere('property_id', $dueDateProp->id)?->value) : null;
                                    $isOverdue = $dateValue && \Carbon\Carbon::parse($dateValue)->isPast() && $statusLabel !== 'Done';
                                    $dateColor = $isOverdue ? 'text-red-500 bg-red-50/50 dark:bg-red-500/10' : 'text-gray-400 dark:text-zinc-500';
                                @endphp
                                <div class="flex items-center gap-1.5 {{ $dateColor }} px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">
                                    <x-icon name="calendar" class="w-3.5 h-3.5" />
                                    <span>{{ $dateValue ? \Carbon\Carbon::parse($dateValue)->format('M d') : 'No Date' }}</span>
                                </div>

                                {{-- Multi-Assignee Avatar Stack --}}
                                @php
                                    $assigneeValue = $assigneeProp ? ($task->values->firstWhere('property_id', $assigneeProp->id)?->value) : null;
                                    $assigneeIds = $assigneeValue ? json_decode($assigneeValue, true) : [];
                                    if(!is_array($assigneeIds)) $assigneeIds = [$assigneeValue];
                                    $assigneeIds = array_filter($assigneeIds);
                                @endphp
                                @if(count($assigneeIds) > 0)
                                    <div class="flex -space-x-2">
                                        @foreach(array_slice($assigneeIds, 0, 3) as $userId)
                                            @php $u = \App\Models\User::find($userId); @endphp
                                            @if($u)
                                                <div class="w-7 h-7 rounded-full border-2 border-white dark:border-zinc-900 flex items-center justify-center bg-gray-100 dark:bg-zinc-800 overflow-hidden z-{{ 30 - ($loop->index * 10) }}" title="{{ $u->name }}">
                                                    <x-boring-avatar :name="$u->name" size="40" class="w-full h-full object-cover" />
                                                </div>
                                            @endif
                                        @endforeach
                                        @if(count($assigneeIds) > 3)
                                            <div class="w-7 h-7 rounded-full bg-gray-100 dark:bg-zinc-800 border-2 border-white dark:border-zinc-900 flex items-center justify-center text-[8px] font-black text-gray-500 z-0">
                                                +{{ count($assigneeIds) - 3 }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="w-7 h-7 rounded-full border-2 border-dashed border-gray-200 dark:border-zinc-700 flex items-center justify-center text-gray-300 dark:text-zinc-600">
                                        <x-icon name="user-group" class="w-3.5 h-3.5" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if($groupTasks->count() === 0)
                        <div class="flex flex-col items-center justify-center py-10 px-4 border-2 border-dashed border-gray-200 dark:border-zinc-800 rounded-3xl opacity-50">
                            <x-icon name="document-text" class="w-8 h-8 text-gray-300 dark:text-zinc-600 mb-2" />
                            <p class="text-[11px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-widest text-center">No Tasks</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Livewire Task Form Slide-over -->
    <livewire:tasks.task-form />

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('taskSaved', (message) => {
                window.location.reload();
            });
        });
    </script>
    </div>
@endsection
