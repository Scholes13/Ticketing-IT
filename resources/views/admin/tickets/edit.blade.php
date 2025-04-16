@extends('layouts.admin')

@section('title', 'Edit Ticket')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Edit Ticket</h1>
            <p class="text-sm text-gray-600 mt-1">Update ticket information and details</p>
        </div>
        <a href="{{ $previousUrl }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out">
            <i class="fas fa-arrow-left mr-2"></i>
            Back
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md fade-in" role="alert">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <p>{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md fade-in" role="alert">
        <div class="flex">
            <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
            <div>
                <p class="font-semibold">Please fix the following errors:</p>
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="border-b border-gray-200 pb-4 mb-6">
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-500">
                        <i class="fas fa-ticket-alt"></i>
                    </span>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Ticket #{{ $ticket->ticket_number }}</h2>
                        <p class="text-sm text-gray-500">Created {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="previous_url" value="{{ $previousUrl }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="ticket_number" class="block text-sm font-medium text-gray-700 mb-1">Ticket Number</label>
                            <input type="text" id="ticket_number" value="{{ $ticket->ticket_number }}" class="w-full px-4 py-2 bg-gray-50 text-gray-800 border border-gray-200 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-transparent" readonly>
                        </div>

                        <div>
                            <label for="requester_name" class="block text-sm font-medium text-gray-700 mb-1">Requester Name</label>
                            <input type="text" id="requester_name" name="requester_name" value="{{ old('requester_name', $ticket->requester_name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-transparent @error('requester_name') border-red-500 @enderror" required>
                            @error('requester_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="requester_email" class="block text-sm font-medium text-gray-700 mb-1">Requester Email</label>
                            <input type="email" id="requester_email" name="requester_email" value="{{ old('requester_email', $ticket->requester_email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-transparent @error('requester_email') border-red-500 @enderror" required>
                            @error('requester_email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-transparent @error('status') border-red-500 @enderror" required>
                                <option value="">Select Status</option>
                                <option value="waiting" {{ old('status', $ticket->status) == 'waiting' ? 'selected' : '' }}>Waiting</option>
                                <option value="in_progress" {{ old('status', $ticket->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ old('status', $ticket->status) == 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                            @error('status')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <select id="priority" name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-transparent @error('priority') border-red-500 @enderror" required>
                                <option value="">Select Priority</option>
                                <option value="low" {{ old('priority', $ticket->priority) == 'low' ? 'selected' : '' }}>
                                    <i class="fas fa-arrow-down text-blue-500"></i> Low
                                </option>
                                <option value="medium" {{ old('priority', $ticket->priority) == 'medium' ? 'selected' : '' }}>
                                    <i class="fas fa-arrow-right text-yellow-500"></i> Medium
                                </option>
                                <option value="high" {{ old('priority', $ticket->priority) == 'high' ? 'selected' : '' }}>
                                    <i class="fas fa-arrow-up text-red-500"></i> High
                                </option>
                            </select>
                            @error('priority')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                            <select id="assigned_to" name="assigned_to" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-transparent @error('assigned_to') border-red-500 @enderror">
                                <option value="">Select Staff</option>
                                @foreach($staff as $member)
                                <option value="{{ $member->id }}" {{ old('assigned_to', $ticket->assigned_to) == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Full Width Fields -->
                <div class="space-y-6 pt-6">
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input type="text" id="subject" name="subject" value="{{ old('subject', $ticket->subject ?: 'Request ('.$ticket->ticket_number.')') }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-transparent @error('subject') border-red-500 @enderror" required>
                        @error('subject')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-400 focus:border-transparent @error('description') border-red-500 @enderror" required>{{ old('description', $ticket->description) }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <a href="{{ $previousUrl }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150 ease-in-out">
                        <i class="fas fa-save mr-2"></i>
                        Update Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    select option {
        padding: 8px;
    }
    
    .form-group label {
        margin-bottom: 0.5rem;
    }
    
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
    }
</style>
@endpush
@endsection
