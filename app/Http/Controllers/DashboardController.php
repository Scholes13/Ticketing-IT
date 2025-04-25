<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
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
     * Show the dashboard with analytics
     */
    public function index()
    {
        // Use shorter caching to improve real-time updates
        $cacheTTL = 1; // Cache for 1 minute
        
        // Count tickets by status - use caching
        $ticketsByStatus = Cache::remember('dashboard_tickets_by_status', $cacheTTL, function() {
            return Ticket::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();
        });
        
        // Count tickets by priority - use caching
        $ticketsByPriority = Cache::remember('dashboard_tickets_by_priority', $cacheTTL, function() {
            return Ticket::select('priority', DB::raw('count(*) as total'))
                ->groupBy('priority')
                ->pluck('total', 'priority')
                ->toArray();
        });
        
        // Count tickets by category - use caching
        $ticketsByCategory = Cache::remember('dashboard_tickets_by_category', $cacheTTL, function() {
            return Ticket::select('categories.name', DB::raw('count(*) as total'))
                ->leftJoin('categories', 'tickets.category_id', '=', 'categories.id')
                ->groupBy('categories.name')
                ->pluck('total', 'name')
                ->toArray();
        });
        
        // Calculate average times - use more efficient queries with shorter cache
        $avgTimes = Cache::remember('dashboard_avg_times', $cacheTTL, function() {
            // Follow-up time (waiting to in_progress)
            $avgFollowUp = DB::select("
                SELECT AVG(TIMESTAMPDIFF(MINUTE, created_at, follow_up_at)) as avg_minutes,
                       COUNT(*) as total_tickets
                FROM tickets
                WHERE follow_up_at IS NOT NULL 
                AND status IN ('in_progress', 'done')
            ");
            
            // Processing time (in_progress to done)
            $avgProcessing = DB::select("
                SELECT AVG(TIMESTAMPDIFF(MINUTE, follow_up_at, resolved_at)) as avg_minutes,
                       COUNT(*) as total_tickets
                FROM tickets
                WHERE follow_up_at IS NOT NULL 
                AND resolved_at IS NOT NULL 
                AND status = 'done'
            ");
            
            // Total resolution time (waiting to done)
            $avgTotal = DB::select("
                SELECT AVG(TIMESTAMPDIFF(MINUTE, created_at, resolved_at)) as avg_minutes,
                       COUNT(*) as total_tickets
                FROM tickets
                WHERE resolved_at IS NOT NULL 
                AND status = 'done'
            ");
            
            // Format durations
            $followUpTime = $this->formatDuration($avgFollowUp[0]->avg_minutes ?? 0);
            $processingTime = $this->formatDuration($avgProcessing[0]->avg_minutes ?? 0);
            $totalTime = $this->formatDuration($avgTotal[0]->avg_minutes ?? 0);
            
            return [
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
        });
        
        // Recent tickets - using eager loading to reduce queries
        $recentTickets = Cache::remember('dashboard_recent_tickets', $cacheTTL / 3, function() {
            return Ticket::with(['category', 'staff'])
                ->latest()
                ->take(5)
                ->get();
        });
        
        // Tickets by staff - use caching
        $ticketsByStaff = Cache::remember('dashboard_tickets_by_staff', $cacheTTL, function() {
            return Ticket::select('staff.name', DB::raw('count(*) as total'))
                ->leftJoin('staff', 'tickets.assigned_to', '=', 'staff.id')
                ->whereNotNull('staff.name')
                ->groupBy('staff.name')
                ->pluck('total', 'name')
                ->toArray();
        });
        
        // Tickets created over time (last 30 days) - use caching
        $ticketsOverTime = Cache::remember('dashboard_tickets_over_time', $cacheTTL, function() {
            return Ticket::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('date')
                ->pluck('total', 'date')
                ->toArray();
        });
        
        // Assign values for view
        $avgFollowUpTime = $avgTimes['follow_up'];
        $avgFollowUpUnit = $avgTimes['follow_up_unit'];
        $avgProcessingTime = $avgTimes['processing'];
        $avgProcessingUnit = $avgTimes['processing_unit'];
        $avgTotalTime = $avgTimes['total'];
        $avgTotalUnit = $avgTimes['total_unit'];
        
        return view('admin.dashboard', compact(
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
            'ticketsOverTime'
        ));
    }
    
    /**
     * Filter dashboard data based on time period
     */
    public function filterData(Request $request)
    {
        $period = $request->get('period', 'today');
        
        // Define date ranges based on filter
        $startDate = now();
        $endDate = now();
        
        switch ($period) {
            case 'today':
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case 'week':
                $startDate = now()->startOfWeek();
                $endDate = now()->endOfWeek();
                break;
            case 'month':
                $startDate = now()->startOfMonth();
                $endDate = now()->endOfMonth();
                break;
            case 'year':
                $startDate = now()->startOfYear();
                $endDate = now()->endOfYear();
                break;
            case 'all':
                // Set a very old start date to include all records
                $startDate = now()->subYears(50);
                $endDate = now();
                break;
            default:
                $startDate = now()->startOfDay();
                $endDate = now()->endOfDay();
        }
        
        // Count tickets by status with date filter
        $ticketsByStatus = Ticket::select('status', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        // Count tickets by priority with date filter
        $ticketsByPriority = Ticket::select('priority', DB::raw('count(*) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('priority')
            ->pluck('total', 'priority')
            ->toArray();
        
        // Count tickets by category with date filter
        $ticketsByCategory = Ticket::select('categories.name', DB::raw('count(*) as total'))
            ->leftJoin('categories', 'tickets.category_id', '=', 'categories.id')
            ->whereBetween('tickets.created_at', [$startDate, $endDate])
            ->groupBy('categories.name')
            ->pluck('total', 'name')
            ->toArray();
        
        // Calculate average times with date filter
        // Follow-up time (waiting to in_progress)
        $avgFollowUp = DB::select("
            SELECT AVG(TIMESTAMPDIFF(MINUTE, created_at, follow_up_at)) as avg_minutes,
                   COUNT(*) as total_tickets
            FROM tickets
            WHERE follow_up_at IS NOT NULL 
            AND status IN ('in_progress', 'done')
            AND created_at BETWEEN ? AND ?
        ", [$startDate, $endDate]);
        
        // Processing time (in_progress to done)
        $avgProcessing = DB::select("
            SELECT AVG(TIMESTAMPDIFF(MINUTE, follow_up_at, resolved_at)) as avg_minutes,
                   COUNT(*) as total_tickets
            FROM tickets
            WHERE follow_up_at IS NOT NULL 
            AND resolved_at IS NOT NULL 
            AND status = 'done'
            AND created_at BETWEEN ? AND ?
        ", [$startDate, $endDate]);
        
        // Total resolution time (waiting to done)
        $avgTotal = DB::select("
            SELECT AVG(TIMESTAMPDIFF(MINUTE, created_at, resolved_at)) as avg_minutes,
                   COUNT(*) as total_tickets
            FROM tickets
            WHERE resolved_at IS NOT NULL 
            AND status = 'done'
            AND created_at BETWEEN ? AND ?
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
        
        // Recent tickets with date filter
        $recentTickets = Ticket::with(['category', 'staff'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(5)
            ->get();
        
        // Tickets by staff with date filter
        $ticketsByStaff = Ticket::select('staff.name', DB::raw('count(*) as total'))
            ->leftJoin('staff', 'tickets.assigned_to', '=', 'staff.id')
            ->whereNotNull('staff.name')
            ->whereBetween('tickets.created_at', [$startDate, $endDate])
            ->groupBy('staff.name')
            ->pluck('total', 'name')
            ->toArray();
        
        return response()->json([
            'ticketsByStatus' => $ticketsByStatus,
            'ticketsByPriority' => $ticketsByPriority,
            'ticketsByCategory' => $ticketsByCategory,
            'avgTimes' => $avgTimes,
            'recentTickets' => $recentTickets,
            'ticketsByStaff' => $ticketsByStaff,
            'period' => $period
        ]);
    }
}
