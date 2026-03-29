@extends('layouts.app')

@section('header_title', 'Tasks Management')

@section('content')
<div x-data="{}">
    <div class="mb-10 flex flex-col md:flex-row md:justify-between md:items-end gap-6">
        <div>
            <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Team Tasks</h2>
            <p class="text-[13px] text-gray-500 dark:text-zinc-400 mt-2 font-medium tracking-wide">Manage and update progress for assignments across all modules.</p>
        </div>
        <div class="flex items-center gap-4 mt-6 md:mt-0">
            <form action="{{ route('tasks.index') }}" method="GET" class="flex items-center w-56">
                <x-custom-select 
                    name="sort" 
                    :submit="true"
                    value="{{ request('sort', 'asc') }}"
                    :options="['asc' => 'Deadline: Paling Dekat', 'desc' => 'Deadline: Paling Jauh']" 
                    class="bg-white dark:bg-zinc-900 border-gray-100 dark:border-zinc-800 text-[13px] font-bold rounded-2xl px-5 py-3.5"
                />
            </form>
            <button @click="Livewire.dispatch('openTaskForm')" class="inline-block bg-[#D0F849] hover:bg-[#bceb23] text-gray-900 px-8 py-3.5 text-sm font-black rounded-2xl shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all">
                + Create New Task
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

    <div class="bg-white dark:bg-zinc-900 shadow-xl shadow-gray-100/20 border border-gray-100/50 dark:border-zinc-800 rounded-[40px] overflow-hidden pt-4 pb-4">
        <table class="w-full divide-y divide-gray-50 dark:divide-zinc-800" style="table-layout: fixed !important;">
            <colgroup>
                <col style="width: 55%;">
                <col style="width: 15%;">
                <col style="width: 15%;">
                <col style="width: 10%;">
                <col style="width: 5%;">
            </colgroup>
            <thead class="bg-white dark:bg-zinc-900">
                <tr>
                    <th scope="col" class="px-10 py-6 text-left text-[10px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.25em]">Task Details</th>
                    <th scope="col" class="px-8 py-6 text-left text-[10px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.25em]">Assignee</th>
                    <th scope="col" class="px-8 py-6 text-left text-[10px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.25em]">Due Date</th>
                    <th scope="col" class="px-8 py-6 text-left text-[10px] font-black text-gray-400 dark:text-zinc-500 uppercase tracking-[0.25em]">Status</th>
                    <th scope="col" class="relative px-8 py-6 text-right"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            
            @if(isset($tasks) && $tasks->count() > 0)
                @php
                    $statusGroups = [
                        'pending' => ['title' => 'To Do / Pending', 'bg' => 'bg-[#f8f149]/10', 'text' => 'text-gray-900 dark:text-white', 'border' => 'border-[#f8f149]/20', 'accent' => '#f8f149'],
                        'in_progress' => ['title' => 'In Progress', 'bg' => 'bg-[#49f8f1]/10', 'text' => 'text-gray-900 dark:text-white', 'border' => 'border-[#49f8f1]/20', 'accent' => '#49f8f1'],
                        'completed' => ['title' => 'Completed', 'bg' => 'bg-[#49f8b4]/10', 'text' => 'text-gray-900 dark:text-white', 'border' => 'border-[#49f8b4]/20', 'accent' => '#49f8b4']
                    ];
                @endphp
                
                @foreach($statusGroups as $statusKey => $statusMeta)
                    @if(isset($tasks[$statusKey]) && $tasks[$statusKey]->count() > 0)
                        <!-- Group Header & Tasks Wrapper -->
                        <tbody x-data="{ open: true }" class="bg-white dark:bg-zinc-900 divide-y divide-gray-50 dark:divide-zinc-800 border-b border-gray-50 dark:border-zinc-800">
                            <!-- Header Row -->
                            <tr @click="open = !open" class="cursor-pointer hover:bg-gray-50/50 dark:hover:bg-zinc-800/50 select-none transition-colors">
                                <td colspan="5" class="px-10 py-5 text-[11px] font-black uppercase tracking-[0.2em] {{ $statusMeta['text'] }} {{ $statusMeta['bg'] }} border-y {{ $statusMeta['border'] }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <span class="w-2.5 h-2.5 rounded-full mr-3 shadow-sm" style="background-color: {{ $statusMeta['accent'] }}"></span>
                                            {{ $statusMeta['title'] }} 
                                            <span class="ml-4 py-1 px-3 bg-white dark:bg-zinc-800 rounded-full text-[10px] border {{ $statusMeta['border'] }} shadow-sm font-black">{{ $tasks[$statusKey]->count() }}</span>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 dark:text-zinc-500 transform transition-transform duration-300" :class="{ 'rotate-180': !open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Tasks Rows -->
                            @foreach($tasks[$statusKey] as $task)
                                <tr x-show="open" x-transition.opacity.duration.300ms class="hover:bg-indigo-50/20 dark:hover:bg-zinc-800/30 transition-colors group">
                                    <td class="px-10 py-6 overflow-hidden max-w-0">
                                        <div class="text-[15px] font-black text-gray-900 dark:text-zinc-100 group-hover:text-gray-900 dark:group-hover:text-[#D0F849] transition-colors truncate mb-1.5" title="{{ $task->title }}">
                                            {{ $task->title }}
                                        </div>
                                        <div class="text-[12px] text-gray-400 dark:text-zinc-500 font-bold line-clamp-1 h-4 tracking-wide" title="{{ $task->description }}">
                                            {{ $task->description ?? 'No description provided' }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap overflow-hidden">
                                        <div class="flex items-center">
                                            <div class="h-9 w-9 flex-shrink-0 rounded-[14px] bg-gray-900 dark:bg-zinc-800 flex items-center justify-center text-[#D0F849] font-black text-[12px] mr-4 shadow-md group-hover:scale-110 transition-transform">
                                                {{ strtoupper(substr($task->assignee->name ?? '?', 0, 1)) }}
                                            </div>
                                            <span class="text-[13px] font-black text-gray-700 dark:text-zinc-300 truncate block tracking-wide">{{ $task->assignee->name ?? 'Unassigned' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap overflow-hidden text-ellipsis">
                                        <span class="inline-block text-[10px] font-black uppercase tracking-[0.15em] px-4 py-1.5 bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700 rounded-2xl shadow-sm {{ $task->due_date && $task->due_date < now() && $task->status !== 'completed' ? 'text-[#fb8c89] border-[#fb8c89]/20 bg-[#fb8c89]/5' : 'text-gray-400 dark:text-zinc-400' }}">
                                            {{ $task->due_date ? $task->due_date->format('d M, Y') : 'No Date' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-left">
                                        <span class="text-[10px] font-black text-gray-600 dark:text-zinc-500 uppercase tracking-[0.2em] {{ $task->status === 'completed' ? 'dark:text-[#49f8b4]' : '' }}">
                                            {{ str_replace('_', ' ', $task->status) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right">
                                        <button @click="Livewire.dispatch('openTaskForm', { id: {{ $task->id }} })" class="inline-flex items-center justify-center w-10 h-10 text-gray-300 dark:text-zinc-600 hover:text-gray-900 dark:hover:text-zinc-100 transition-all hover:bg-[#D0F849]/10 rounded-2xl">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                @endforeach
            @else
                <tbody>
                    <tr>
                        <td colspan="5" class="px-10 py-20 text-center">
                            <div class="flex justify-center mb-6">
                                <div class="w-16 h-16 bg-gray-50 dark:bg-zinc-800 rounded-3xl flex items-center justify-center">
                                    <svg class="h-8 w-8 text-gray-300 dark:text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white tracking-tight">No tasks found</h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-zinc-500 font-medium tracking-wide">Get started by creating a new task.</p>
                        </td>
                    </tr>
                </tbody>
            @endif
        </table>
    </div>

    <!-- Livewire Task Form Slide-over -->
    <livewire:tasks.task-form />

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('taskSaved', (message) => {
                // Flash message usually handled by session, but here we can just reload or use sweetalert
                window.location.reload();
            });
        });
    </script>
    </div>
@endsection
