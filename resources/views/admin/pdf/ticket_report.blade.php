<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Tiket</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        .report-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #1e40af;
        }
        .report-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .report-date {
            font-size: 12px;
            color: #666;
        }
        .stats-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        .stat-card {
            width: 23%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .stat-card-header {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }
        .stat-card-value {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .metrics-section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .metric-row {
            display: flex;
            margin-bottom: 15px;
        }
        .metric-card {
            width: 30%;
            margin-right: 3%;
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
        }
        .metric-name {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .metric-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .metric-description {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f1f5f9;
            color: #475569;
        }
        .priority-low {
            color: #10b981;
            font-weight: bold;
        }
        .priority-medium {
            color: #f59e0b;
            font-weight: bold;
        }
        .priority-high {
            color: #f97316;
            font-weight: bold;
        }
        .priority-critical {
            color: #ef4444;
            font-weight: bold;
        }
        .status-waiting {
            color: #f59e0b;
            font-weight: bold;
        }
        .status-in_progress {
            color: #0ea5e9;
            font-weight: bold;
        }
        .status-done {
            color: #10b981;
            font-weight: bold;
        }
        .chart-container {
            page-break-inside: avoid;
            margin-bottom: 30px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="report-title">Laporan Ticketing</div>
        <div class="report-subtitle">WGTicket - IT Support Ticketing System</div>
        <div class="report-date">Periode: {{ $dateRange['start_formatted'] }} - {{ $dateRange['end_formatted'] }}</div>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">Total Tiket</div>
            <div class="stat-card-value">{{ array_sum($ticketsByStatus) }}</div>
            <div>Semua tiket dalam periode</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">Menunggu</div>
            <div class="stat-card-value">{{ $ticketsByStatus['waiting'] ?? 0 }}</div>
            <div>Belum diproses</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">Dalam Proses</div>
            <div class="stat-card-value">{{ $ticketsByStatus['in_progress'] ?? 0 }}</div>
            <div>Sedang ditangani</div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">Selesai</div>
            <div class="stat-card-value">{{ $ticketsByStatus['done'] ?? 0 }}</div>
            <div>Telah diselesaikan</div>
        </div>
    </div>
    
    <div class="metrics-section">
        <div class="section-title">Metrik Kinerja</div>
        <div class="metric-row">
            <div class="metric-card">
                <div class="metric-name">Waktu Follow Up</div>
                <div class="metric-value">{{ number_format($avgFollowUpTime, 1) }} {{ $avgFollowUpUnit }}</div>
                <div class="metric-description">Rata-rata waktu dari tiket dibuat hingga direspon</div>
            </div>
            <div class="metric-card">
                <div class="metric-name">Waktu Proses</div>
                <div class="metric-value">{{ number_format($avgProcessingTime, 1) }} {{ $avgProcessingUnit }}</div>
                <div class="metric-description">Rata-rata waktu dari tiket direspon hingga selesai</div>
            </div>
            <div class="metric-card">
                <div class="metric-name">Total Waktu</div>
                <div class="metric-value">{{ number_format($avgTotalTime, 1) }} {{ $avgTotalUnit }}</div>
                <div class="metric-description">Rata-rata total waktu penyelesaian tiket</div>
            </div>
        </div>
    </div>
    
    <div class="section-title">Distribusi Prioritas</div>
    <table>
        <thead>
            <tr>
                <th>Prioritas</th>
                <th>Jumlah Tiket</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPriority = array_sum($ticketsByPriority);
            @endphp
            
            <tr>
                <td><span class="priority-low">Rendah</span></td>
                <td>{{ $ticketsByPriority['low'] ?? 0 }}</td>
                <td>{{ $totalPriority > 0 ? number_format(($ticketsByPriority['low'] ?? 0) / $totalPriority * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td><span class="priority-medium">Sedang</span></td>
                <td>{{ $ticketsByPriority['medium'] ?? 0 }}</td>
                <td>{{ $totalPriority > 0 ? number_format(($ticketsByPriority['medium'] ?? 0) / $totalPriority * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td><span class="priority-high">Tinggi</span></td>
                <td>{{ $ticketsByPriority['high'] ?? 0 }}</td>
                <td>{{ $totalPriority > 0 ? number_format(($ticketsByPriority['high'] ?? 0) / $totalPriority * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td><span class="priority-critical">Kritis</span></td>
                <td>{{ $ticketsByPriority['critical'] ?? 0 }}</td>
                <td>{{ $totalPriority > 0 ? number_format(($ticketsByPriority['critical'] ?? 0) / $totalPriority * 100, 1) : 0 }}%</td>
            </tr>
        </tbody>
    </table>
    
    <div class="section-title" style="margin-top: 30px;">Distribusi Kategori</div>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jumlah Tiket</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalCategory = array_sum($ticketsByCategory);
            @endphp
            
            @foreach($ticketsByCategory as $category => $count)
            <tr>
                <td>{{ $category ?? 'Tidak ada kategori' }}</td>
                <td>{{ $count }}</td>
                <td>{{ $totalCategory > 0 ? number_format(($count / $totalCategory) * 100, 1) : 0 }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Laporan ini dihasilkan oleh WGTicket pada {{ now()->format('d/m/Y H:i') }}</p>
        <p>&copy; {{ date('Y') }} WGTicket - IT Support Ticketing System</p>
    </div>
</body>
</html> 