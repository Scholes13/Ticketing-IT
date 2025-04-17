<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Staff;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Attachment;
use App\Mail\TicketStatusInProgress;
use App\Mail\TicketStatusDone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class TicketController extends Controller
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
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
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
            $query->where('assigned_to', $request->assigned_to);
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
        
        return view('admin.tickets.index', compact('tickets', 'categories', 'staff', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $staff = Staff::where('is_active', true)
                ->orderBy('name', 'asc')
                ->get();
        return view('admin.tickets.create', compact('categories', 'staff'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requester_name' => 'required|string|max:255',
            'requester_email' => 'required|email|max:255',
            'requester_phone' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'priority' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:waiting,in_progress,done',
            'assigned_to' => 'nullable|exists:staff,id',
            'attachments.*' => 'nullable|file|max:10240',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Generate a unique ticket number
        $ticketNumber = 'WG-' . date('ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Create the ticket
        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'title' => $request->title,
            'description' => $request->description,
            'requester_name' => $request->requester_name,
            'requester_email' => $request->requester_email,
            'requester_phone' => $request->requester_phone,
            'department' => $request->department,
            'category_id' => $request->category_id,
            'priority' => $request->priority,
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
        ]);
        
        // Update timestamps based on status
        if ($request->status == 'in_progress') {
            $ticket->follow_up_at = now();
            $ticket->save();
        } elseif ($request->status == 'done') {
            $ticket->follow_up_at = now();
            $ticket->resolved_at = now();
            $ticket->save();
        }
        
        // Handle file attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('attachments', $filename, 'public');
                
                Attachment::create([
                    'ticket_id' => $ticket->id,
                    'filename' => $filename,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }
        
        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ticket = Ticket::with(['category', 'staff', 'comments.user', 'attachments'])
            ->findOrFail($id);
        $staff = Staff::where('is_active', true)
                ->where('department', 'BAS')
                ->get();
        
        return view('admin.tickets.show', compact('ticket', 'staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();
        $staff = Staff::where('is_active', true)
                ->where('department', 'BAS')
                ->orderBy('name', 'asc')
                ->get();
        $previousUrl = url()->previous();
        
        return view('admin.tickets.edit', compact('ticket', 'categories', 'staff', 'previousUrl'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'requester_name' => 'required|string|max:255',
            'requester_email' => 'required|email|max:255',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:waiting,in_progress,done',
            'assigned_to' => 'nullable|exists:staff,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $ticket = Ticket::findOrFail($id);
        $oldStatus = $ticket->status;
        $newStatus = $request->status;
        
        // Update the ticket
        $ticket->update([
            'subject' => $request->subject,
            'description' => $request->description,
            'requester_name' => $request->requester_name,
            'requester_email' => $request->requester_email,
            'priority' => $request->priority,
            'status' => $newStatus,
            'assigned_to' => $request->assigned_to,
        ]);
        
        // Update timestamps based on status changes
        if ($oldStatus != 'in_progress' && $newStatus == 'in_progress') {
            $ticket->follow_up_at = now();
            $ticket->save();
        } elseif ($oldStatus != 'done' && $newStatus == 'done') {
            if (!$ticket->follow_up_at) {
                $ticket->follow_up_at = now();
            }
            $ticket->resolved_at = now();
            $ticket->save();
        }
        
        // Send email notifications based on status changes
        if ($oldStatus == 'waiting' && $newStatus == 'in_progress') {
            // Kirim email notifikasi status "in progress"
            Mail::to($ticket->requester_email)
                ->queue(new TicketStatusInProgress($ticket));
        } elseif (($oldStatus == 'in_progress' || $oldStatus == 'waiting') && $newStatus == 'done') {
            // Kirim email notifikasi status "done"
            Mail::to($ticket->requester_email)
                ->queue(new TicketStatusDone($ticket));
        }
        
        // Redirect to the previous URL if provided, otherwise to the show page
        $redirectUrl = $request->input('previous_url') ?: route('tickets.show', $ticket->id);
        
        return redirect($redirectUrl)
            ->with('success', 'Ticket updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ticket = Ticket::findOrFail($id);
        
        // Delete attachments
        foreach ($ticket->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }
        
        $ticket->delete();
        
        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully!');
    }
    
    /**
     * Add a comment to a ticket
     */
    public function addComment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
            'is_private' => 'boolean',
            'attachment' => 'nullable|file|max:10240',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $ticket = Ticket::findOrFail($id);
        
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('comment_attachments', $filename, 'public');
        }
        
        Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'content' => $request->content,
            'is_private' => $request->has('is_private'),
            'attachment_path' => $attachmentPath,
        ]);
        
        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Comment added successfully!');
    }
    
    /**
     * Change ticket status
     */
    public function changeStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:waiting,in_progress,done',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $ticket = Ticket::findOrFail($id);
        $oldStatus = $ticket->status;
        $newStatus = $request->status;
        
        $ticket->status = $newStatus;
        $now = now();
        
        // Update timestamps based on status changes
        if ($oldStatus == 'waiting' && $newStatus == 'in_progress') {
            // Dari waiting ke in_progress
            $ticket->follow_up_at = $now;
        } elseif ($oldStatus == 'in_progress' && $newStatus == 'done') {
            // Dari in_progress ke done
            $ticket->resolved_at = $now;
        } elseif ($oldStatus == 'waiting' && $newStatus == 'done') {
            // Langsung dari waiting ke done
            if (!$ticket->follow_up_at) {
                $ticket->follow_up_at = $now;
            }
            $ticket->resolved_at = $now;
        } elseif ($newStatus == 'waiting') {
            // Jika dikembalikan ke waiting, reset timestamp
            $ticket->follow_up_at = null;
            $ticket->resolved_at = null;
        }
        
        $ticket->save();
        
        // Clear dashboard caches to update metrics immediately
        Cache::forget('dashboard_tickets_by_status');
        Cache::forget('dashboard_avg_times');
        Cache::forget('dashboard_tickets_by_priority');
        Cache::forget('dashboard_recent_tickets');
        
        // Add a comment about the status change
        Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'content' => "Status changed from {$oldStatus} to {$newStatus}",
            'is_private' => true,
        ]);
        
        // Send email notifications based on status changes
        if ($oldStatus == 'waiting' && $newStatus == 'in_progress') {
            // Kirim email notifikasi status "in progress"
            Mail::to($ticket->requester_email)
                ->queue(new TicketStatusInProgress($ticket));
        } elseif (($oldStatus == 'in_progress' || $oldStatus == 'waiting') && $newStatus == 'done') {
            // Kirim email notifikasi status "done"
            Mail::to($ticket->requester_email)
                ->queue(new TicketStatusDone($ticket));
        }
        
        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket status updated successfully!');
    }
    
    /**
     * Assign ticket to staff
     */
    public function assignTicket(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'assigned_to' => 'required|exists:staff,id',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $ticket = Ticket::findOrFail($id);
        $staff = Staff::findOrFail($request->assigned_to);
        
        // Check if staff is from BAS department
        if ($staff->department !== 'BAS') {
            return redirect()->back()
                ->withErrors(['assigned_to' => 'Only staff from BAS department can be assigned to tickets'])
                ->withInput();
        }
        
        $ticket->assigned_to = $staff->id;
        $ticket->save();
        
        // Add a comment about the assignment
        Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'content' => "Ticket assigned to {$staff->name}",
            'is_private' => true,
        ]);
        
        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', "Ticket assigned to {$staff->name} successfully!");
    }
}
