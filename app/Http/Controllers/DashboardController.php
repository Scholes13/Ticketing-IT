<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Show the dashboard with analytics
     */
    public function index()
    {
        // Count tickets by status
        $ticketsByStatus = Ticket::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
        
        // Count tickets by priority
        $ticketsByPriority = Ticket::select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->pluck('total', 'priority')
            ->toArray();
        
        // Count tickets by category
        $ticketsByCategory = Ticket::select('categories.name', DB::raw('count(*) as total'))
            ->leftJoin('categories', 'tickets.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->pluck('total', 'name')
            ->toArray();
        
        // Calculate average follow-up time (waiting to in_progress) using PHP instead of SQL
        $ticketsWithFollowUp = Ticket::whereNotNull('follow_up_at')->get();
        $totalFollowUpHours = 0;
        $followUpCount = count($ticketsWithFollowUp);
        
        foreach ($ticketsWithFollowUp as $ticket) {
            $totalFollowUpHours += $ticket->created_at->diffInHours($ticket->follow_up_at);
        }
        
        $avgFollowUpTime = $followUpCount > 0 ? $totalFollowUpHours / $followUpCount : 0;
        
        // Calculate average processing time (in_progress to done)
        $ticketsResolved = Ticket::whereNotNull('follow_up_at')->whereNotNull('resolved_at')->get();
        $totalProcessingHours = 0;
        $resolvedCount = count($ticketsResolved);
        
        foreach ($ticketsResolved as $ticket) {
            $totalProcessingHours += $ticket->follow_up_at->diffInHours($ticket->resolved_at);
        }
        
        $avgProcessingTime = $resolvedCount > 0 ? $totalProcessingHours / $resolvedCount : 0;
        
        // Calculate average total resolution time (created to done)
        $ticketsCompleted = Ticket::whereNotNull('resolved_at')->get();
        $totalResolutionHours = 0;
        $completedCount = count($ticketsCompleted);
        
        foreach ($ticketsCompleted as $ticket) {
            $totalResolutionHours += $ticket->created_at->diffInHours($ticket->resolved_at);
        }
        
        $avgTotalTime = $completedCount > 0 ? $totalResolutionHours / $completedCount : 0;
        
        // Recent tickets
        $recentTickets = Ticket::with(['category', 'staff'])
            ->latest()
            ->take(5)
            ->get();
        
        // Tickets by staff
        $ticketsByStaff = Ticket::select('staff.name', DB::raw('count(*) as total'))
            ->leftJoin('staff', 'tickets.assigned_to', '=', 'staff.id')
            ->groupBy('staff.name')
            ->pluck('total', 'name')
            ->toArray();
        
        // Tickets created over time (last 30 days)
        $ticketsOverTime = Ticket::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();
        
        return view('admin.dashboard', compact(
            'ticketsByStatus',
            'ticketsByPriority',
            'ticketsByCategory',
            'avgFollowUpTime',
            'avgProcessingTime',
            'avgTotalTime',
            'recentTickets',
            'ticketsByStaff',
            'ticketsOverTime'
        ));
    }
}
