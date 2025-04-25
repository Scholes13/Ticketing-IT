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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @stack('styles')
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --dark: #334155;
            --light: #f9fafb;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 64px;
        }
        
        * {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
        }
        
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            z-index: 100;
            position: fixed;
            background: #ffffff;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border-right: 1px solid #e5e7eb;
            transform: translateX(-100%);
            opacity: 0;
        }
        
        .sidebar.active {
            transform: translateX(0);
            opacity: 1;
        }
        
        /* Hide scrollbar but keep functionality */
        .sidebar-content {
            height: calc(100vh - 5rem);
            overflow-y: auto;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
            padding-bottom: 2rem;
        }
        
        .sidebar-content::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }
        
        .sidebar-collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar-collapsed .sidebar-text,
        .sidebar-collapsed .sidebar-category-text {
            display: none !important;
        }
        
        .sidebar-collapsed .sidebar-link {
            justify-content: center;
            padding: 0.75rem;
            margin-left: 6px;
            margin-right: 6px;
            width: 40px;
            height: 40px;
        }
        
        .sidebar-collapsed .sidebar-link i {
            margin-right: 0;
            font-size: 1.15rem;
        }
        
        .sidebar-collapsed .sidebar-brand {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        
        .sidebar-collapsed .sidebar-brand span {
            display: none;
        }
        
        .sidebar-collapsed .user-info {
            display: none;
        }
        
        .sidebar-collapsed .sidebar-toggle-btn {
            right: -12px;
        }
        
        .sidebar-collapsed .sidebar-category {
            text-align: center;
            padding: 1rem 0 0.5rem 0;
            display: flex;
            justify-content: center;
        }
        
        @media (min-width: 768px) {
            .sidebar {
                transform: translateX(0);
                opacity: 1;
            }
            .content {
                margin-left: var(--sidebar-width);
                transition: margin-left 0.3s ease;
                width: calc(100% - var(--sidebar-width));
            }
            .sidebar-collapsed + .content {
                margin-left: var(--sidebar-collapsed-width);
                width: calc(100% - var(--sidebar-collapsed-width));
            }
        }
        
        /* Enhance menu link aesthetics */
        .sidebar-link {
            position: relative;
            overflow: hidden;
            border-left: 3px solid transparent;
            display: flex;
            align-items: center;
            margin: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            color: #64748b;
            font-weight: 500;
        }
        
        .sidebar-link:hover {
            background-color: #f1f5f9;
            color: var(--primary);
            transform: translateX(2px);
        }
        
        .sidebar-link.active {
            background-color: #f8faff;
            border-left-color: var(--primary);
            color: var(--primary);
            font-weight: 600;
        }
        
        .sidebar-link.active i {
            color: var(--primary);
        }
        
        .sidebar-link i {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-collapsed .sidebar-link:hover {
            transform: scale(1.05);
            background-color: #f1f5f9;
        }
        
        /* Improve dropdown items */
        .sidebar-dropdown a {
            transition: all 0.2s ease;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
            color: #64748b;
        }
        
        .sidebar-dropdown a:hover {
            background-color: #f1f5f9;
            color: var(--primary);
            transform: translateX(2px);
        }
        
        /* Category headers */
        .sidebar-category {
            padding: 1rem 1.5rem 0.5rem 1.5rem;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: #94a3b8;
            font-weight: 600;
        }
        
        .content {
            transition: margin-left 0.3s ease;
        }
        
        .content-expanded {
            margin-left: 0;
        }
        
        .badge {
            display: inline-block;
            border-radius: 9999px;
            font-weight: 500;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            text-align: center;
            vertical-align: middle;
            transition: all 0.15s ease;
            position: relative;
            overflow: hidden;
        }
        
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .avatar {
            transition: transform 0.3s ease;
        }
        
        .avatar:hover {
            transform: scale(1.1);
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .sidebar-toggle-btn {
            position: absolute;
            right: -12px;
            top: 50%;
            transform: translateY(-50%);
            background: #ffffff;
            border: 1px solid #e5e7eb;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 10;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 0;
        }
        
        .sidebar-toggle-btn:hover {
            background: #f8fafc;
            transform: translateY(-50%) scale(1.1);
            color: var(--primary);
        }
        
        .collapse-icon {
            width: 16px;
            height: 16px;
            stroke-width: 2;
            color: #64748b;
        }
        
        .tooltip {
            position: relative;
        }
        
        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        
        .tooltip-text {
            visibility: hidden;
            width: auto;
            background-color: #334155;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 10px;
            position: absolute;
            z-index: 1;
            left: 110%;
            opacity: 0;
            transition: opacity 0.3s;
            white-space: nowrap;
            font-size: 12px;
        }
        
        .tooltip-text::after {
            content: "";
            position: absolute;
            top: 50%;
            right: 100%;
            margin-top: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent #334155 transparent transparent;
        }
        
        .sidebar-dropdown {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        
        .sidebar-dropdown.active {
            max-height: 500px;
            transition: max-height 0.5s ease-in;
        }
        
        /* Adjust the tooltip position for collapsed sidebar */
        .sidebar-collapsed .tooltip .tooltip-text {
            left: 50px;
            top: 50%;
            transform: translateY(-50%);
        }
        
        /* Top navigation design */
        .top-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.75rem 1.5rem;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <div class="flex justify-between items-center h-16 px-6 border-b border-gray-200 relative sidebar-brand">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800 flex items-center">
                    <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center mr-2">
                        <i class="fas fa-ticket-alt text-white"></i>
                    </div>
                    <span class="sidebar-text">WGTicket</span>
                </a>
                <button id="sidebarCollapseBtn" class="sidebar-toggle-btn hidden md:block">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="collapse-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>
            </div>
            <div class="sidebar-content">
                <nav class="mt-6 px-3">
                    <div class="sidebar-category">
                        <span class="sidebar-category-text">Core</span>
                    </div>
                    <a href="{{ route('dashboard') }}" class="sidebar-link py-2 px-3 rounded-lg mb-1 tooltip {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt w-5 h-5 text-center mr-3"></i>
                        <span class="sidebar-text">Dashboard</span>
                        <span class="tooltip-text">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('reporting') }}" class="sidebar-link py-2 px-3 rounded-lg mb-1 tooltip {{ request()->routeIs('reporting') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar w-5 h-5 text-center mr-3"></i>
                        <span class="sidebar-text">Reporting</span>
                        <span class="tooltip-text">Reporting</span>
                    </a>
                    
                    <div class="sidebar-category">
                        <span class="sidebar-category-text">Tickets</span>
                    </div>
                    <a href="{{ route('tickets.waiting') }}" class="sidebar-link py-2 px-3 rounded-lg mb-1 tooltip {{ request()->routeIs('tickets.waiting') ? 'active' : '' }}">
                        <i class="fas fa-hourglass-start mr-2 w-5 text-center"></i>
                        <span class="sidebar-text">Waiting Tickets</span>
                        <span class="tooltip-text">Waiting Tickets</span>
                    </a>
                    <a href="{{ route('tickets.in-progress') }}" class="sidebar-link py-2 px-3 rounded-lg mb-1 tooltip {{ request()->routeIs('tickets.in-progress') ? 'active' : '' }}">
                        <i class="fas fa-spinner mr-2 w-5 text-center"></i>
                        <span class="sidebar-text">In Progress Tickets</span>
                        <span class="tooltip-text">In Progress Tickets</span>
                    </a>
                    <a href="{{ route('tickets.done') }}" class="sidebar-link py-2 px-3 rounded-lg mb-1 tooltip {{ request()->routeIs('tickets.done') ? 'active' : '' }}">
                        <i class="fas fa-check-circle mr-2 w-5 text-center"></i>
                        <span class="sidebar-text">Done Tickets</span>
                        <span class="tooltip-text">Done Tickets</span>
                    </a>
                    <a href="{{ route('tickets.all') }}" class="sidebar-link py-2 px-3 rounded-lg mb-1 tooltip {{ request()->routeIs('tickets.all') ? 'active' : '' }}">
                        <i class="fas fa-ticket-alt mr-2 w-5 text-center"></i>
                        <span class="sidebar-text">All Tickets</span>
                        <span class="tooltip-text">All Tickets</span>
                    </a>
                    <a href="{{ route('tickets.create') }}" class="sidebar-link py-2 px-3 rounded-lg mb-1 tooltip {{ request()->routeIs('tickets.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle mr-2 w-5 text-center"></i>
                        <span class="sidebar-text">Create Ticket</span>
                        <span class="tooltip-text">Create Ticket</span>
                    </a>
                    
                    <div class="sidebar-category">
                        <span class="sidebar-category-text">Management</span>
                    </div>
                    <a href="{{ route('admin.categories.index') }}" class="sidebar-link py-2 px-3 rounded-lg mb-1 tooltip {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-tags w-5 h-5 text-center mr-3"></i>
                        <span class="sidebar-text">Categories</span>
                        <span class="tooltip-text">Categories</span>
                    </a>
                    <a href="{{ route('admin.departments.index') }}" class="sidebar-link py-2 px-3 rounded-lg mb-1 tooltip {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                        <i class="fas fa-building w-5 h-5 text-center mr-3"></i>
                        <span class="sidebar-text">Departments</span>
                        <span class="tooltip-text">Departments</span>
                    </a>
                    <a href="{{ route('staff.index') }}" class="sidebar-link py-2 px-3 rounded-lg mb-1 tooltip {{ request()->routeIs('staff.*') ? 'active' : '' }}">
                        <i class="fas fa-users w-5 h-5 text-center mr-3"></i>
                        <span class="sidebar-text">Staff</span>
                        <span class="tooltip-text">Staff Management</span>
                    </a>
                </nav>
                
                <div class="border-t border-gray-200 mt-8 pt-4 px-6 user-info">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 avatar">
                            <img class="h-10 w-10 rounded-full bg-gray-100 p-1 border border-gray-200" src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=f1f5f9&color=4f46e5" alt="User avatar">
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-800">{{ Auth::user()->name ?? 'Admin User' }}</p>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-xs text-gray-500 hover:text-indigo-600">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Content -->
        <div id="content" class="content flex-1 flex flex-col h-screen">
            <!-- Top Navbar -->
            <header class="bg-white shadow-sm z-10">
                <div class="flex items-center justify-between h-16 px-6">
                    <button id="sidebarToggle" class="text-gray-500 focus:outline-none md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="flex items-center text-gray-700 cursor-pointer group">
                                <span class="mr-3 text-sm font-medium group-hover:text-indigo-600">{{ Auth::user()->name ?? 'Admin User' }}</span>
                                <img class="h-8 w-8 rounded-full bg-gray-100 p-1 border border-gray-200 avatar" src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=f1f5f9&color=4f46e5" alt="User avatar">
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="bg-white py-4 px-6 border-t">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-sm text-gray-600 mb-2 md:mb-0">
                        &copy; {{ date('Y') }} WGTicket. All rights reserved.
                    </div>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-600 hover:text-indigo-600 text-sm">Privacy Policy</a>
                        <span class="text-gray-400">|</span>
                        <a href="#" class="text-gray-600 hover:text-indigo-600 text-sm">Terms of Service</a>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Setup variables
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const sidebarCollapseBtn = document.getElementById('sidebarCollapseBtn');
            const toggleIcon = sidebarCollapseBtn ? sidebarCollapseBtn.querySelector('svg path') : null;
            
            // Check localStorage for saved sidebar state
            const sidebarState = localStorage.getItem('wgticket_sidebar_collapsed');
            
            // Apply the saved state on page load
            if (sidebarState === 'true') {
                sidebar.classList.add('sidebar-collapsed');
                content.style.marginLeft = 'var(--sidebar-collapsed-width)';
                content.style.width = 'calc(100% - var(--sidebar-collapsed-width))';
                
                // Update the toggle button icon direction - point RIGHT when collapsed
                if (toggleIcon) {
                    toggleIcon.setAttribute('d', 'M8.25 4.5l7.5 7.5-7.5 7.5');
                }
            } else {
                content.style.marginLeft = 'var(--sidebar-width)';
                content.style.width = 'calc(100% - var(--sidebar-width))';
                
                // Set the toggle button icon direction - point LEFT when expanded
                if (toggleIcon) {
                    toggleIcon.setAttribute('d', 'M15.75 19.5L8.25 12l7.5-7.5');
                }
            }
            
            // Toggle sidebar for mobile
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
            
            // Toggle sidebar collapse for desktop
            if (sidebarCollapseBtn) {
                sidebarCollapseBtn.addEventListener('click', function() {
                    // Toggle the sidebar collapsed class
                    sidebar.classList.toggle('sidebar-collapsed');
                    
                    // Update content margin to be responsive to sidebar state
                    const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
                    
                    // Save the state to localStorage
                    localStorage.setItem('wgticket_sidebar_collapsed', isCollapsed);
                    
                    // Toggle the chevron icon direction
                    const icon = sidebarCollapseBtn.querySelector('svg path');
                    
                    if (isCollapsed) {
                        content.style.marginLeft = 'var(--sidebar-collapsed-width)';
                        content.style.width = 'calc(100% - var(--sidebar-collapsed-width))';
                        // Point RIGHT when sidebar is collapsed
                        icon.setAttribute('d', 'M8.25 4.5l7.5 7.5-7.5 7.5');
                    } else {
                        content.style.marginLeft = 'var(--sidebar-width)';
                        content.style.width = 'calc(100% - var(--sidebar-width))';
                        // Point LEFT when sidebar is expanded
                        icon.setAttribute('d', 'M15.75 19.5L8.25 12l7.5-7.5');
                    }
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickInsideToggle = sidebarToggle && sidebarToggle.contains(event.target);
                
                if (!isClickInsideSidebar && !isClickInsideToggle && window.innerWidth < 768 && sidebar.classList.contains('active')) {
                    sidebar.classList.remove('active');
                }
            });
            
            // Make sidebar active by default on mobile load
            if (window.innerWidth < 768) {
                sidebar.classList.add('active');
            }
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
