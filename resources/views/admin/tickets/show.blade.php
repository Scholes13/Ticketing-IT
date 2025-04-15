@extends('layouts.admin')

@section('title', 'Ticket Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Ticket Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Tickets</a></li>
        <li class="breadcrumb-item active">{{ $ticket->ticket_number }}</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="row">
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $ticket->title }}</h5>
                        <span class="text-muted">{{ $ticket->ticket_number }}</span>
                    </div>
                    <div>
                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Requester:</strong> {{ $ticket->requester_name }}</p>
                            <p><strong>Email:</strong> {{ $ticket->requester_email }}</p>
                            @if($ticket->requester_phone)
                                <p><strong>Phone:</strong> {{ $ticket->requester_phone }}</p>
                            @endif
                            @if($ticket->department)
                                <p><strong>Department:</strong> {{ $ticket->department }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p>
                                <strong>Status:</strong>
                                @if($ticket->status == 'waiting')
                                    <span class="badge bg-warning text-dark">Waiting</span>
                                @elseif($ticket->status == 'in_progress')
                                    <span class="badge bg-info">In Progress</span>
                                @elseif($ticket->status == 'done')
                                    <span class="badge bg-success">Done</span>
                                @endif
                            </p>
                            <p>
                                <strong>Priority:</strong>
                                @if($ticket->priority == 'low')
                                    <span class="badge bg-success">Low</span>
                                @elseif($ticket->priority == 'medium')
                                    <span class="badge bg-warning text-dark">Medium</span>
                                @elseif($ticket->priority == 'high')
                                    <span class="badge bg-danger">High</span>
                                @elseif($ticket->priority == 'critical')
                                    <span class="badge bg-danger">Critical</span>
                                @endif
                            </p>
                            <p><strong>Category:</strong> {{ $ticket->category ? $ticket->category->name : 'N/A' }}</p>
                            <p><strong>Assigned To:</strong> {{ $ticket->staff ? $ticket->staff->name : 'Unassigned' }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Description</h5>
                        <div class="p-3 bg-light rounded">
                            {{ $ticket->description }}
                        </div>
                    </div>
                    
                    @if(count($ticket->attachments) > 0)
                        <div class="mb-4">
                            <h5>Attachments</h5>
                            <div class="list-group">
                                @foreach($ticket->attachments as $attachment)
                                    <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-paperclip me-2"></i>
                                            {{ $attachment->original_filename }}
                                        </div>
                                        <span class="badge bg-primary rounded-pill">{{ number_format($attachment->file_size / 1024, 2) }} KB</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-4">
                        <h5>Timeline</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Ticket Created</h6>
                                    <small>{{ $ticket->created_at->format('M d, Y h:i A') }}</small>
                                </div>
                                <p class="mb-1">Ticket was created by {{ $ticket->requester_name }}</p>
                            </li>
                            
                            @if($ticket->follow_up_at)
                                <li class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Processing Started</h6>
                                        <small>{{ $ticket->follow_up_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                    <p class="mb-1">Ticket status changed to In Progress</p>
                                </li>
                            @endif
                            
                            @if($ticket->resolved_at)
                                <li class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">Ticket Resolved</h6>
                                        <small>{{ $ticket->resolved_at->format('M d, Y h:i A') }}</small>
                                    </div>
                                    <p class="mb-1">Ticket status changed to Done</p>
                                </li>
                            @endif
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Comments</h5>
                        @if(count($ticket->comments) > 0)
                            <div class="comment-list">
                                @foreach($ticket->comments as $comment)
                                    <div class="card mb-2 {{ $comment->is_private ? 'border-warning' : 'border-info' }}">
                                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $comment->user ? $comment->user->name : 'System' }}</strong>
                                                @if($comment->is_private)
                                                    <span class="badge bg-warning text-dark ms-2">Private</span>
                                                @endif
                                            </div>
                                            <small>{{ $comment->created_at->format('M d, Y h:i A') }}</small>
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $comment->content }}</p>
                                            @if($comment->attachment_path)
                                                <a href="{{ Storage::url($comment->attachment_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-paperclip"></i> View Attachment
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No comments yet</p>
                        @endif
                    </div>
                    
                    <div>
                        <h5>Add Comment</h5>
                        <form action="{{ route('tickets.comment', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <textarea class="form-control" name="content" rows="3" placeholder="Enter your comment here..." required></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_private" name="is_private">
                                    <label class="form-check-label" for="is_private">
                                        Private comment (not visible to requester)
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="attachment" class="form-label">Attachment (optional)</label>
                                <input class="form-control" type="file" id="attachment" name="attachment">
                            </div>
                            <button type="submit" class="btn btn-primary">Add Comment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6>Change Status</h6>
                        <form action="{{ route('tickets.status', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="d-grid gap-2">
                                @if($ticket->status != 'waiting')
                                    <button type="submit" name="status" value="waiting" class="btn btn-warning text-dark">
                                        <i class="fas fa-clock me-2"></i> Mark as Waiting
                                    </button>
                                @endif
                                
                                @if($ticket->status != 'in_progress')
                                    <button type="submit" name="status" value="in_progress" class="btn btn-info text-white">
                                        <i class="fas fa-spinner me-2"></i> Mark as In Progress
                                    </button>
                                @endif
                                
                                @if($ticket->status != 'done')
                                    <button type="submit" name="status" value="done" class="btn btn-success">
                                        <i class="fas fa-check-circle me-2"></i> Mark as Done
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Assign Ticket</h6>
                        <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <select class="form-select" name="assigned_to" required>
                                    <option value="">-- Select Staff --</option>
                                    @foreach($staff as $member)
                                        <option value="{{ $member->id }}" {{ $ticket->assigned_to == $member->id ? 'selected' : '' }}>
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-check me-2"></i> Assign Ticket
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <div>
                        <h6>Ticket Information</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Created
                                <span>{{ $ticket->created_at->format('M d, Y') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Last Updated
                                <span>{{ $ticket->updated_at->format('M d, Y') }}</span>
                            </li>
                            @if($ticket->follow_up_at)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Follow-up Time
                                    <span>{{ $ticket->follow_up_time ?? 'N/A' }} hours</span>
                                </li>
                            @endif
                            @if($ticket->resolved_at)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Processing Time
                                    <span>{{ $ticket->processing_time ?? 'N/A' }} hours</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    Total Time
                                    <span>{{ $ticket->total_time ?? 'N/A' }} hours</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete ticket <strong>{{ $ticket->ticket_number }}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
