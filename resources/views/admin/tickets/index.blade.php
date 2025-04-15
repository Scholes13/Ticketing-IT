@extends('layouts.admin')

@section('title', 'Tickets')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">
        Tickets 
        @if(request()->query('status'))
            - {{ ucfirst(request()->query('status')) }}
        @endif
    </h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Tickets</li>
    </ol>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-table me-1"></i>
                    Ticket List
                </div>
                <a href="{{ route('tickets.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> New Ticket
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form action="{{ route('tickets.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search..." name="search" value="{{ request()->query('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="waiting" {{ request()->query('status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                            <option value="in_progress" {{ request()->query('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="done" {{ request()->query('status') == 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="priority" class="form-select" onchange="this.form.submit()">
                            <option value="">All Priorities</option>
                            <option value="low" {{ request()->query('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request()->query('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request()->query('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ request()->query('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="category_id" class="form-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request()->query('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="assigned_to" class="form-select" onchange="this.form.submit()">
                            <option value="">All Staff</option>
                            <option value="unassigned" {{ request()->query('assigned_to') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                            @foreach($staff as $member)
                                <option value="{{ $member->id }}" {{ request()->query('assigned_to') == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('tickets.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>Title</th>
                            <th>Requester</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td>{{ $ticket->ticket_number }}</td>
                                <td>{{ Str::limit($ticket->title, 30) }}</td>
                                <td>{{ $ticket->requester_name }}</td>
                                <td>{{ $ticket->category ? $ticket->category->name : 'N/A' }}</td>
                                <td>
                                    @if($ticket->priority == 'low')
                                        <span class="badge bg-success">Low</span>
                                    @elseif($ticket->priority == 'medium')
                                        <span class="badge bg-warning text-dark">Medium</span>
                                    @elseif($ticket->priority == 'high')
                                        <span class="badge bg-danger">High</span>
                                    @elseif($ticket->priority == 'critical')
                                        <span class="badge bg-danger">Critical</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->status == 'waiting')
                                        <span class="badge bg-warning text-dark">Waiting</span>
                                    @elseif($ticket->status == 'in_progress')
                                        <span class="badge bg-info">In Progress</span>
                                    @elseif($ticket->status == 'done')
                                        <span class="badge bg-success">Done</span>
                                    @endif
                                </td>
                                <td>{{ $ticket->staff ? $ticket->staff->name : 'Unassigned' }}</td>
                                <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $ticket->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $ticket->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $ticket->id }}">Confirm Delete</h5>
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No tickets found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $tickets->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
