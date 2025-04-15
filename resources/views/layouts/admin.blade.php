<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="IT Support Ticketing System" />
    <meta name="author" content="WGTicket" />
    <title>@yield('title') - WGTicket Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    @stack('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }
        
        .sidebar {
            width: 250px;
            transition: all 0.3s;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .content {
                width: 100%;
                margin-left: 0;
            }
            
            .content-active {
                margin-left: 250px;
            }
        }
        
        .sidebar-link {
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #3b82f6;
        }
        
        .sidebar-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left-color: #3b82f6;
        }
        
        .sidebar-dropdown {
            transition: all 0.3s;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar bg-gray-800 text-white fixed h-full z-20 shadow-lg">
            <div class="flex justify-center items-center h-16 border-b border-gray-700">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-white">WGTicket</a>
            </div>
            <div class="overflow-y-auto h-full pb-20">
                <nav class="mt-5 px-4">
                    <p class="text-gray-400 text-xs uppercase tracking-wider mb-2">Core</p>
                    <a href="{{ route('dashboard') }}" class="sidebar-link flex items-center py-3 px-4 text-gray-300 hover:text-white rounded-lg mb-1 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5 text-center mr-2"></i>
                        <span>Dashboard</span>
                    </a>
                    
                    <p class="text-gray-400 text-xs uppercase tracking-wider mt-6 mb-2">Tickets</p>
                    <a href="{{ route('tickets.index', ['status' => 'waiting']) }}" class="sidebar-link flex items-center py-3 px-4 text-gray-300 hover:text-white rounded-lg mb-1 {{ request()->routeIs('tickets.index') && request()->query('status') == 'waiting' ? 'active' : '' }}">
                        <i class="fas fa-clock w-5 h-5 text-center mr-2"></i>
                        <span>Waiting</span>
                    </a>
                    <a href="{{ route('tickets.index', ['status' => 'in_progress']) }}" class="sidebar-link flex items-center py-3 px-4 text-gray-300 hover:text-white rounded-lg mb-1 {{ request()->routeIs('tickets.index') && request()->query('status') == 'in_progress' ? 'active' : '' }}">
                        <i class="fas fa-spinner w-5 h-5 text-center mr-2"></i>
                        <span>In Progress</span>
                    </a>
                    <a href="{{ route('tickets.index', ['status' => 'done']) }}" class="sidebar-link flex items-center py-3 px-4 text-gray-300 hover:text-white rounded-lg mb-1 {{ request()->routeIs('tickets.index') && request()->query('status') == 'done' ? 'active' : '' }}">
                        <i class="fas fa-check-circle w-5 h-5 text-center mr-2"></i>
                        <span>Completed</span>
                    </a>
                    <a href="{{ route('tickets.index') }}" class="sidebar-link flex items-center py-3 px-4 text-gray-300 hover:text-white rounded-lg mb-1 {{ request()->routeIs('tickets.index') && !request()->query('status') ? 'active' : '' }}">
                        <i class="fas fa-ticket-alt w-5 h-5 text-center mr-2"></i>
                        <span>All Tickets</span>
                    </a>
                    <a href="{{ route('tickets.create') }}" class="sidebar-link flex items-center py-3 px-4 text-gray-300 hover:text-white rounded-lg mb-1 {{ request()->routeIs('tickets.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle w-5 h-5 text-center mr-2"></i>
                        <span>Create Ticket</span>
                    </a>
                    
                    <p class="text-gray-400 text-xs uppercase tracking-wider mt-6 mb-2">Management</p>
                    <div x-data="{ open: false }" class="mb-1">
                        <button @click="open = !open" class="sidebar-link w-full flex items-center justify-between py-3 px-4 text-gray-300 hover:text-white rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-cogs w-5 h-5 text-center mr-2"></i>
                                <span>Settings</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs" :class="{ 'transform rotate-180': open }"></i>
                        </button>
                        <div x-show="open" class="sidebar-dropdown pl-6 mt-1 space-y-1">
                            <a href="{{ route('admin.categories.index') }}" class="flex items-center py-2 px-4 text-gray-300 hover:text-white rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-gray-700' : '' }}">
                                <i class="fas fa-tags w-5 h-5 text-center mr-2"></i>
                                <span>Categories</span>
                            </a>
                            <a href="{{ route('admin.departments.index') }}" class="flex items-center py-2 px-4 text-gray-300 hover:text-white rounded-lg {{ request()->routeIs('admin.departments.*') ? 'bg-gray-700' : '' }}">
                                <i class="fas fa-building w-5 h-5 text-center mr-2"></i>
                                <span>Departments</span>
                            </a>
                        </div>
                    </div>
                </nav>
                
                <div class="border-t border-gray-700 mt-6 pt-4 px-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full bg-gray-600 p-1" src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=random" alt="User avatar">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Admin User' }}</p>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-gray-400 hover:text-white">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content flex-1 ml-0 md:ml-[250px] transition-all duration-300 flex flex-col h-screen">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between h-16 px-4">
                    <button id="sidebarToggle" class="text-gray-500 focus:outline-none md:hidden">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="flex items-center">
                        <div class="relative">
                            <button class="flex items-center text-gray-500 hover:text-gray-700 focus:outline-none">
                                <span class="mr-2 text-sm">{{ Auth::user()->name ?? 'Admin User' }}</span>
                                <img class="h-8 w-8 rounded-full bg-gray-200 p-1" src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=random" alt="User avatar">
                            </button>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-100 p-4">
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="bg-white py-4 px-6 border-t">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-sm text-gray-600">
                        &copy; {{ date('Y') }} WGTicket. All rights reserved.
                    </div>
                    <div class="mt-2 md:mt-0 text-sm text-gray-600">
                        <a href="#" class="hover:text-blue-600">Privacy Policy</a>
                        <span class="mx-2">|</span>
                        <a href="#" class="hover:text-blue-600">Terms of Service</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        // Toggle sidebar
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const content = document.querySelector('.content');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    content.classList.toggle('content-active');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickInsideToggle = sidebarToggle.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickInsideToggle && window.innerWidth < 768 && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                    content.classList.remove('content-active');
                }
            });
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
