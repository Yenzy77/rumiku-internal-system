<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RUMIKU System') }}</title>

    <!-- Dark Mode Initializer -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                }
            }
        }
    </script>



    <livewire:styles />
</head>
<body class="font-sans antialiased bg-[#F8F9FA] dark:bg-zinc-950 text-gray-900 dark:text-zinc-100 h-screen flex overflow-hidden tracking-wide text-[13px] transition-colors duration-300">

    <!-- Slim Sidebar -->
    <aside class="w-24 bg-white dark:bg-zinc-900 border-r border-gray-100 dark:border-zinc-800 flex-shrink-0 flex flex-col transition-all duration-300 shadow-[2px_0_8px_-4px_rgba(0,0,0,0.05)] z-20">
        <!-- Logo -->
        <div class="h-20 flex items-center justify-center border-b border-gray-50/50 dark:border-zinc-800/50 mt-2 mb-2">
            <a href="{{ route('dashboard') }}" class="block p-2 rounded-xl hover:bg-gray-50 dark:hover:bg-zinc-800 transition">
                <img src="{{ asset('logo/FA RUMI KULTURA UTOPIA_Logogram bnw fitted.svg') }}" class="w-10 h-10 object-contain dark:invert" alt="RUMIKU Logo">
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 flex flex-col items-center py-6 space-y-4 overflow-y-auto hide-scrollbar">
            
            <a href="{{ route('dashboard') }}" class="w-12 h-12 flex items-center justify-center rounded-[14px] transition-all duration-200 group relative {{ request()->routeIs('dashboard') || request()->is('/') ? 'bg-[#D0F849] text-gray-900 shadow-sm' : 'bg-gray-50 dark:bg-zinc-800/80 text-gray-500 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-zinc-100' }}" title="Dashboard">
                <svg class="w-6 h-6 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
            </a>

            <a href="{{ route('tasks.index') }}" class="w-12 h-12 flex items-center justify-center rounded-[14px] transition-all duration-200 group relative {{ request()->routeIs('tasks.*') ? 'bg-[#D0F849] text-gray-900 shadow-sm' : 'bg-gray-50 dark:bg-zinc-800/80 text-gray-500 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-zinc-100' }}" title="Team Tasks">
                <svg class="w-6 h-6 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </a>
            
            <a href="{{ route('bookkeeping') }}" class="w-12 h-12 flex items-center justify-center rounded-[14px] transition-all duration-200 group relative {{ request()->routeIs('bookkeeping') ? 'bg-[#D0F849] text-gray-900 shadow-sm' : 'bg-gray-50 dark:bg-zinc-800/80 text-gray-500 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-zinc-100' }}" title="Bookkeeping">
                <svg class="w-6 h-6 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </a>

            <a href="#" class="w-12 h-12 flex items-center justify-center rounded-[14px] transition-all duration-200 group relative text-gray-500 dark:text-zinc-400 bg-gray-50 dark:bg-zinc-800/80 hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-zinc-100" title="Inventory">
                <svg class="w-6 h-6 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
            </a>

            <a href="{{ route('social-media.accounts') }}" class="w-12 h-12 flex items-center justify-center rounded-[14px] transition-all duration-200 group relative {{ request()->routeIs('social-media.accounts') ? 'bg-[#D0F849] text-gray-900 shadow-sm' : 'bg-gray-50 dark:bg-zinc-800/80 text-gray-500 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-zinc-100' }}" title="Social Accounts">
                <svg class="w-6 h-6 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </a>

            <a href="{{ route('social-media.planner') }}" class="w-12 h-12 flex items-center justify-center rounded-[14px] transition-all duration-200 group relative {{ request()->routeIs('social-media.planner') ? 'bg-[#D0F849] text-gray-900 shadow-sm' : 'bg-gray-50 dark:bg-zinc-800/80 text-gray-500 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-zinc-100' }}" title="Content Planner">
                <svg class="w-6 h-6 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </a>

            <a href="{{ route('social-media.analytics') }}" class="w-12 h-12 flex items-center justify-center rounded-[14px] transition-all duration-200 group relative {{ request()->routeIs('social-media.analytics') ? 'bg-[#D0F849] text-gray-900 shadow-sm' : 'bg-gray-50 dark:bg-zinc-800/80 text-gray-500 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-zinc-100' }}" title="Approval & Analytics">
                <svg class="w-6 h-6 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </a>

            <a href="{{ route('omni-channel.inbox') }}" class="w-12 h-12 flex items-center justify-center rounded-[14px] transition-all duration-200 group relative {{ request()->routeIs('omni-channel.*') ? 'bg-[#D0F849] text-gray-900 shadow-sm' : 'bg-gray-50 dark:bg-zinc-800/80 text-gray-500 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-zinc-100' }}" title="Omni-channel">
                <svg class="w-6 h-6 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </a>

        </nav>

        <!-- Bottom Actions (Alpine for Dark Mode) -->
        <div class="py-6 flex flex-col items-center space-y-4" x-data="{
            darkMode: localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
            toggleTheme() {
                this.darkMode = !this.darkMode;
                if(this.darkMode) {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                }
            }
        }">
            
            <button @click="toggleTheme()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-zinc-100 transition group" title="Toggle Theme">
                <!-- Sun Icon -->
                <svg x-show="darkMode" x-cloak class="w-5 h-5 -rotate-90 group-hover:rotate-0 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <!-- Moon Icon -->
                <svg x-show="!darkMode" x-cloak class="w-5 h-5 group-hover:-rotate-12 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-zinc-100 relative transition group" title="Notifications">
                <span class="absolute top-2 right-2.5 h-2 w-2 rounded-full bg-red-500 border-2 border-white dark:border-zinc-800"></span>
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </button>

            <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-zinc-800 text-gray-500 dark:text-zinc-400 hover:bg-gray-100 dark:hover:bg-zinc-700 hover:text-gray-900 dark:hover:text-zinc-100 transition group" title="Settings">
                <svg class="w-5 h-5 group-hover:rotate-45 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </button>

            <div class="h-10 w-10 mt-1 rounded-xl bg-gray-100 dark:bg-zinc-800 overflow-hidden cursor-pointer ring-2 ring-transparent hover:ring-indigo-100 dark:hover:ring-zinc-600 transition-all shadow-sm" title="{{ auth()->check() ? auth()->user()->name : 'User' }}">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->check() ? auth()->user()->name : 'User') }}&color=111827&background=F3F4F6&bold=true" class="h-full w-full object-cover">
            </div>

        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden relative">
        <!-- Content Area with Modern Top Header -->
        <header class="bg-transparent h-24 flex items-center justify-between px-8 flex-shrink-0 z-10">
            <div class="flex flex-col items-start">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-wider">
                    @if(request()->routeIs('dashboard') || request()->is('/'))
                        Dashboard
                    @elseif(request()->routeIs('tasks.*'))
                        Team Tasks
                    @elseif(request()->routeIs('bookkeeping'))
                        Bookkeeping
                    @elseif(request()->routeIs('social-media.accounts'))
                        Social Media Accounts
                    @elseif(request()->routeIs('social-media.planner'))
                        Content Planner
                    @elseif(request()->routeIs('social-media.analytics'))
                        Approval & Analytics
                    @elseif(request()->routeIs('omni-channel.inbox'))
                        Unified Inbox
                    @else
                        @yield('header_title', 'RUMIKU System')
                    @endif
                </h1>
                <p class="text-[13px] text-gray-400 dark:text-zinc-400 mt-2 font-medium tracking-wide">
                    @if(request()->routeIs('dashboard') || request()->is('/'))
                        Monitor your business performance, tasks, and financial overview in one place.
                    @elseif(request()->routeIs('tasks.*'))
                        Manage your team's productivity, track progress, and meet every deadline.
                    @elseif(request()->routeIs('bookkeeping'))
                        Track every transaction, monitor cash flow, and manage your business finances.
                    @elseif(request()->routeIs('social-media.accounts'))
                        Connect and manage all your brand's social media profiles securely.
                    @elseif(request()->routeIs('social-media.planner'))
                        Plan, schedule, and visualize your content strategy across all platforms.
                    @elseif(request()->routeIs('social-media.analytics'))
                        Review pending content and track engagement performance across channels.
                    @elseif(request()->routeIs('omni-channel.inbox'))
                        Centralize all your customer communications from different platforms into one inbox.
                    @else
                        @yield('header_subtitle', 'Welcome to the RUMIKU Internal System dashboard.')
                    @endif
                </p>
            </div>
            
            <!-- Modern Header Actions (e.g., search, date filter mimicking the UI) -->
            <div class="flex items-center space-x-3">
                <button class="h-10 w-10 rounded-full bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 flex items-center justify-center text-gray-500 dark:text-zinc-400 hover:text-gray-900 dark:hover:text-zinc-100 hover:border-gray-300 dark:hover:border-zinc-600 shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <div class="h-10 px-4 rounded-full bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-700 flex items-center justify-center space-x-2 shadow-sm text-sm font-medium text-gray-600 dark:text-zinc-300 cursor-pointer hover:border-gray-300 dark:hover:border-zinc-600 transition">
                    <svg class="w-4 h-4 text-gray-400 dark:text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>{{ date('d M Y') }}</span>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto px-8 pb-24" style="scrollbar-gutter: stable;">
            <div class="w-full min-h-full mt-10">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>

    <style>
        /* Modern Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #E5E7EB; /* gray-200 */
            border-radius: 20px;
            border: 2px solid transparent;
            background-clip: content-box;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #D1D5DB; /* gray-300 */
            background-clip: content-box;
        }

        /* Dark Mode Scrollbar */
        .dark ::-webkit-scrollbar-thumb {
            background: #3F3F46; /* zinc-700 */
            background-clip: content-box;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #52525B; /* zinc-600 */
            background-clip: content-box;
        }

        /* Legacy Custom Scrollbar Class */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        
        .hide-scrollbar {
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }
    </style>

    <livewire:scripts />
</body>
</html>