<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket Confirmation</title>
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
            background-color: #2e4c92;
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
            background-color: #2e4c92;
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
            <h1>Ticket Submitted Successfully</h1>
        </div>
        <div class="content">
            <p>Dear {{ $ticket->requester_name }},</p>
            
            <p>Thank you for contacting IT Support. We have received your ticket and will address it as soon as possible.</p>
            
            <div class="ticket-number">
                {{ $ticket->ticket_number }}
            </div>
            
            <p>Please save this ticket number for future reference. You can use it to check the status of your request.</p>
            
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
                        <td>Submitted by:</td>
                        <td>{{ $ticket->requester_name }}</td>
                    </tr>
                    <tr>
                        <td>Email:</td>
                        <td>{{ $ticket->requester_email }}</td>
                    </tr>
                    <tr>
                        <td>Submitted on:</td>
                        <td>{{ $ticket->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @if($ticket->department)
                    <tr>
                        <td>Department:</td>
                        <td>{{ $ticket->department }}</td>
                    </tr>
                    @endif
                </table>
            </div>
            
            <div class="details">
                <h3>Description</h3>
                <p>{{ $ticket->description }}</p>
            </div>
            
            <p>
                <a href="{{ route('public.view.ticket.status', ['ticket_number' => $ticket->ticket_number]) }}" class="button">Check Ticket Status</a>
            </p>
            
            <p>If you need immediate assistance, please contact the IT Support team directly.</p>
            
            <p>Thank you,<br>
            IT Support Team</p>
        </div>
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html> 