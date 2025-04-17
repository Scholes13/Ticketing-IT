@extends('layouts.admin')

@section('title', ucfirst($status) . ' Tickets')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    .card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .status-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .priority-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 6px;
    }
    
    .chart-container {
        position: relative;
        height: 150px;
    }
    
    .ticket-table tr:hover {
        background-color: #f1f5f9;
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }
    
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    
    .table-row-hover:hover {
        background-color: #f8fafc;
    }
    
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                @if($status == 'all')
                    Semua Tiket
                @elseif($status == 'waiting')
                    Tiket Menunggu
                @elseif($status == 'in_progress')
                    Tiket Dalam Proses
                @elseif($status == 'done')
                    Tiket Selesai
                @endif
            </h1>
            <p class="text-gray-600">Manajemen tiket dan layanan dukungan</p>
        </div>
        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
            <div class="relative">
                <form action="{{ $status == 'all' ? route('tickets.all') : ($status == 'waiting' ? route('tickets.waiting') : ($status == 'in_progress' ? route('tickets.in-progress') : route('tickets.done'))) }}" method="GET">
                    <div class="flex">
                        <input type="text" name="search" placeholder="Cari tiket..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full" value="{{ request()->query('search') }}">
                        <button type="submit" class="ml-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </form>
            </div>
            <a href="{{ route('tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i> Buat Tiket Baru
            </a>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Tickets -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 card-hover">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Tiket</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $statusCounts['all'] }}</h3>
                        <p class="text-blue-500 text-sm mt-2"><i class="fas fa-ticket-alt mr-1"></i> Semua tiket</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class="fas fa-ticket-alt text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-between items-center">
                <a href="{{ route('tickets.all') }}" class="text-blue-600 text-sm font-medium hover:text-blue-800">Lihat Detail</a>
                <i class="fas fa-chevron-right text-blue-600 text-sm"></i>
            </div>
        </div>
        <!-- Waiting Tickets -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 card-hover">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Menunggu</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $statusCounts['waiting'] }}</h3>
                        <p class="text-amber-500 text-sm mt-2"><i class="fas fa-hourglass-start mr-1"></i> Belum diproses</p>
                    </div>
                    <div class="bg-amber-100 p-4 rounded-full">
                        <i class="fas fa-hourglass-start text-amber-600 text-2xl animate-pulse"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-between items-center">
                <a href="{{ route('tickets.waiting') }}" class="text-amber-600 text-sm font-medium hover:text-amber-800">Lihat Detail</a>
                <i class="fas fa-chevron-right text-amber-600 text-sm"></i>
            </div>
        </div>
        <!-- In Progress -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 card-hover">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Dalam Proses</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $statusCounts['in_progress'] }}</h3>
                        <p class="text-cyan-500 text-sm mt-2"><i class="fas fa-spinner mr-1"></i> Sedang ditangani</p>
                    </div>
                    <div class="bg-cyan-100 p-4 rounded-full">
                        <i class="fas fa-spinner text-cyan-600 text-2xl animate-spin"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-between items-center">
                <a href="{{ route('tickets.in-progress') }}" class="text-cyan-600 text-sm font-medium hover:text-cyan-800">Lihat Detail</a>
                <i class="fas fa-chevron-right text-cyan-600 text-sm"></i>
            </div>
        </div>
        <!-- Completed -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 card-hover">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Selesai</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $statusCounts['done'] }}</h3>
                        <p class="text-emerald-500 text-sm mt-2"><i class="fas fa-check-circle mr-1"></i> Telah diselesaikan</p>
                    </div>
                    <div class="bg-emerald-100 p-4 rounded-full">
                        <i class="fas fa-check-circle text-emerald-600 text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-between items-center">
                <a href="{{ route('tickets.done') }}" class="text-emerald-600 text-sm font-medium hover:text-emerald-800">Lihat Detail</a>
                <i class="fas fa-chevron-right text-emerald-600 text-sm"></i>
            </div>
        </div>
    </div>
    
    <!-- Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Filter Panel -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden lg:col-span-1">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-filter text-blue-500 mr-2"></i> Filter Tiket
                </h3>
            </div>
            <div class="p-6">
                <form action="{{ $status == 'all' ? route('tickets.all') : ($status == 'waiting' ? route('tickets.waiting') : ($status == 'in_progress' ? route('tickets.in-progress') : route('tickets.done'))) }}" method="GET">
                    <div class="space-y-4">
                        <!-- Priority Filter -->
                        <div>
                            <label class="text-sm font-medium text-gray-700 block mb-2">Prioritas</label>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="flex items-center">
                                    <input type="radio" name="priority" value="low" id="priority_low" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ request()->query('priority') == 'low' ? 'checked' : '' }}>
                                    <label for="priority_low" class="text-sm text-gray-700 flex items-center">
                                        <span class="priority-dot bg-green-500"></span>Rendah
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="priority" value="medium" id="priority_medium" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ request()->query('priority') == 'medium' ? 'checked' : '' }}>
                                    <label for="priority_medium" class="text-sm text-gray-700 flex items-center">
                                        <span class="priority-dot bg-amber-500"></span>Sedang
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="priority" value="high" id="priority_high" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ request()->query('priority') == 'high' ? 'checked' : '' }}>
                                    <label for="priority_high" class="text-sm text-gray-700 flex items-center">
                                        <span class="priority-dot bg-orange-500"></span>Tinggi
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" name="priority" value="critical" id="priority_critical" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ request()->query('priority') == 'critical' ? 'checked' : '' }}>
                                    <label for="priority_critical" class="text-sm text-gray-700 flex items-center">
                                        <span class="priority-dot bg-red-500"></span>Kritis
                                    </label>
                                </div>
                                <div class="flex items-center col-span-2 mt-2">
                                    <input type="radio" name="priority" value="" id="priority_all" class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300" {{ request()->query('priority') == '' ? 'checked' : '' }}>
                                    <label for="priority_all" class="text-sm text-gray-700">Semua Prioritas</label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Category Filter -->
                        <div>
                            <label for="category_id" class="text-sm font-medium text-gray-700 block mb-2">Kategori</label>
                            <select id="category_id" name="category_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request()->query('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Assigned To Filter -->
                        <div>
                            <label for="assigned_to" class="text-sm font-medium text-gray-700 block mb-2">Ditugaskan Kepada</label>
                            <select id="assigned_to" name="assigned_to" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                <option value="">Semua Staff</option>
                                <option value="unassigned" {{ request()->query('assigned_to') == 'unassigned' ? 'selected' : '' }}>Belum Ditugaskan</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}" {{ request()->query('assigned_to') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="pt-4 flex space-x-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md flex-grow flex items-center justify-center transition-colors">
                                <i class="fas fa-filter mr-2"></i> Terapkan Filter
                            </button>
                            <a href="{{ $status == 'all' ? route('tickets.all') : ($status == 'waiting' ? route('tickets.waiting') : ($status == 'in_progress' ? route('tickets.in-progress') : route('tickets.done'))) }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-md">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Ticket List -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden lg:col-span-2">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-list-ul text-blue-500 mr-2"></i> Daftar Tiket
                    @if($status == 'waiting')
                        <span class="ml-2 bg-amber-100 text-amber-800 text-xs font-semibold py-1 px-2 rounded">Menunggu</span>
                    @elseif($status == 'in_progress')
                        <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold py-1 px-2 rounded">Dalam Proses</span>
                    @elseif($status == 'done')
                        <span class="ml-2 bg-green-100 text-green-800 text-xs font-semibold py-1 px-2 rounded">Selesai</span>
                    @endif
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Tiket</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemohon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tickets as $ticket)
                            <tr class="table-row-hover transition-all">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500">{{ $ticket->ticket_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($ticket->title, 30) }}</div>
                                    <div class="text-xs text-gray-500">{{ $ticket->category ? $ticket->category->name : 'Tidak ada kategori' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $ticket->requester_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $ticket->requester_email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($ticket->priority == 'low')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <span class="priority-dot bg-green-500"></span>Rendah
                                        </span>
                                    @elseif($ticket->priority == 'medium')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <span class="priority-dot bg-yellow-500"></span>Sedang
                                        </span>
                                    @elseif($ticket->priority == 'high')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                            <span class="priority-dot bg-orange-500"></span>Tinggi
                                        </span>
                                    @elseif($ticket->priority == 'critical')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <span class="priority-dot bg-red-500"></span>Kritis
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($ticket->status == 'waiting')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 shadow-sm border border-yellow-200">
                                            <span class="w-2 h-2 rounded-full bg-yellow-400 mr-2"></span>
                                            Menunggu
                                        </span>
                                    @elseif($ticket->status == 'in_progress')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 shadow-sm border border-blue-200">
                                            <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 animate-pulse"></span>
                                            Dalam Proses
                                        </span>
                                    @elseif($ticket->status == 'done')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 shadow-sm border border-green-200">
                                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('tickets.show', $ticket->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors duration-150 ease-in-out shadow-sm" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-50 text-amber-600 hover:bg-amber-100 transition-colors duration-150 ease-in-out shadow-sm" title="Edit Tiket">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if($status != 'all')
                                        <!-- Status Dropdown -->
                                        <div class="relative inline-block text-left" x-data="{ statusOpen: false }">
                                            <!-- Status Trigger Button -->
                                            <div @click="statusOpen = !statusOpen" class="cursor-pointer inline-flex items-center justify-center rounded-md bg-white px-3 py-1.5 text-sm font-medium shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                                @if($ticket->status == 'waiting')
                                                <span class="inline-flex items-center mr-1">
                                                    <span class="w-2 h-2 rounded-full bg-yellow-400 mr-1.5"></span>
                                                    <span class="text-yellow-700">Waiting</span>
                                                </span>
                                                @elseif($ticket->status == 'in_progress')
                                                <span class="inline-flex items-center mr-1">
                                                    <span class="w-2 h-2 rounded-full bg-blue-400 mr-1.5"></span>
                                                    <span class="text-blue-700">In Progress</span>
                                                </span>
                                                @elseif($ticket->status == 'done')
                                                <span class="inline-flex items-center mr-1">
                                                    <span class="w-2 h-2 rounded-full bg-green-400 mr-1.5"></span>
                                                    <span class="text-green-700">Done</span>
                                                </span>
                                                @endif
                                                <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            
                                            <!-- Status Dropdown Menu -->
                                            <div x-show="statusOpen" 
                                                @click.away="statusOpen = false"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                x-transition:leave-end="transform opacity-0 scale-95"
                                                class="absolute right-0 z-50 mt-2 w-44 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none status-dropdown-menu"
                                                style="display: none;">
                                                <div class="py-1">
                                                    @if($ticket->status != 'waiting')
                                                    <form action="{{ route('tickets.change-status', $ticket->id) }}" method="POST" class="block">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="waiting">
                                                        <button type="submit" class="w-full px-4 py-2 text-left text-sm flex items-center text-yellow-700 hover:bg-yellow-50">
                                                            <span class="w-2 h-2 rounded-full bg-yellow-400 mr-2"></span>
                                                            Waiting
                                                        </button>
                                                    </form>
                                                    @endif
                                                    
                                                    @if($ticket->status != 'in_progress')
                                                    <form action="{{ route('tickets.change-status', $ticket->id) }}" method="POST" class="block">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="in_progress">
                                                        <button type="submit" class="w-full px-4 py-2 text-left text-sm flex items-center text-blue-700 hover:bg-blue-50">
                                                            <span class="w-2 h-2 rounded-full bg-blue-400 mr-2"></span>
                                                            In Progress
                                                        </button>
                                                    </form>
                                                    @endif
                                                    
                                                    @if($ticket->status != 'done')
                                                    <form action="{{ route('tickets.change-status', $ticket->id) }}" method="POST" class="block">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="done">
                                                        <button type="submit" class="w-full px-4 py-2 text-left text-sm flex items-center text-green-700 hover:bg-green-50">
                                                            <span class="w-2 h-2 rounded-full bg-green-400 mr-2"></span>
                                                            Done
                                                        </button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <button onclick="confirmDelete('{{ $ticket->id }}', '{{ $ticket->ticket_number }}', '{{ $ticket->title }}')" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 transition-colors duration-150 ease-in-out shadow-sm" title="Hapus Tiket">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-ticket-alt text-gray-300 text-5xl mb-4"></i>
                                        <p>Tidak ada tiket ditemukan</p>
                                        <a href="{{ route('tickets.create') }}" class="mt-3 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-plus mr-2"></i> Buat Tiket Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $tickets->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="border-b px-6 py-3">
            <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus Tiket</h3>
        </div>
        <div class="p-6">
            <p class="text-gray-700">Apakah Anda yakin ingin menghapus tiket berikut?</p>
            <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                <p class="font-medium text-gray-900" id="deleteTicketNumber"></p>
                <p class="text-gray-600" id="deleteTicketTitle"></p>
            </div>
            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md">
                    Batal
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                        Hapus Tiket
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all dropdowns
        document.querySelectorAll('.status-dropdown').forEach(function(dropdown) {
            dropdown.addEventListener('click', function(event) {
                event.stopPropagation();
                this.querySelector('.dropdown-menu').classList.toggle('hidden');
            });
        });
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                if (!menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                }
            });
        });
    });
    
    // Delete confirmation modal functions
    function confirmDelete(ticketId, ticketNumber, ticketTitle) {
        document.getElementById('deleteTicketNumber').textContent = ticketNumber;
        document.getElementById('deleteTicketTitle').textContent = ticketTitle;
        document.getElementById('deleteForm').action = "{{ url('tickets') }}/" + ticketId;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('flex');
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    // Close modal when clicking outside the modal content
    document.getElementById('deleteModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeDeleteModal();
        }
    });
</script>
@endpush 