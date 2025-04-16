<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketCategoryController extends Controller
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
     * Display waiting tickets.
     */
    public function waiting(Request $request)
    {
        return $this->getTicketsByStatus($request, 'waiting');
    }
    
    /**
     * Display in-progress tickets.
     */
    public function inProgress(Request $request)
    {
        return $this->getTicketsByStatus($request, 'in_progress');
    }
    
    /**
     * Display done tickets.
     */
    public function done(Request $request)
    {
        return $this->getTicketsByStatus($request, 'done');
    }
    
    /**
     * Display all tickets.
     */
    public function all(Request $request)
    {
        return $this->getTicketsByStatus($request, 'all');
    }
    
    /**
     * Get tickets by status with filters.
     */
    private function getTicketsByStatus(Request $request, $status)
    {
        $query = Ticket::with(['category', 'staff']);
        
        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }
        
        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter by assigned staff
        if ($request->has('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }
        
        // Search by ticket number or title
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('requester_name', 'like', "%{$search}%")
                  ->orWhere('requester_email', 'like', "%{$search}%");
            });
        }
        
        $tickets = $query->latest()->paginate(10);
        $categories = Category::orderBy('name', 'asc')->get();
        $staff = Staff::where('is_active', true)
                ->orderBy('name', 'asc')
                ->get();
        
        // Get summary counts for different statuses
        $statusCounts = [
            'all' => Ticket::count(),
            'waiting' => Ticket::where('status', 'waiting')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'done' => Ticket::where('status', 'done')->count()
        ];

        // Use different views based on status
        $view = match($status) {
            'waiting' => 'admin.tickets.waiting',
            'in_progress' => 'admin.tickets.in_progress',
            'done' => 'admin.tickets.done',
            default => 'admin.tickets.category',
        };
        
        return view($view, compact(
            'tickets', 
            'categories', 
            'staff', 
            'status',
            'statusCounts'
        ));
    }
} 