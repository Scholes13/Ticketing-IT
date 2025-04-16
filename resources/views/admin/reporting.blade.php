@extends('layouts.admin')

@section('title', 'Reporting')

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
    
    .filter-active {
        background-color: #3b82f6;
        color: white;
    }
    
    .filter-section {
        background-color: white;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    
    .btn-export {
        transition: all 0.3s ease;
    }
    
    .btn-export:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Ticketing</h1>
            <p class="text-gray-600">Periode: {{ $dateRange['start_formatted'] }} - {{ $dateRange['end_formatted'] }}</p>
        </div>
        
        <!-- Export Buttons -->
        <div class="flex items-center space-x-4">
            <form action="{{ route('reporting.export.excel') }}" method="GET">
                <input type="hidden" name="period" value="{{ $period }}">
                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                <input type="hidden" name="quarter" value="{{ $selectedQuarter }}">
                <input type="hidden" name="year" value="{{ $selectedYear }}">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center btn-export">
                    <i class="fas fa-file-excel mr-2"></i> Export Excel
                </button>
            </form>
            
            <form action="{{ route('reporting.export.pdf') }}" method="GET">
                <input type="hidden" name="period" value="{{ $period }}">
                <input type="hidden" name="month" value="{{ $selectedMonth }}">
                <input type="hidden" name="quarter" value="{{ $selectedQuarter }}">
                <input type="hidden" name="year" value="{{ $selectedYear }}">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center btn-export">
                    <i class="fas fa-file-pdf mr-2"></i> Export PDF
                </button>
            </form>
        </div>
    </div>
    
    <!-- Filter Section -->
    <div class="filter-section mb-8">
        <form action="{{ route('reporting') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
                <select name="period" id="period" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Bulanan</option>
                    <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>Quarterly</option>
                    <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Tahunan</option>
                </select>
            </div>
            
            <div id="month-selector" class="{{ $period != 'month' ? 'hidden' : '' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="month" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="1" {{ $selectedMonth == 1 ? 'selected' : '' }}>Januari</option>
                    <option value="2" {{ $selectedMonth == 2 ? 'selected' : '' }}>Februari</option>
                    <option value="3" {{ $selectedMonth == 3 ? 'selected' : '' }}>Maret</option>
                    <option value="4" {{ $selectedMonth == 4 ? 'selected' : '' }}>April</option>
                    <option value="5" {{ $selectedMonth == 5 ? 'selected' : '' }}>Mei</option>
                    <option value="6" {{ $selectedMonth == 6 ? 'selected' : '' }}>Juni</option>
                    <option value="7" {{ $selectedMonth == 7 ? 'selected' : '' }}>Juli</option>
                    <option value="8" {{ $selectedMonth == 8 ? 'selected' : '' }}>Agustus</option>
                    <option value="9" {{ $selectedMonth == 9 ? 'selected' : '' }}>September</option>
                    <option value="10" {{ $selectedMonth == 10 ? 'selected' : '' }}>Oktober</option>
                    <option value="11" {{ $selectedMonth == 11 ? 'selected' : '' }}>November</option>
                    <option value="12" {{ $selectedMonth == 12 ? 'selected' : '' }}>Desember</option>
                </select>
            </div>
            
            <div id="quarter-selector" class="{{ $period != 'quarter' ? 'hidden' : '' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1">Quarter</label>
                <select name="quarter" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="1" {{ $selectedQuarter == 1 ? 'selected' : '' }}>Q1 (Jan-Mar)</option>
                    <option value="2" {{ $selectedQuarter == 2 ? 'selected' : '' }}>Q2 (Apr-Jun)</option>
                    <option value="3" {{ $selectedQuarter == 3 ? 'selected' : '' }}>Q3 (Jul-Sep)</option>
                    <option value="4" {{ $selectedQuarter == 4 ? 'selected' : '' }}>Q4 (Oct-Dec)</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="year" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    @for ($i = date('Y'); $i >= date('Y') - 5; $i--)
                    <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-filter mr-2"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Tickets -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 card-hover">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Tiket</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ array_sum($ticketsByStatus) }}</h3>
                        <p class="text-green-500 text-sm mt-2"><i class="fas fa-ticket-alt mr-1"></i> Semua tiket</p>
                    </div>
                    <div class="bg-blue-100 p-4 rounded-full">
                        <i class="fas fa-ticket-alt text-blue-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Waiting Tickets -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 card-hover">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Menunggu</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $ticketsByStatus['waiting'] ?? 0 }}</h3>
                        <p class="text-amber-500 text-sm mt-2"><i class="fas fa-clock mr-1"></i> Belum diproses</p>
                    </div>
                    <div class="bg-amber-100 p-4 rounded-full">
                        <i class="fas fa-clock text-amber-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- In Progress -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 card-hover">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Dalam Proses</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $ticketsByStatus['in_progress'] ?? 0 }}</h3>
                        <p class="text-cyan-500 text-sm mt-2"><i class="fas fa-spinner mr-1"></i> Sedang ditangani</p>
                    </div>
                    <div class="bg-cyan-100 p-4 rounded-full">
                        <i class="fas fa-spinner text-cyan-600 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Completed -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 card-hover">
            <div class="p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Selesai</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-1">{{ $ticketsByStatus['done'] ?? 0 }}</h3>
                        <p class="text-emerald-500 text-sm mt-2"><i class="fas fa-check-circle mr-1"></i> Telah diselesaikan</p>
                    </div>
                    <div class="bg-emerald-100 p-4 rounded-full">
                        <i class="fas fa-check-circle text-emerald-600 text-2xl"></i>
                    </div>
                </div>
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
                                <p class="text-gray-800 font-medium">{{ number_format($avgFollowUpTime, 1) }} {{ $avgFollowUpUnit }}</p>
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
                                <p class="text-gray-800 font-medium">{{ number_format($avgProcessingTime, 1) }} {{ $avgProcessingUnit }}</p>
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
                                <p class="text-gray-800 font-medium">{{ number_format($avgTotalTime, 1) }} {{ $avgTotalUnit }}</p>
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
                <div class="flex justify-between items-center mt-4">
                    <div class="flex items-center">
                        <span class="priority-dot" style="background-color: #10b981;"></span>
                        <span class="text-sm">Rendah: {{ $ticketsByPriority['low'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="priority-dot" style="background-color: #f59e0b;"></span>
                        <span class="text-sm">Sedang: {{ $ticketsByPriority['medium'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="priority-dot" style="background-color: #f97316;"></span>
                        <span class="text-sm">Tinggi: {{ $ticketsByPriority['high'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center">
                        <span class="priority-dot" style="background-color: #ef4444;"></span>
                        <span class="text-sm">Kritis: {{ $ticketsByPriority['critical'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Category Distribution & Recent Tickets -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Category Distribution -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-folder text-yellow-500 mr-2"></i> Distribusi Kategori
                </h3>
            </div>
            <div class="p-6">
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Recent Tickets -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-list-alt text-purple-500 mr-2"></i> Tiket Terbaru
                </h3>
            </div>
            <div class="p-1">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Tiket</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
                                <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 ticket-table">
                            @forelse($recentTickets as $ticket)
                            <tr>
                                <td class="py-3 px-4 text-sm">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $ticket->ticket_number }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 text-sm">{{ Str::limit($ticket->title, 30) }}</td>
                                <td class="py-3 px-4">
                                    @if($ticket->status == 'waiting')
                                    <span class="status-badge bg-amber-100 text-amber-800">Menunggu</span>
                                    @elseif($ticket->status == 'in_progress')
                                    <span class="status-badge bg-cyan-100 text-cyan-800">Diproses</span>
                                    @elseif($ticket->status == 'done')
                                    <span class="status-badge bg-emerald-100 text-emerald-800">Selesai</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($ticket->priority == 'low')
                                    <span class="priority-dot" style="background-color: #10b981;"></span>Rendah
                                    @elseif($ticket->priority == 'medium')
                                    <span class="priority-dot" style="background-color: #f59e0b;"></span>Sedang
                                    @elseif($ticket->priority == 'high')
                                    <span class="priority-dot" style="background-color: #f97316;"></span>Tinggi
                                    @elseif($ticket->priority == 'critical')
                                    <span class="priority-dot" style="background-color: #ef4444;"></span>Kritis
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-sm">{{ $ticket->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-4 px-4 text-center text-gray-500">Tidak ada tiket dalam periode ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle filter sections based on period selection
        const periodSelect = document.getElementById('period');
        const monthSelector = document.getElementById('month-selector');
        const quarterSelector = document.getElementById('quarter-selector');
        
        periodSelect.addEventListener('change', function() {
            const selectedPeriod = this.value;
            
            if (selectedPeriod === 'month') {
                monthSelector.classList.remove('hidden');
                quarterSelector.classList.add('hidden');
            } else if (selectedPeriod === 'quarter') {
                monthSelector.classList.add('hidden');
                quarterSelector.classList.remove('hidden');
            } else {
                monthSelector.classList.add('hidden');
                quarterSelector.classList.add('hidden');
            }
        });
        
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
        const backgroundColors = [
            '#4f46e5', '#0ea5e9', '#10b981', '#f59e0b', '#f97316', 
            '#ef4444', '#8b5cf6', '#ec4899', '#14b8a6', '#a855f7'
        ];
        
        new Chart(categoryCtx, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Tiket',
                    data: categoryData,
                    backgroundColor: backgroundColors.slice(0, categoryLabels.length),
                    borderWidth: 0,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection 