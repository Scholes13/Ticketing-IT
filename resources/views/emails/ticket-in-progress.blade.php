<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3b82f6;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .ticket-number {
            font-size: 18px;
            font-weight: bold;
            background-color: #f3f4f6;
            padding: 10px;
            text-align: center;
            margin: 20px 0;
            border-radius: 5px;
        }
        .status-badge {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: bold;
        }
        .details {
            margin-bottom: 20px;
        }
        .details h3 {
            margin-top: 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .details table {
            width: 100%;
            border-collapse: collapse;
        }
        .details table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .details table td:first-child {
            font-weight: bold;
            width: 140px;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Ticket Is In Progress</h1>
        </div>
        <div class="content">
            <p>Dear {{ $ticket->requester_name }},</p>
            
            <p>Great news! Your IT support ticket is now being worked on. Our team is actively addressing the issue you reported.</p>
            
            <div class="ticket-number">
                {{ $ticket->ticket_number }}
            </div>
            
            <p>
                Status update: <span class="status-badge">In Progress</span>
            </p>
            
            <div class="details">
                <h3>Ticket Summary</h3>
                <table>
                    <tr>
                        <td>Title:</td>
                        <td>{{ $ticket->title }}</td>
                    </tr>
                    <tr>
                        <td>Priority:</td>
                        <td>{{ ucfirst($ticket->priority) }}</td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</td>
                    </tr>
                    <tr>
                        <td>Submitted on:</td>
                        <td>{{ $ticket->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Started on:</td>
                        <td>{{ $ticket->follow_up_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @if($ticket->assigned_to && $ticket->staff)
                    <tr>
                        <td>Assigned to:</td>
                        <td>{{ $ticket->staff->name }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            <p>
                <a href="{{ route('public.view.ticket.status', ['ticket_number' => $ticket->ticket_number]) }}" class="button">View Ticket Details</a>
            </p>
            
            <p>If you have any questions or additional information to provide, please contact the IT Support team directly or check your ticket status using the link above.</p>
            
            <p>Thank you for your patience,<br>
            IT Support Team</p>
        </div>
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html> 