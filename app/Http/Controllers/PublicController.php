<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\Staff;
use App\Models\Department;
use App\Models\Attachment;
use App\Mail\TicketConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class PublicController extends Controller
{
    /**
     * Show the ticket submission form
     */
    public function createTicket()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $departments = Department::where('is_active', true)
                      ->orderBy('name', 'asc')
                      ->get();
        $staffMembers = Staff::where('is_active', true)
                      ->orderBy('name', 'asc')
                      ->get();
        return view('public.ticket_form_with_kb', compact('categories', 'departments', 'staffMembers'));
    }
    
    /**
     * Store a new ticket
     */
    public function storeTicket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requester_name' => 'required|exists:staff,id',
            'department' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'priority' => 'required|in:low,medium,high,critical',
            'attachments.*' => 'nullable|file|max:10240',
            'form_token' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Check for duplicate submission using the form token
        $formToken = $request->input('form_token');
        $cacheKey = 'form_submission_' . $formToken;
        
        // If this form was already submitted, redirect to prevent duplicate
        if (Cache::has($cacheKey)) {
            return redirect()->route('public.check.ticket')
                ->with('warning', 'Your ticket has already been submitted. You can check its status here.');
        }
        
        // Store form token in cache to prevent duplicate submissions
        // Set an expiration of 30 minutes
        Cache::put($cacheKey, true, 1800);
        
        // Generate ticket number
        $ticketNumber = 'WG-' . date('ymd') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Get staff details
        $staff = Staff::findOrFail($request->requester_name);
        
        // Find default staff to assign (Pramuji Arif Yulianto from BAS department)
        $defaultStaff = Staff::where('name', 'Pramuji Arif Yulianto')
            ->where('department', 'BAS')
            ->first();
            
        // If not found, try to find any staff from BAS department
        if (!$defaultStaff) {
            $defaultStaff = Staff::where('department', 'BAS')->first();
        }
        
        // Create the ticket
        $ticket = Ticket::create([
            'ticket_number' => $ticketNumber,
            'title' => $request->title,
            'description' => $request->description,
            'requester_name' => $staff->name,
            'requester_email' => $staff->email,
            'department' => $request->department,
            'category_id' => $request->category_id,
            'priority' => $request->priority,
            'status' => 'waiting',
            'assigned_to' => $defaultStaff ? $defaultStaff->id : null,
            'form_token' => $formToken, // Store form token with the ticket for reference
        ]);
        
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
        
        // Send confirmation email
        try {
            Mail::to($ticket->requester_email)
                ->send(new TicketConfirmation($ticket));
        } catch (\Exception $e) {
            // Log the error but don't stop the process
            \Log::error('Failed to send ticket confirmation email: ' . $e->getMessage());
        }
        
        return redirect()->route('public.ticket.confirmation', $ticket->ticket_number)
            ->with('success', 'Your ticket has been submitted successfully!');
    }
    
    /**
     * Show ticket confirmation page
     */
    public function ticketConfirmation($ticketNumber)
    {
        $ticket = Ticket::where('ticket_number', $ticketNumber)->firstOrFail();
        return view('public.ticket_confirmation', compact('ticket'));
    }
    
    /**
     * Show ticket status check form
     */
    public function checkTicketForm()
    {
        return view('public.check_ticket');
    }
    
    /**
     * Check ticket status
     */
    public function checkTicketStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ticket_number' => 'required|string|exists:tickets,ticket_number',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Ticket not found. Please check the ticket number and try again.');
        }
        
        $ticket = Ticket::where('ticket_number', $request->ticket_number)
            ->with(['category', 'attachments', 'comments' => function($query) {
                $query->where('is_private', false);
            }])
            ->firstOrFail();
        
        return view('public.ticket_status', compact('ticket'));
    }
    
    /**
     * View ticket status directly via URL (for email links)
     */
    public function viewTicketStatus($ticketNumber)
    {
        $ticket = Ticket::where('ticket_number', $ticketNumber)
            ->with(['category', 'attachments', 'comments' => function($query) {
                $query->where('is_private', false);
            }])
            ->firstOrFail();
        
        return view('public.ticket_status', compact('ticket'));
    }
}
