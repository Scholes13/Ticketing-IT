<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all tickets
        $tickets = Ticket::all();
        
        foreach ($tickets as $ticket) {
            // Skip if the ticket already has the new format
            if (strpos($ticket->ticket_number, 'WG-') === 0) {
                continue;
            }
            
            // Extract the date from the old format (TKT-YYYYMMDD-XXXXX)
            preg_match('/TKT-(\d{8})-/', $ticket->ticket_number, $matches);
            if (isset($matches[1])) {
                $date = $matches[1];
                // Convert YYYYMMDD to YYMMDD
                $shortDate = substr($date, 2);
                
                // Generate a new ticket number
                $newTicketNumber = 'WG-' . $shortDate . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
                
                // Update the ticket
                $ticket->ticket_number = $newTicketNumber;
                $ticket->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible
    }
};
