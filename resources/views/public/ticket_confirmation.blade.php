<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Submitted - IT Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .confirmation-container {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 5rem;
            color: #198754;
            margin-bottom: 20px;
        }
        .ticket-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 20px 0;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            display: inline-block;
        }
        .ticket-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: left;
        }
        .nav-links {
            margin-top: 30px;
        }
        .btn-action {
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="confirmation-container">
            <div class="success-icon">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            
            <h1>Ticket Submitted Successfully!</h1>
            <p class="lead">Thank you for contacting IT Support. We have received your ticket and will address it as soon as possible.</p>
            
            <div class="ticket-number">
                {{ $ticket->ticket_number }}
            </div>
            
            <p>Please save this ticket number for future reference. You can use it to check the status of your request.</p>
            
            <div class="ticket-details">
                <h5>Ticket Summary</h5>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Title:</strong> {{ $ticket->title }}</p>
                        <p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>
                        <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Submitted by:</strong> {{ $ticket->requester_name }}</p>
                        <p><strong>Email:</strong> {{ $ticket->requester_email }}</p>
                        <p><strong>Submitted on:</strong> {{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="nav-links">
                <a href="{{ route('public.check.ticket') }}" class="btn btn-primary btn-action">
                    <i class="bi bi-search me-2"></i> Check Ticket Status
                </a>
                <a href="{{ route('public.create.ticket') }}" class="btn btn-outline-secondary btn-action">
                    <i class="bi bi-plus-circle me-2"></i> Submit Another Ticket
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
