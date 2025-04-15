<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Status - {{ $ticket->ticket_number }} - IT Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .ticket-container {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        .ticket-number {
            font-size: 1.2rem;
            font-weight: 500;
            color: #6c757d;
        }
        .status-badge {
            font-size: 1rem;
            padding: 8px 15px;
            border-radius: 20px;
        }
        .status-waiting {
            background-color: #ffc107;
            color: #212529;
        }
        .status-in_progress {
            background-color: #0d6efd;
            color: white;
        }
        .status-done {
            background-color: #198754;
            color: white;
        }
        .priority-badge {
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .priority-low {
            background-color: #198754;
            color: white;
        }
        .priority-medium {
            background-color: #fd7e14;
            color: white;
        }
        .priority-high, .priority-critical {
            background-color: #dc3545;
            color: white;
        }
        .ticket-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .ticket-description {
            margin-top: 20px;
            white-space: pre-line;
        }
        .comment-item {
            border-left: 3px solid #0d6efd;
            padding-left: 15px;
            margin-bottom: 15px;
        }
        .comment-date {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .nav-links {
            text-align: center;
            margin-top: 20px;
        }
        .timeline {
            position: relative;
            padding-left: 30px;
            margin-bottom: 20px;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }
        .timeline-item:before {
            content: "";
            position: absolute;
            left: -30px;
            top: 0;
            width: 2px;
            height: 100%;
            background-color: #dee2e6;
        }
        .timeline-item:last-child:before {
            height: 0;
        }
        .timeline-badge {
            position: absolute;
            left: -39px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
        }
        .timeline-content {
            padding: 10px 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .timeline-date {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="ticket-container">
            <div class="header">
                <h1>Ticket Status</h1>
                <div class="ticket-number mb-2">{{ $ticket->ticket_number }}</div>
                <span class="badge status-badge status-{{ $ticket->status }}">
                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                </span>
            </div>
            
            <div class="row mb-4">
                <div class="col-md-12">
                    <h4>{{ $ticket->title }}</h4>
                    <div class="d-flex align-items-center mt-2">
                        <span class="badge priority-badge priority-{{ $ticket->priority }} me-2">
                            {{ ucfirst($ticket->priority) }} Priority
                        </span>
                        @if($ticket->category)
                            <span class="badge bg-secondary me-2">{{ $ticket->category->name }}</span>
                        @endif
                        <span class="text-muted ms-2">Submitted on {{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="ticket-details">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Requester:</strong> {{ $ticket->requester_name }}</p>
                        <p><strong>Email:</strong> {{ $ticket->requester_email }}</p>
                        @if($ticket->requester_phone)
                            <p><strong>Phone:</strong> {{ $ticket->requester_phone }}</p>
                        @endif
                    </div>
                    <div class="col-md-6">
                        @if($ticket->department)
                            <p><strong>Department:</strong> {{ $ticket->department }}</p>
                        @endif
                        <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</p>
                        <p><strong>Assigned To:</strong> {{ $ticket->staff ? $ticket->staff->name : 'Not assigned yet' }}</p>
                    </div>
                </div>
                
                <div class="ticket-description">
                    <h5>Description</h5>
                    <p>{{ $ticket->description }}</p>
                </div>
            </div>
            
            <div class="timeline">
                <h5 class="mb-3">Ticket Timeline</h5>
                
                <div class="timeline-item">
                    <div class="timeline-badge">
                        <i class="bi bi-plus-circle-fill"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">{{ $ticket->created_at->format('M d, Y h:i A') }}</div>
                        <div>Ticket created</div>
                    </div>
                </div>
                
                @if($ticket->follow_up_at)
                <div class="timeline-item">
                    <div class="timeline-badge">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">{{ $ticket->follow_up_at->format('M d, Y h:i A') }}</div>
                        <div>Ticket processing started</div>
                    </div>
                </div>
                @endif
                
                @if($ticket->resolved_at)
                <div class="timeline-item">
                    <div class="timeline-badge">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">{{ $ticket->resolved_at->format('M d, Y h:i A') }}</div>
                        <div>Ticket resolved</div>
                    </div>
                </div>
                @endif
            </div>
            
            @if(count($ticket->comments->where('is_private', false)) > 0)
            <div class="comments mt-4">
                <h5>Comments</h5>
                @foreach($ticket->comments->where('is_private', false) as $comment)
                <div class="comment-item">
                    <div class="comment-date">{{ $comment->created_at->format('M d, Y h:i A') }}</div>
                    <div class="comment-content">{{ $comment->content }}</div>
                    @if($comment->attachment_path)
                    <div class="mt-2">
                        <a href="{{ Storage::url($comment->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-paperclip"></i> View Attachment
                        </a>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
            
            @if(count($ticket->attachments) > 0)
            <div class="attachments mt-4">
                <h5>Attachments</h5>
                <div class="list-group">
                    @foreach($ticket->attachments as $attachment)
                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-paperclip me-2"></i>
                            {{ $attachment->original_filename }}
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ number_format($attachment->file_size / 1024, 2) }} KB</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
            
            <div class="nav-links mt-4">
                <a href="{{ route('public.check.ticket') }}" class="btn btn-outline-secondary">Check Another Ticket</a>
                <a href="{{ route('public.create.ticket') }}" class="btn btn-outline-primary">Submit a New Ticket</a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
