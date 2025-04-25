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
        return view('public.create_ticket', compact('categories', 'departments', 'staffMembers'));
    }
    
    /**
     * Store a new ticket
     */
    public function storeTicket(Request $request)
    {
        // Check for duplicate submission using the submission token
        $submissionToken = $request->input('_submission_token');
        
        // If token is missing, reject the submission
        if (!$submissionToken) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Invalid form submission. Please try again.']);
        }
        
        // Check if this token has been used before
        if (Session::has('used_tokens') && in_array($submissionToken, Session::get('used_tokens', []))) {
            // This is a duplicate submission, redirect back to the form
            return redirect()->back()
                ->with('error', 'Your ticket has already been submitted. Please do not submit the same form multiple times.');
        }
        
        // Store the token in the session
        $usedTokens = Session::get('used_tokens', []);
        $usedTokens[] = $submissionToken;
        Session::put('used_tokens', $usedTokens);
        
        // Limit the number of stored tokens to prevent session bloat
        if (count($usedTokens) > 10) {
            Session::put('used_tokens', array_slice($usedTokens, -10));
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requester_name' => 'required|exists:staff,id',
            'department' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,id',
            'priority' => 'required|in:low,medium,high,critical',
            'attachments.*' => 'nullable|file|max:10240',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
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
