@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
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
        height: 300px;
    }
    
    .ticket-table tr:hover {
        background-color: #f1f5f9;
    }
    
    .animate-bounce {
        animation: bounce 2s infinite;
    }
    
    .filter-active {
        background-color: #3b82f6;
        color: white;
    }
    
    @keyframes bounce {
        0%, 100% {
            transform: translateY(-5px);
        }
        50% {
            transform: translateY(5px);
        }
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Ticketing</h1>
            <p class="text-gray-600">Pelaporan Masalah IT</p>
        </div>
        <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-4 w-full md:w-auto">
            <!-- Date Filter -->
            <div class="hidden md:block">
                <div class="relative inline-block">
                    <button id="dateFilterDropdown" class="flex items-center justify-between bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-2 text-sm font-medium min-w-[160px]">
                        <span id="currentFilterText">Hari Ini</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div id="filterDropdownMenu" class="hidden absolute z-10 mt-2 w-full bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <button id="todayFilter" class="w-full px-4 py-2 text-left text-sm font-medium text-gray-700 hover:bg-gray-100 filter-active">
                    Hari Ini
                </button>
                        <button id="weekFilter" class="w-full px-4 py-2 text-left text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Minggu Ini
                </button>
                        <button id="monthFilter" class="w-full px-4 py-2 text-left text-sm font-medium text-gray-700 hover:bg-gray-100">
                    Bulan Ini
                </button>
                        <button id="yearFilter" class="w-full px-4 py-2 text-left text-sm font-medium text-gray-700 hover:bg-gray-100">
                            Tahun Ini
                        </button>
                        <button id="allFilter" class="w-full px-4 py-2 text-left text-sm font-medium text-gray-700 hover:bg-gray-100">
                            Semua Data
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Dropdown Filter -->
            <div class="md:hidden w-full">
                <div class="relative">
                    <select id="mobileFilter" class="appearance-none bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-2 pr-8 text-sm font-medium w-full">
                        <option value="today">Hari Ini</option>
                        <option value="week">Minggu Ini</option>
                        <option value="month">Bulan Ini</option>
                        <option value="year">Tahun Ini</option>
                        <option value="all">Semua Data</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-4 w-full md:w-auto">
                <div class="relative">
                    <input type="text" placeholder="Cari tiket..." class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
                <a href="{{ route('tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center justify-center whitespace-nowrap">
                    <i class="fas fa-plus mr-2"></i> Buat Tiket Baru
                </a>
            </div>
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
                        <h3 class="text-3xl font-bold text-gray-800 mt-1" id="total-tickets">{{ array_sum($ticketsByStatus) }}</h3>
                        <p class="text-green-500 text-sm mt-2"><i class="fas fa-ticket-alt mr-1"></i> Semua tiket</p>
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
                        <h3 class="text-3xl font-bold text-gray-800 mt-1" id="waiting-tickets">{{ $ticketsByStatus['waiting'] ?? 0 }}</h3>
                        <p class="text-amber-500 text-sm mt-2"><i class="fas fa-clock mr-1"></i> Belum diproses</p>
                    </div>
                    <div class="bg-amber-100 p-4 rounded-full">
                        <i class="fas fa-clock text-amber-600 text-2xl animate-pulse"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-between items-center">
                <a href="{{ route('tickets.waiting') }}" class="text-blue-600 text-sm font-medium hover:text-blue-800">Lihat Detail</a>
                <i class="fas fa-chevron-right text-blue-600 text-sm"></i>
            </div>
        </div>
        <!-- In Progress -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 card-hover">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Dalam Proses</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1" id="in-progress-tickets">{{ $ticketsByStatus['in_progress'] ?? 0 }}</h3>
                        <p class="text-cyan-500 text-sm mt-2"><i class="fas fa-spinner mr-1"></i> Sedang ditangani</p>
                    </div>
                    <div class="bg-cyan-100 p-4 rounded-full">
                        <i class="fas fa-spinner text-cyan-600 text-2xl animate-spin"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-between items-center">
                <a href="{{ route('tickets.in-progress') }}" class="text-blue-600 text-sm font-medium hover:text-blue-800">Lihat Detail</a>
                <i class="fas fa-chevron-right text-blue-600 text-sm"></i>
            </div>
        </div>
        <!-- Completed -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 card-hover">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Selesai</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1" id="done-tickets">{{ $ticketsByStatus['done'] ?? 0 }}</h3>
                        <p class="text-emerald-500 text-sm mt-2"><i class="fas fa-check-circle mr-1"></i> Telah diselesaikan</p>
                    </div>
                    <div class="bg-emerald-100 p-4 rounded-full">
                        <i class="fas fa-check-circle text-emerald-600 text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-3 flex justify-between items-center">
                <a href="{{ route('tickets.done') }}" class="text-blue-600 text-sm font-medium hover:text-blue-800">Lihat Detail</a>
                <i class="fas fa-chevron-right text-blue-600 text-sm"></i>
            </div>
        </div>
    </div>
    
    <!-- Metrics and Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Performance Metrics -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-chart-line text-blue-500 mr-2"></i> Metrik Kinerja
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <!-- Waktu Follow Up -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg mr-4">
                                <i class="fas fa-stopwatch text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Waktu Follow Up</p>
                                <p class="text-gray-800 font-medium"><span id="avg-follow-up-time">{{ number_format($avgFollowUpTime, 1) }}</span> <span id="avg-follow-up-unit">{{ $avgFollowUpUnit }}</span></p>
                                <p class="text-xs text-gray-500 mt-1">Dari Waiting ke In Progress</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-medium">Respon Awal</span>
                            <span class="text-xs text-gray-500 mt-1">Waiting → In Progress</span>
                        </div>
                    </div>
                    
                    <!-- Waktu Proses -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-2 rounded-lg mr-4">
                                <i class="fas fa-tasks text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Waktu Proses</p>
                                <p class="text-gray-800 font-medium"><span id="avg-processing-time">{{ number_format($avgProcessingTime, 1) }}</span> <span id="avg-processing-unit">{{ $avgProcessingUnit }}</span></p>
                                <p class="text-xs text-gray-500 mt-1">Dari In Progress ke Done</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-medium">Penanganan</span>
                            <span class="text-xs text-gray-500 mt-1">In Progress → Done</span>
                        </div>
                    </div>

                    <!-- Total Waktu -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-indigo-100 p-2 rounded-lg mr-4">
                                <i class="fas fa-clock text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Total Waktu</p>
                                <p class="text-gray-800 font-medium"><span id="avg-total-time">{{ number_format($avgTotalTime, 1) }}</span> <span id="avg-total-unit">{{ $avgTotalUnit }}</span></p>
                                <p class="text-xs text-gray-500 mt-1">Total waktu penyelesaian</p>
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-xs font-medium">Total</span>
                            <span class="text-xs text-gray-500 mt-1">Waiting → Done</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Priority Distribution -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i> Distribusi Prioritas
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="priorityChart"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <span class="priority-dot bg-green-500"></span>
                        <span class="text-sm text-gray-600">Rendah: {{ $ticketsByPriority['low'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="priority-dot bg-amber-500"></span>
                        <span class="text-sm text-gray-600">Sedang: {{ $ticketsByPriority['medium'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="priority-dot bg-orange-500"></span>
                        <span class="text-sm text-gray-600">Tinggi: {{ $ticketsByPriority['high'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="priority-dot bg-red-500"></span>
                        <span class="text-sm text-gray-600">Kritis: {{ $ticketsByPriority['critical'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Tickets and Category Distribution -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Tickets -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-list-ul text-blue-500 mr-2"></i> Tiket Terbaru
                </h3>
                <a href="{{ route('tickets.index') }}" class="text-blue-600 text-sm font-medium hover:text-blue-800">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 ticket-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Tiket</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="recent-tickets-table-body">
                        @foreach($recentTickets as $ticket)
                        <tr class="hover:bg-gray-50 cursor-pointer">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">{{ $ticket->ticket_number }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $ticket->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ticket->status == 'waiting')
                                <span class="status-badge bg-amber-100 text-amber-800">Menunggu</span>
                                @elseif($ticket->status == 'in_progress')
                                <span class="status-badge bg-cyan-100 text-cyan-800">Dalam Proses</span>
                                @elseif($ticket->status == 'done')
                                <span class="status-badge bg-emerald-100 text-emerald-800">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($ticket->priority == 'low')
                                    <span class="priority-dot bg-green-500"></span>
                                    <span class="text-sm text-gray-600">Rendah</span>
                                    @elseif($ticket->priority == 'medium')
                                    <span class="priority-dot bg-amber-500"></span>
                                    <span class="text-sm text-gray-600">Sedang</span>
                                    @elseif($ticket->priority == 'high')
                                    <span class="priority-dot bg-orange-500"></span>
                                    <span class="text-sm text-gray-600">Tinggi</span>
                                    @elseif($ticket->priority == 'critical')
                                    <span class="priority-dot bg-red-500"></span>
                                    <span class="text-sm text-gray-600">Kritis</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        
                        @if(count($recentTickets) == 0)
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada tiket</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Category Distribution -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-tags text-purple-500 mr-2"></i> Distribusi Kategori
                </h3>
                <a href="{{ route('admin.categories.index') }}" class="text-blue-600 text-sm font-medium hover:text-blue-800">Lihat Detail</a>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                    $totalTickets = array_sum($ticketsByCategory);
                    $colors = ['bg-blue-500', 'bg-purple-500', 'bg-green-500', 'bg-amber-500', 'bg-red-500', 'bg-pink-500', 'bg-indigo-500', 'bg-teal-500'];
                    $i = 0;
                    @endphp
                    
                    @foreach($ticketsByCategory as $category => $count)
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full {{ $colors[$i % count($colors)] }} mr-2"></span>
                        <span class="text-sm text-gray-600">{{ $category ?: 'Tidak ada kategori' }}: {{ $count }} ({{ $totalTickets > 0 ? round(($count / $totalTickets) * 100) : 0 }}%)</span>
                    </div>
                    @php $i++; @endphp
                    @endforeach
                    
                    @if(count($ticketsByCategory) == 0)
                    <div class="flex items-center col-span-2">
                        <span class="text-sm text-gray-600">Belum ada data kategori</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Priority Chart
    const priorityCtx = document.getElementById('priorityChart').getContext('2d');
    const priorityData = {
        labels: ['Rendah', 'Sedang', 'Tinggi', 'Kritis'],
        datasets: [{
            data: [
                {{ $ticketsByPriority['low'] ?? 0 }},
                {{ $ticketsByPriority['medium'] ?? 0 }},
                {{ $ticketsByPriority['high'] ?? 0 }},
                {{ $ticketsByPriority['critical'] ?? 0 }}
            ],
            backgroundColor: [
                '#10b981', // green
                '#f59e0b', // amber
                '#f97316', // orange
                '#ef4444'  // red
            ],
            borderWidth: 0
        }]
    };
    
    new Chart(priorityCtx, {
        type: 'doughnut',
        data: priorityData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryLabels = @json(array_keys($ticketsByCategory));
    const categoryData = @json(array_values($ticketsByCategory));
    const categoryColors = [
        '#3b82f6', // blue
        '#8b5cf6', // purple
        '#10b981', // green
        '#f59e0b', // amber
        '#ef4444', // red
        '#ec4899', // pink
        '#6366f1', // indigo
        '#14b8a6'  // teal
    ];
    
    new Chart(categoryCtx, {
        type: 'bar',
        data: {
            labels: categoryLabels,
            datasets: [{
                data: categoryData,
                backgroundColor: categoryColors.slice(0, categoryLabels.length),
                borderRadius: 6,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = categoryData.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        display: false
                    },
                    ticks: {
                        stepSize: 5
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // Date Filter Functionality
    const todayFilter = document.getElementById('todayFilter');
    const weekFilter = document.getElementById('weekFilter');
    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter');
    const allFilter = document.getElementById('allFilter');
    const mobileFilter = document.getElementById('mobileFilter');

    function applyFilter(filterElement) {
        // Reset all filters
        const allFilterButtons = [todayFilter, weekFilter, monthFilter, yearFilter, allFilter];
        allFilterButtons.forEach(btn => {
            btn.classList.remove('filter-active');
            btn.classList.add('text-gray-700', 'hover:bg-gray-100');
        });
        
        let period;
        let filterText;
        
        // Handle both button clicks and mobile dropdown
        if (typeof filterElement === 'string') {
            period = filterElement;
            // Handle mobile dropdown
        } else {
            // Get the period based on which filter was clicked
            if (filterElement.id === 'todayFilter' || filterElement === todayFilter) {
                period = 'today';
                filterText = 'Hari Ini';
            } else if (filterElement.id === 'weekFilter' || filterElement === weekFilter) {
                period = 'week';
                filterText = 'Minggu Ini';
            } else if (filterElement.id === 'monthFilter' || filterElement === monthFilter) {
                period = 'month';
                filterText = 'Bulan Ini';
            } else if (filterElement.id === 'yearFilter' || filterElement === yearFilter) {
                period = 'year';
                filterText = 'Tahun Ini';
            } else if (filterElement.id === 'allFilter' || filterElement === allFilter) {
                period = 'all';
                filterText = 'Semua Data';
            }
            
            // Update dropdown text if it's a button click
            if (filterElement.id) {
                document.getElementById('currentFilterText').textContent = filterText;
                // Hide dropdown menu after selection
                document.getElementById('filterDropdownMenu').classList.add('hidden');
            }
        }
        
        // Update button UI
        switch(period) {
            case 'today':
                todayFilter.classList.add('filter-active');
                todayFilter.classList.remove('text-gray-700', 'hover:bg-gray-100');
                break;
            case 'week':
                weekFilter.classList.add('filter-active');
                weekFilter.classList.remove('text-gray-700', 'hover:bg-gray-100');
                break;
            case 'month':
                monthFilter.classList.add('filter-active');
                monthFilter.classList.remove('text-gray-700', 'hover:bg-gray-100');
                break;
            case 'year':
                yearFilter.classList.add('filter-active');
                yearFilter.classList.remove('text-gray-700', 'hover:bg-gray-100');
                break;
            case 'all':
                allFilter.classList.add('filter-active');
                allFilter.classList.remove('text-gray-700', 'hover:bg-gray-100');
                break;
        }
        
        // Show loading state
        showLoadingState();
        
        // Make AJAX call to get filtered data
        fetch(`{{ route('dashboard.filter') }}?period=${period}`)
            .then(response => response.json())
            .then(data => {
                updateDashboardData(data);
                hideLoadingState();
            })
            .catch(error => {
                console.error('Error fetching dashboard data:', error);
                hideLoadingState();
            });
    }

    function showLoadingState() {
        const cards = document.querySelectorAll('.card-hover');
        cards.forEach(card => {
            card.classList.add('opacity-75');
        });
        
        // Add loading spinner if needed
        document.querySelectorAll('.stat-value').forEach(el => {
            el.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        });
    }
    
    function hideLoadingState() {
        const cards = document.querySelectorAll('.card-hover');
            cards.forEach(card => {
                card.classList.remove('opacity-75');
            });
    }
    
    function updateDashboardData(data) {
        // Update ticket status counts
        const waitingCount = data.ticketsByStatus['waiting'] || 0;
        const inProgressCount = data.ticketsByStatus['in_progress'] || 0;
        const doneCount = data.ticketsByStatus['done'] || 0;
        const totalCount = waitingCount + inProgressCount + doneCount;
        
        // Update statistics cards
        document.getElementById('total-tickets').textContent = totalCount;
        document.getElementById('waiting-tickets').textContent = waitingCount;
        document.getElementById('in-progress-tickets').textContent = inProgressCount;
        document.getElementById('done-tickets').textContent = doneCount;
        
        // Update average times
        document.getElementById('avg-follow-up-time').textContent = data.avgTimes.follow_up;
        document.getElementById('avg-follow-up-unit').textContent = data.avgTimes.follow_up_unit;
        document.getElementById('avg-processing-time').textContent = data.avgTimes.processing;
        document.getElementById('avg-processing-unit').textContent = data.avgTimes.processing_unit;
        document.getElementById('avg-total-time').textContent = data.avgTimes.total;
        document.getElementById('avg-total-unit').textContent = data.avgTimes.total_unit;
        
        // Update priority chart
        if (window.priorityChart) {
            window.priorityChart.data.datasets[0].data = [
                data.ticketsByPriority['low'] || 0,
                data.ticketsByPriority['medium'] || 0,
                data.ticketsByPriority['high'] || 0,
                data.ticketsByPriority['critical'] || 0
            ];
            window.priorityChart.update();
        }
        
        // Update recent tickets table
        updateRecentTicketsTable(data.recentTickets);
    }
    
    function updateRecentTicketsTable(recentTickets) {
        const ticketTableBody = document.getElementById('recent-tickets-table-body');
        if (!ticketTableBody) return;
        
        let html = '';
        
        if (recentTickets.length === 0) {
            html = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada tiket terbaru</td></tr>';
        } else {
            recentTickets.forEach(ticket => {
                let statusClass = '';
                let statusText = '';
                
                if (ticket.status === 'waiting') {
                    statusClass = 'bg-amber-100 text-amber-800';
                    statusText = 'Menunggu';
                } else if (ticket.status === 'in_progress') {
                    statusClass = 'bg-blue-100 text-blue-800';
                    statusText = 'Diproses';
                } else if (ticket.status === 'done') {
                    statusClass = 'bg-green-100 text-green-800';
                    statusText = 'Selesai';
                }
                
                html += `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${ticket.ticket_number}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">${ticket.title}</div>
                        <div class="text-xs text-gray-500">${ticket.requester_name}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            ${statusText}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${new Date(ticket.created_at).toLocaleString('id-ID')}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <a href="/tickets/${ticket.id}" class="text-blue-600 hover:text-blue-900">Detail</a>
                    </td>
                </tr>
                `;
            });
        }
        
        ticketTableBody.innerHTML = html;
    }

    todayFilter.addEventListener('click', () => applyFilter(todayFilter));
    weekFilter.addEventListener('click', () => applyFilter(weekFilter));
    monthFilter.addEventListener('click', () => applyFilter(monthFilter));
    yearFilter.addEventListener('click', () => applyFilter(yearFilter));
    allFilter.addEventListener('click', () => applyFilter(allFilter));

    // Dropdown toggle
    document.getElementById('dateFilterDropdown').addEventListener('click', function() {
        document.getElementById('filterDropdownMenu').classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('filterDropdownMenu');
        const dropdownButton = document.getElementById('dateFilterDropdown');
        
        if (!dropdown.contains(event.target) && !dropdownButton.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    // Mobile dropdown filter change handler
    mobileFilter.addEventListener('change', function() {
        applyFilter(this.value);
        
        // Synchronize with desktop filter display
        document.getElementById('currentFilterText').textContent = this.options[this.selectedIndex].text;
    });

    // Initialize with today's filter
    document.addEventListener('DOMContentLoaded', function() {
    applyFilter(todayFilter);
    });
    
    // Store priority chart reference
    const priorityChart = new Chart(priorityCtx, {
        type: 'doughnut',
        data: priorityData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    
    // Store chart reference in window object for later updates
    window.priorityChart = priorityChart;
</script>
@endpush



