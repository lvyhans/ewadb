<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
          
    <title>@yield('title', 'CRM Dashboard') - {{ config('app.name') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/heroicons@1.0.6/outline/style.css">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        purple: {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            200: '#e9d5ff',
                            300: '#d8b4fe',
                            400: '#c084fc',
                            500: '#a855f7',
                            600: '#9333ea',
                            700: '#7c3aed',
                            800: '#6b21a8',
                            900: '#581c87',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-in': 'slideIn 0.6s ease-out',
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s ease-in-out infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideIn: {
                            '0%': { opacity: '0', transform: 'translateX(-10px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        }
                    },
                    backdropBlur: {
                        xs: '2px',
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js for interactive components -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Styles -->
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .glass-dark {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Alpine.js cloak - hide until Alpine loads */
        [x-cloak] { 
            display: none !important; 
        }
        
        /* ULTIMATE NOTIFICATION DROPDOWN FIX - ALWAYS ON TOP */
        .notification-dropdown-panel {
            position: fixed !important;
            z-index: 2147483647 !important; /* Maximum z-index value */
            transform: none !important;
            will-change: auto !important;
            backface-visibility: visible !important;
            perspective: none !important;
            contain: none !important;
            isolation: auto !important;
            mix-blend-mode: normal !important;
            filter: none !important;
            clip: auto !important;
            clip-path: none !important;
            mask: none !important;
            overflow: visible !important;
            pointer-events: auto !important;
            
            /* Force break out of any stacking context */
            top: 70px !important;
            right: 20px !important;
            left: auto !important;
            bottom: auto !important;
            
            /* Ensure it's rendered in its own layer */
            transform: translate3d(0, 0, 0) !important;
            
            /* Override any parent constraints */
            max-height: none !important;
            max-width: none !important;
        }
        
        /* When Alpine.js shows the dropdown - override display:none */
        #global-notification-dropdown[x-show="open"]:not([style*="display: none"]) {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* Ensure notification button container doesn't clip */
        .notification-container {
            position: static !important;
            overflow: visible !important;
            contain: none !important;
            isolation: auto !important;
            transform: none !important;
            z-index: auto !important;
        }
        
        /* Force header to not clip children */
        .topbar {
            overflow: visible !important;
            contain: none !important;
            isolation: auto !important;
            transform: none !important;
            z-index: auto !important;
        }
        
        /* Force main layout containers to not clip */
        .main-content,
        .flex,
        .relative {
            overflow: visible !important;
            contain: none !important;
            isolation: auto !important;
        }
        
        /* Ensure dropdown shows with backdrop blur */
        .notification-dropdown-panel::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 0.75rem;
            z-index: -1;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .nav-item {
            position: relative;
            overflow: hidden;
        }
        
        .nav-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .nav-item:hover::before {
            left: 100%;
        }
        
        /* Z-index layers for proper stacking */
        .notification-dropdown-panel {
            position: fixed !important;
            z-index: 999999 !important;
            top: 70px !important;
            right: 20px !important;
        }
        
        .notification-dropdown {
            position: fixed !important;
            z-index: 999999 !important;
        }
        
        .dropdown-menu {
            z-index: 99999 !important;
        }
        
        .modal-backdrop {
            z-index: 9998 !important;
        }
        
        .sidebar {
            z-index: 9997 !important;
        }
        
        /* Override any parent overflow hidden */
        .notification-dropdown-panel {
            overflow: visible !important;
        }
        
        /* Hide elements initially with x-cloak */
        [x-cloak] { 
            display: none !important; 
        }
        
        .main-content {
            z-index: 1 !important;
        }
        
        /* Modal z-index should be higher than topbar */
        .modal {
            z-index: 9999999999 !important;
        }
        
        /* Ensure follow-up modals always appear on top */
        #followupModal,
        #editFollowupModal {
            z-index: 9999 !important;
            position: fixed !important;
        }
        
        /* Alert container should be above everything except modals */
        #alertContainer {
            z-index: 9998 !important;
            position: fixed !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-purple-50 font-sans min-h-screen">
    <!-- Background decorative elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-600/20 rounded-full blur-3xl animate-float"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-purple-400/20 to-pink-600/20 rounded-full blur-3xl animate-float" style="animation-delay: -3s;"></div>
    </div>
    
    <div class="flex h-screen overflow-hidden relative z-10" x-data="{ sidebarOpen: false }">
        
        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="sidebarOpen = false"
             class="fixed inset-0 z-[9998] bg-black bg-opacity-50 lg:hidden"></div>
        
        <!-- Sidebar -->
        <div class="relative flex flex-col w-64 glass-dark transform transition-transform duration-300 ease-in-out lg:translate-x-0 z-[9997]"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
             x-transition>
            
            <!-- Logo -->
            <div class="flex items-center justify-center h-20 px-6 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600/80 to-purple-600/80"></div>
                <div class="relative z-10 flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h1 class="text-xl font-bold text-white">CRM System</h1>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 overflow-y-auto">
                <div class="space-y-2">
                    
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium text-white/80 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                        <div class="w-5 h-5 mr-3 transition-transform duration-200 group-hover:scale-110">
                            <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                            </svg>
                        </div>
                        <span>Dashboard</span>
                    </a>
                    
                    <!-- User Management -->
                    @if(!auth()->user()->isMember())
                    <div x-data="{ open: {{ request()->routeIs('users.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="nav-item w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-white/80 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200 group">
                            <div class="flex items-center">
                                <div class="w-5 h-5 mr-3 transition-transform duration-200 group-hover:scale-110">
                                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                                    </svg>
                                </div>
                                <span>User Management</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-8 mt-2 space-y-1">
                            <a href="{{ route('users.index') }}" 
                               class="block px-4 py-2 text-sm text-white/70 rounded-lg hover:bg-white/10 hover:text-white transition-all duration-200 {{ request()->routeIs('users.index') ? 'bg-white/10 text-white' : '' }}">
                                All Users
                            </a>
                            <a href="{{ route('users.create') }}" 
                               class="block px-4 py-2 text-sm text-white/70 rounded-lg hover:bg-white/10 hover:text-white transition-all duration-200 {{ request()->routeIs('users.create') ? 'bg-white/10 text-white' : '' }}">
                                Add User
                            </a>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Lead Management -->
                    <div x-data="{ open: {{ request()->routeIs('leads.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="nav-item w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-white/80 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200 group {{ request()->routeIs('leads.*') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                            <div class="flex items-center">
                                <div class="w-5 h-5 mr-3 transition-transform duration-200 group-hover:scale-110">
                                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <span>Lead Management</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-8 mt-2 space-y-1">
                            <a href="{{ route('leads.index') }}" 
                               class="block px-4 py-2 text-sm text-white/70 rounded-lg hover:bg-white/10 hover:text-white transition-all duration-200 {{ request()->routeIs('leads.index') ? 'bg-white/10 text-white' : '' }}">
                                All Leads
                            </a>
                            <a href="{{ route('leads.create') }}" 
                               class="block px-4 py-2 text-sm text-white/70 rounded-lg hover:bg-white/10 hover:text-white transition-all duration-200 {{ request()->routeIs('leads.create') ? 'bg-white/10 text-white' : '' }}">
                                Add New Lead
                            </a>
                        </div>
                    </div>
                    
                    <!-- Follow-up Management -->
                    <div x-data="{ open: {{ request()->routeIs('followups.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="nav-item w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-white/80 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200 group {{ request()->routeIs('followups.*') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                            <div class="flex items-center">
                                <div class="w-5 h-5 mr-3 transition-transform duration-200 group-hover:scale-110">
                                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <span>Follow-ups</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-8 mt-2 space-y-1">
                            <a href="{{ route('followups.dashboard') }}" 
                               class="block px-4 py-2 text-sm text-white/70 rounded-lg hover:bg-white/10 hover:text-white transition-all duration-200 {{ request()->routeIs('followups.dashboard') ? 'bg-white/10 text-white' : '' }}">
                                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                            </a>
                            <a href="{{ route('followups.today') }}" 
                               class="block px-4 py-2 text-sm text-white/70 rounded-lg hover:bg-white/10 hover:text-white transition-all duration-200 {{ request()->routeIs('followups.today') ? 'bg-white/10 text-white' : '' }}">
                                <i class="fas fa-calendar-day mr-2"></i> Today's Follow-ups
                            </a>
                            <a href="{{ route('followups.overdue') }}" 
                               class="block px-4 py-2 text-sm text-white/70 rounded-lg hover:bg-white/10 hover:text-white transition-all duration-200 {{ request()->routeIs('followups.overdue') ? 'bg-white/10 text-white' : '' }}">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Overdue Follow-ups
                            </a>
                        </div>
                    </div>
                    
                    <!-- Application Management -->
                    <div x-data="{ open: {{ request()->routeIs('applications.*') ? 'true' : 'false' }} }">
                        <button @click="open = !open" 
                                class="nav-item w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-white/80 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200 group {{ request()->routeIs('applications.*') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                            <div class="flex items-center">
                                <div class="w-5 h-5 mr-3 transition-transform duration-200 group-hover:scale-110">
                                    <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <span>Apply Application</span>
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-8 mt-2 space-y-1">
                            <a href="{{ route('applications.index') }}" 
                               class="block px-4 py-2 text-sm text-white/70 rounded-lg hover:bg-white/10 hover:text-white transition-all duration-200 {{ request()->routeIs('applications.index') ? 'bg-white/10 text-white' : '' }}">
                                All Applications
                            </a>
                            <a href="{{ route('applications.create') }}" 
                               class="block px-4 py-2 text-sm text-white/70 rounded-lg hover:bg-white/10 hover:text-white transition-all duration-200 {{ request()->routeIs('applications.create') ? 'bg-white/10 text-white' : '' }}">
                                New Application
                            </a>
                        </div>
                    </div>
                    
                    <!-- Course Finder -->
                    <a href="{{ route('courses.finder') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium text-white/80 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200 group {{ request()->routeIs('courses.*') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                        <div class="w-5 h-5 mr-3 transition-transform duration-200 group-hover:scale-110">
                            <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <span>Course Finder</span>
                    </a>
                    
                    <!-- Settings -->
                    <a href="{{ route('settings.index') }}" 
                       class="nav-item flex items-center px-4 py-3 text-sm font-medium text-white/80 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200 group {{ request()->routeIs('settings.*') ? 'bg-white/20 text-white shadow-lg' : '' }}">
                        <div class="w-5 h-5 mr-3 transition-transform duration-200 group-hover:scale-110">
                            <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span>Settings</span>
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            
            <!-- Top Bar -->
            <header class="glass-effect border-b border-white/20 shadow-sm topbar">
                <div class="flex items-center justify-between px-6 py-4">
                    <div class="flex items-center space-x-4">
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="p-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-white/50 transition-all duration-200 lg:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <div>
                            <h2 class="text-2xl font-bold gradient-text">@yield('page-title', 'Dashboard')</h2>
                            <p class="text-sm text-gray-600 mt-1">Welcome back to your workspace</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   class="block w-64 pl-10 pr-3 py-2 border border-white/20 rounded-xl bg-white/50 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all duration-200" 
                                   placeholder="Search...">
                        </div>
                        
                        <!-- Notifications -->
                        <div class="notification-container relative" x-data="{ unreadCount: $store.notifications.unreadCount }">
                            <button @click="window.toggleNotificationDropdown();" 
                                    class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-white/50 rounded-xl transition-all duration-200 group">
                                <svg class="w-6 h-6 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a4 4 0 00-8 0v3L2 17h5m8 0v1a3 3 0 01-6 0v-1m6 0H9"></path>
                                </svg>
                                <span x-show="$store.notifications.unreadCount > 0" 
                                      x-text="$store.notifications.unreadCount" 
                                      class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center animate-pulse"></span>
                            </button>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="flex items-center p-2 bg-white/50 rounded-xl hover:bg-white/70 transition-all duration-200 group">
                                <img class="w-8 h-8 rounded-lg mr-2 ring-2 ring-white/50" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=667eea&color=fff" 
                                     alt="{{ auth()->user()->name }}">
                                <div class="hidden sm:block text-left">
                                    <span class="text-sm font-medium text-gray-900 block">{{ auth()->user()->name }}</span>
                                    <span class="text-xs text-gray-600">{{ Str::limit(auth()->user()->email, 20) }}</span>
                                </div>
                                <svg class="w-4 h-4 ml-2 text-gray-500 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div x-show="open" 
                                 @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute right-0 w-56 mt-2 origin-top-right glass-effect rounded-xl shadow-xl ring-1 ring-black/5 z-[99998]">
                                <div class="p-2">
                                    <div class="px-3 py-2 border-b border-white/20">
                                        <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-600">{{ auth()->user()->email }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('profile.edit') }}" 
                                           class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-white/50 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            Profile
                                        </a>
                                        <a href="#" 
                                           class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-white/50 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Settings
                                        </a>
                                        <div class="border-t border-white/20 my-2"></div>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="flex items-center w-full px-3 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                                </svg>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto main-content relative z-[1]">
                <div class="container mx-auto px-6 py-8">
                    @if(session('success'))
                        <div class="mb-6 glass-effect border border-green-200 text-green-800 px-6 py-4 rounded-xl animate-fade-in" role="alert">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 glass-effect border border-red-200 text-red-800 px-6 py-4 rounded-xl animate-fade-in" role="alert">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="font-medium">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    <div class="animate-fade-in">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    @stack('scripts')
    
    <script>
        // Initialize Alpine.js stores
        document.addEventListener('alpine:init', () => {
            Alpine.store('notifications', {
                unreadCount: 0
            });
        });
        
        // Notification Dropdown Component
        function notificationDropdown() {
            return {
                open: false,
                loading: false,
                notifications: [],
                unreadCount: 0,
                
                async init() {
                    // Set up global reference for toggle function
                    window.notificationDropdown = this;
                    
                    // Only fetch unread count, not full notifications
                    await this.fetchUnreadCount();
                    
                    // Update global count
                    window.updateNotificationCount(this.unreadCount);
                    
                    // Poll for new notifications every 30 seconds
                    setInterval(async () => {
                        await this.fetchUnreadCount();
                        window.updateNotificationCount(this.unreadCount);
                    }, 30000);
                },
                
                async toggleDropdown() {
                    this.open = !this.open;
                    
                    if (this.open && this.notifications.length === 0) {
                        await this.fetchNotifications();
                    }
                },
                
                async fetchNotifications() {
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route("notifications.index") }}', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.notifications = data.notifications || [];
                            this.unreadCount = data.unread_count || 0;
                        }
                    } catch (error) {
                        console.error('Error fetching notifications:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                async fetchUnreadCount() {
                    try {
                        const response = await fetch('{{ route("notifications.unread-count") }}', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.unreadCount = data.unread_count || 0;
                            // Update global count
                            window.updateNotificationCount(this.unreadCount);
                        }
                    } catch (error) {
                        console.error('Error fetching unread count:', error);
                    }
                },
                
                async markAsReadAndRedirect(notification) {
                    console.log('Notification clicked:', notification);
                    
                    try {
                        // Close the dropdown immediately
                        this.open = false;
                        
                        // Mark as read (don't wait for response to avoid delay)
                        fetch(`/notifications/${notification.id}/read`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        }).then(response => {
                            if (response.ok) {
                                console.log('Notification marked as read');
                            }
                        }).catch(error => {
                            console.error('Error marking notification as read:', error);
                        });
                        
                        // Construct the URL with scroll parameter
                        let targetUrl;
                        if (notification.action_url) {
                            const url = new URL(notification.action_url, window.location.origin);
                            url.searchParams.set('scroll', 'bottom');
                            targetUrl = url.toString();
                        } else if (notification.lead_id) {
                            targetUrl = `{{ url('/leads') }}/${notification.lead_id}?scroll=bottom`;
                        }
                        
                        console.log('Redirecting to:', targetUrl);
                        
                        if (targetUrl) {
                            // Navigate to the lead page with scroll parameter
                            window.location.href = targetUrl;
                        }
                        
                        // Update local state
                        notification.read_at = new Date().toISOString();
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                        window.updateNotificationCount(this.unreadCount);
                        
                    } catch (error) {
                        console.error('Error in markAsReadAndRedirect:', error);
                        
                        // Fallback redirect
                        const fallbackUrl = notification.action_url || `{{ url('/leads') }}/${notification.lead_id}`;
                        if (fallbackUrl) {
                            window.location.href = fallbackUrl + '?scroll=bottom';
                        }
                    }
                },
                
                async markAllAsRead() {
                    try {
                        const response = await fetch('{{ route("notifications.mark-all-read") }}', {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        if (response.ok) {
                            this.notifications.forEach(notification => {
                                notification.read_at = new Date().toISOString();
                            });
                            this.unreadCount = 0;
                            // Update global count
                            window.updateNotificationCount(0);
                        }
                    } catch (error) {
                        console.error('Error marking all notifications as read:', error);
                    }
                }
            }
        }
        
        // Lead Management Functions
        function loadTodaysFollowups() {
            window.location.href = "{{ route('followups.today') }}";
        }

        function loadOverdueFollowups() {
            window.location.href = "{{ route('followups.overdue') }}";
        }
        
        // Global notification functions
        window.toggleNotificationDropdown = function() {
            if (window.notificationDropdown) {
                window.notificationDropdown.toggleDropdown();
            } else {
                // Try to find and trigger the dropdown directly
                const dropdown = document.querySelector('#global-notification-dropdown');
                if (dropdown && dropdown.__x) {
                    dropdown.__x.toggleDropdown();
                }
            }
        };
        
        // Update global unread count
        window.updateNotificationCount = function(count) {
            window.notificationUnreadCount = count;
            // Update Alpine.js store
            if (Alpine && Alpine.store) {
                Alpine.store('notifications').unreadCount = count;
            }
        };
    </script>
    
    <!-- Global Notification Dropdown (Outside of all containers) -->
    <div id="global-notification-dropdown" 
         x-data="notificationDropdown()" 
         x-show="open" 
         x-cloak
         @click.away="open = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="notification-dropdown-panel w-96 rounded-xl shadow-xl ring-1 ring-black/5"
         style="position: fixed !important; top: 70px !important; right: 20px !important; z-index: 2147483647 !important;">
        <div class="p-4" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-radius: 0.75rem;">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-900">Notifications</h3>
                <button @click="markAllAsRead()" 
                        x-show="unreadCount > 0"
                        class="text-xs text-blue-600 hover:text-blue-800 transition-colors">
                    Mark all read
                </button>
            </div>
            
            <div x-show="loading" class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
            </div>
            
            <div x-show="!loading && notifications.length === 0" class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a4 4 0 00-8 0v3L2 17h5m8 0v1a3 3 0 01-6 0v-1m6 0H9"></path>
                </svg>
                <p class="text-sm">No notifications</p>
            </div>
            
            <div x-show="!loading" class="space-y-2 max-h-96 overflow-y-auto">
                <template x-for="notification in notifications" :key="notification.id">
                    <div class="flex items-start space-x-3 p-3 rounded-lg hover:bg-white/50 transition-colors cursor-pointer"
                         @click="markAsReadAndRedirect(notification)"
                         :class="notification.read_at ? 'opacity-75' : 'bg-blue-50/50'">
                        <div class="flex-shrink-0">
                            <div class="w-2 h-2 rounded-full mt-2"
                                 :class="notification.read_at ? 'bg-gray-400' : 'bg-blue-500'"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                            <p class="text-xs text-gray-600 mt-1" x-text="notification.message"></p>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-xs text-gray-500" x-text="notification.time_ago"></span>
                                <span x-show="notification.lead_ref_no" 
                                      class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded" 
                                      x-text="notification.lead_ref_no"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <div x-show="notifications.length > 0" class="border-t pt-3 mt-3">
                <a href="{{ route('notifications.index') }}" 
                   class="block text-center text-sm text-blue-600 hover:text-blue-800 transition-colors">
                    View all notifications
                </a>
            </div>
        </div>
    </div>
</body>
</html>