<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Category;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PublicController extends Controller
{
    /**
     * Show the ticket submission form
     */
    public function createTicket()
    {
        $categories = Category::all();
        return view('public.create_ticket', compact('categories'));
    }
    
    /**
     * Store a new ticket
     */
    public function storeTicket(Request $request)
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
            'attachments.*' => 'nullable|file|max:10240',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Generate a unique ticket number
        $ticketNumber = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        
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
            'status' => 'waiting',
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
}
