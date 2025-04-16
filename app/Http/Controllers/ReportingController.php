<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketExport;

class ReportingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Authentication is handled at the route level
    }
    
    /**
     * Format duration in minutes to appropriate unit (seconds, minutes, or hours)
     */
    private function formatDuration($minutes)
    {
        if ($minutes < 1) {
            $seconds = round($minutes * 60);
            return [
                'value' => $seconds,
                'unit' => 'detik'
            ];
        } elseif ($minutes < 60) {
            return [
                'value' => round($minutes, 1),
                'unit' => 'menit'
            ];
        } else {
            return [
                'value' => round($minutes / 60, 1),
                'unit' => 'jam'
            ];
        }
    }

    /**
     * Show the reporting page with analytics and filters
     */
    public function index(Request $request)
    {
        $period = $request->input('period', 'month'); // month, quarter, year
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedQuarter = $request->input('quarter', ceil(Carbon::now()->month / 3));
        $selectedYear = $request->input('year', Carbon::now()->year);
        
        // Define date range based on filters
        $startDate = null;
        $endDate = null;
        
        switch ($period) {
            case 'month':
                $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();
                break;
            case 'quarter':
                $startMonth = ($selectedQuarter - 1) * 3 + 1;
                $startDate = Carbon::createFromDate($selectedYear, $startMonth, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($selectedYear, $startMonth, 1)->addMonths(2)->endOfMonth();
                break;
            case 'year':
                $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfYear();
                $endDate = Carbon::createFromDate($selectedYear, 12, 31)->endOfYear();
                break;
            default:
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
        }
        
        // Query tickets for the selected period
        $ticketsQuery = Ticket::whereBetween('tickets.created_at', [$startDate, $endDate]);
        
        // Count tickets by status
        $ticketsByStatus = $ticketsQuery->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        // Count tickets by priority
        $ticketsByPriority = $ticketsQuery->select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->pluck('total', 'priority')
            ->toArray();
        
        // Count tickets by category
        $ticketsByCategory = Ticket::whereBetween('tickets.created_at', [$startDate, $endDate])
            ->select('categories.name', DB::raw('count(*) as total'))
            ->leftJoin('categories', 'tickets.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->pluck('total', 'name')
            ->toArray();
        
        // Calculate average times
        // Follow-up time (waiting to in_progress)
        $avgFollowUp = DB::select("
            SELECT AVG(TIMESTAMPDIFF(MINUTE, tickets.created_at, tickets.follow_up_at)) as avg_minutes,
                   COUNT(*) as total_tickets
            FROM tickets
            WHERE tickets.follow_up_at IS NOT NULL 
            AND tickets.status IN ('in_progress', 'done')
            AND tickets.created_at BETWEEN ? AND ?
        ", [$startDate, $endDate]);
        
        // Processing time (in_progress to done)
        $avgProcessing = DB::select("
            SELECT AVG(TIMESTAMPDIFF(MINUTE, tickets.follow_up_at, tickets.resolved_at)) as avg_minutes,
                   COUNT(*) as total_tickets
            FROM tickets
            WHERE tickets.follow_up_at IS NOT NULL 
            AND tickets.resolved_at IS NOT NULL 
            AND tickets.status = 'done'
            AND tickets.created_at BETWEEN ? AND ?
        ", [$startDate, $endDate]);
        
        // Total resolution time (waiting to done)
        $avgTotal = DB::select("
            SELECT AVG(TIMESTAMPDIFF(MINUTE, tickets.created_at, tickets.resolved_at)) as avg_minutes,
                   COUNT(*) as total_tickets
            FROM tickets
            WHERE tickets.resolved_at IS NOT NULL 
            AND tickets.status = 'done'
            AND tickets.created_at BETWEEN ? AND ?
        ", [$startDate, $endDate]);
        
        // Format durations
        $followUpTime = $this->formatDuration($avgFollowUp[0]->avg_minutes ?? 0);
        $processingTime = $this->formatDuration($avgProcessing[0]->avg_minutes ?? 0);
        $totalTime = $this->formatDuration($avgTotal[0]->avg_minutes ?? 0);
        
        $avgTimes = [
            'follow_up' => $followUpTime['value'],
            'follow_up_unit' => $followUpTime['unit'],
            'follow_up_count' => $avgFollowUp[0]->total_tickets ?? 0,
            'processing' => $processingTime['value'],
            'processing_unit' => $processingTime['unit'],
            'processing_count' => $avgProcessing[0]->total_tickets ?? 0,
            'total' => $totalTime['value'],
            'total_unit' => $totalTime['unit'],
            'total_count' => $avgTotal[0]->total_tickets ?? 0
        ];
        
        // Recent tickets
        $recentTickets = Ticket::with(['category', 'staff'])
            ->whereBetween('tickets.created_at', [$startDate, $endDate])
            ->latest()
            ->take(5)
            ->get();
        
        // Tickets by staff
        $ticketsByStaff = Ticket::whereBetween('tickets.created_at', [$startDate, $endDate])
            ->select('staff.name', DB::raw('count(*) as total'))
            ->leftJoin('staff', 'tickets.assigned_to', '=', 'staff.id')
            ->whereNotNull('staff.name')
            ->groupBy('staff.name')
            ->pluck('total', 'name')
            ->toArray();
        
        // Assign values for view
        $avgFollowUpTime = $avgTimes['follow_up'];
        $avgFollowUpUnit = $avgTimes['follow_up_unit'];
        $avgProcessingTime = $avgTimes['processing'];
        $avgProcessingUnit = $avgTimes['processing_unit'];
        $avgTotalTime = $avgTimes['total'];
        $avgTotalUnit = $avgTimes['total_unit'];
        
        // Date range for display
        $dateRange = [
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->format('Y-m-d'),
            'start_formatted' => $startDate->format('d M Y'),
            'end_formatted' => $endDate->format('d M Y'),
        ];
        
        return view('admin.reporting', compact(
            'ticketsByStatus',
            'ticketsByPriority',
            'ticketsByCategory',
            'avgFollowUpTime',
            'avgFollowUpUnit',
            'avgProcessingTime',
            'avgProcessingUnit',
            'avgTotalTime',
            'avgTotalUnit',
            'recentTickets',
            'ticketsByStaff',
            'period',
            'selectedMonth',
            'selectedQuarter',
            'selectedYear',
            'dateRange'
        ));
    }
    
    /**
     * Export tickets report as Excel
     */
    public function exportExcel(Request $request)
    {
        $period = $request->input('period', 'month');
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedQuarter = $request->input('quarter', ceil(Carbon::now()->month / 3));
        $selectedYear = $request->input('year', Carbon::now()->year);
        
        // Get date range
        $startDate = null;
        $endDate = null;
        $fileName = "ticket_report_";
        
        switch ($period) {
            case 'month':
                $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();
                $fileName .= $startDate->format('Y_m');
                break;
            case 'quarter':
                $startMonth = ($selectedQuarter - 1) * 3 + 1;
                $startDate = Carbon::createFromDate($selectedYear, $startMonth, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($selectedYear, $startMonth, 1)->addMonths(2)->endOfMonth();
                $fileName .= "Q{$selectedQuarter}_{$selectedYear}";
                break;
            case 'year':
                $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfYear();
                $endDate = Carbon::createFromDate($selectedYear, 12, 31)->endOfYear();
                $fileName .= $selectedYear;
                break;
        }
        
        return Excel::download(new TicketExport($startDate, $endDate), $fileName . '.xlsx');
    }
    
    /**
     * Export tickets report as PDF
     */
    public function exportPdf(Request $request)
    {
        $period = $request->input('period', 'month');
        $selectedMonth = $request->input('month', Carbon::now()->month);
        $selectedQuarter = $request->input('quarter', ceil(Carbon::now()->month / 3));
        $selectedYear = $request->input('year', Carbon::now()->year);
        
        // Get date range
        $startDate = null;
        $endDate = null;
        $fileName = "ticket_report_";
        
        switch ($period) {
            case 'month':
                $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();
                $fileName .= $startDate->format('Y_m');
                break;
            case 'quarter':
                $startMonth = ($selectedQuarter - 1) * 3 + 1;
                $startDate = Carbon::createFromDate($selectedYear, $startMonth, 1)->startOfMonth();
                $endDate = Carbon::createFromDate($selectedYear, $startMonth, 1)->addMonths(2)->endOfMonth();
                $fileName .= "Q{$selectedQuarter}_{$selectedYear}";
                break;
            case 'year':
                $startDate = Carbon::createFromDate($selectedYear, 1, 1)->startOfYear();
                $endDate = Carbon::createFromDate($selectedYear, 12, 31)->endOfYear();
                $fileName .= $selectedYear;
                break;
        }
        
        // Same logic as index method to generate data for PDF
        $ticketsByStatus = Ticket::whereBetween('tickets.created_at', [$startDate, $endDate])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        $ticketsByPriority = Ticket::whereBetween('tickets.created_at', [$startDate, $endDate])
            ->select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->pluck('total', 'priority')
            ->toArray();
        
        $ticketsByCategory = Ticket::whereBetween('tickets.created_at', [$startDate, $endDate])
            ->select('categories.name', DB::raw('count(*) as total'))
            ->leftJoin('categories', 'tickets.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->pluck('total', 'name')
            ->toArray();
            
        // Calculate average times
        $avgFollowUp = DB::select("
            SELECT AVG(TIMESTAMPDIFF(MINUTE, tickets.created_at, tickets.follow_up_at)) as avg_minutes,
                   COUNT(*) as total_tickets
            FROM tickets
            WHERE tickets.follow_up_at IS NOT NULL 
            AND tickets.status IN ('in_progress', 'done')
            AND tickets.created_at BETWEEN ? AND ?
        ", [$startDate, $endDate]);
        
        $avgProcessing = DB::select("
            SELECT AVG(TIMESTAMPDIFF(MINUTE, tickets.follow_up_at, tickets.resolved_at)) as avg_minutes,
                   COUNT(*) as total_tickets
            FROM tickets
            WHERE tickets.follow_up_at IS NOT NULL 
            AND tickets.resolved_at IS NOT NULL 
            AND tickets.status = 'done'
            AND tickets.created_at BETWEEN ? AND ?
        ", [$startDate, $endDate]);
        
        $avgTotal = DB::select("
            SELECT AVG(TIMESTAMPDIFF(MINUTE, tickets.created_at, tickets.resolved_at)) as avg_minutes,
                   COUNT(*) as total_tickets
            FROM tickets
            WHERE tickets.resolved_at IS NOT NULL 
            AND tickets.status = 'done'
            AND tickets.created_at BETWEEN ? AND ?
        ", [$startDate, $endDate]);
        
        // Format durations
        $followUpTime = $this->formatDuration($avgFollowUp[0]->avg_minutes ?? 0);
        $processingTime = $this->formatDuration($avgProcessing[0]->avg_minutes ?? 0);
        $totalTime = $this->formatDuration($avgTotal[0]->avg_minutes ?? 0);
        
        $dateRange = [
            'start_formatted' => $startDate->format('d M Y'),
            'end_formatted' => $endDate->format('d M Y'),
        ];
        
        $data = [
            'ticketsByStatus' => $ticketsByStatus,
            'ticketsByPriority' => $ticketsByPriority,
            'ticketsByCategory' => $ticketsByCategory,
            'avgFollowUpTime' => $followUpTime['value'],
            'avgFollowUpUnit' => $followUpTime['unit'],
            'avgProcessingTime' => $processingTime['value'],
            'avgProcessingUnit' => $processingTime['unit'],
            'avgTotalTime' => $totalTime['value'],
            'avgTotalUnit' => $totalTime['unit'],
            'dateRange' => $dateRange,
        ];
        
        $pdf = PDF::loadView('admin.pdf.ticket_report', $data);
        return $pdf->download($fileName . '.pdf');
    }
} 