@extends('layouts.admin')

@section('title', 'Tickets')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    .filter-collapse {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .filter-collapse.show {
        max-height: 500px;
    }
    
    .custom-chip {
        display: inline-flex;
        align-items: center;
        background: #e5e7eb;
        border-radius: 9999px;
        padding: 0.25rem 0.75rem;
        margin: 0.25rem;
        font-size: 0.75rem;
        color: #374151;
        transition: all 0.2s;
    }
    
    .custom-chip:hover {
        background: #d1d5db;
    }
    
    .custom-chip .close-icon {
        margin-left: 0.5rem;
        cursor: pointer;
    }
    
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons button {
            width: 100%;
            margin-bottom: 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Daftar Tiket</h1>
                <p class="text-sm text-gray-600 mt-1">Kelola semua tiket support</p>
            </div>
            <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out">
                <i class="fas fa-plus mr-2"></i>
                New Ticket
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
        <p class="font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <!-- Quick Filters -->
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('tickets.index') }}" class="px-4 py-2 rounded-md text-sm font-medium {{ !request('status') ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors duration-150">
            All Tickets
        </a>
        <a href="{{ route('tickets.index', ['status' => 'waiting']) }}" class="px-4 py-2 rounded-md text-sm font-medium {{ request('status') == 'waiting' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors duration-150">
            Waiting
        </a>
        <a href="{{ route('tickets.index', ['status' => 'in_progress']) }}" class="px-4 py-2 rounded-md text-sm font-medium {{ request('status') == 'in_progress' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors duration-150">
            In Progress
        </a>
        <a href="{{ route('tickets.index', ['status' => 'done']) }}" class="px-4 py-2 rounded-md text-sm font-medium {{ request('status') == 'done' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors duration-150">
            Done
        </a>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="relative flex-grow mr-4">
                <form action="{{ route('tickets.index') }}" method="GET" id="search-form">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="priority" value="{{ request('priority') }}">
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                    <input type="hidden" name="assigned_to" value="{{ request('assigned_to') }}">
                    <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                    <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                    <input type="hidden" name="sort_by" value="{{ request('sort_by', 'created_at') }}">
                    <input type="hidden" name="sort_order" value="{{ request('sort_order', 'desc') }}">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            id="search"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" 
                            placeholder="Search ticket number, title, requester name, or email..."
                            value="{{ request('search') }}"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        @if(request('search'))
                        <button type="button" id="clear-search" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-times-circle text-gray-400 hover:text-gray-600"></i>
                        </button>
                        @endif
                    </div>
                </form>
            </div>
            <button id="toggle-filters" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out inline-flex items-center">
                <i class="fas fa-filter mr-2"></i>
                Advanced Filters
                <i class="fas fa-chevron-down ml-2 text-xs transition-transform duration-200" id="filter-arrow"></i>
            </button>
        </div>
        
        <!-- Active Filters -->
        @if(request()->anyFilled(['status', 'priority', 'category_id', 'assigned_to', 'date_from', 'date_to']))
        <div class="active-filters mb-4">
            <div class="text-sm text-gray-600 mb-2">Active filters:</div>
            <div class="flex flex-wrap">
                @if(request('status'))
                <div class="custom-chip">
                    Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                    <span class="close-icon" data-filter="status"><i class="fas fa-times-circle"></i></span>
                </div>
                @endif
                
                @if(request('priority'))
                <div class="custom-chip">
                    Priority: {{ ucfirst(request('priority')) }}
                    <span class="close-icon" data-filter="priority"><i class="fas fa-times-circle"></i></span>
                </div>
                @endif
                
                @if(request('category_id'))
                <div class="custom-chip">
                    Category: {{ $categories->firstWhere('id', request('category_id'))->name ?? 'Unknown' }}
                    <span class="close-icon" data-filter="category_id"><i class="fas fa-times-circle"></i></span>
                </div>
                @endif
                
                @if(request('assigned_to'))
                <div class="custom-chip">
                    Assigned To: {{ $staff->firstWhere('id', request('assigned_to'))->name ?? 'Unknown' }}
                    <span class="close-icon" data-filter="assigned_to"><i class="fas fa-times-circle"></i></span>
                </div>
                @endif
                
                @if(request('date_from') || request('date_to'))
                <div class="custom-chip">
                    Date: {{ request('date_from') ?: 'Any' }} to {{ request('date_to') ?: 'Any' }}
                    <span class="close-icon" data-filter="date"><i class="fas fa-times-circle"></i></span>
                </div>
                @endif
                
                <button id="clear-all-filters" class="text-sm text-indigo-600 hover:text-indigo-800 ml-2">
                    Clear all filters
                </button>
            </div>
        </div>
        @endif
        
        <!-- Advanced Filters Section -->
        <div class="filter-collapse" id="advanced-filters">
            <div class="py-4 border-t border-gray-200">
                <form action="{{ route('tickets.index') }}" method="GET" id="filter-form">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label for="status-select" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status-select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Status</option>
                                <option value="waiting" {{ request('status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                        </div>

                        <div>
                            <label for="priority-select" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                            <select name="priority" id="priority-select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Priorities</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ request('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                        </div>

                        <div>
                            <label for="category-select" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category_id" id="category-select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="staff-select" class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                            <select name="assigned_to" id="staff-select" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">All Staff</option>
                                <option value="unassigned" {{ request('assigned_to') == 'unassigned' ? 'selected' : '' }}>Unassigned</option>
                                @foreach($staff as $member)
                                <option value="{{ $member->id }}" {{ request('assigned_to') == $member->id ? 'selected' : '' }}>
                                    {{ $member->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="date-from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                            <input type="text" name="date_from" id="date-from" class="datepicker w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="YYYY-MM-DD" value="{{ request('date_from') }}">
                        </div>
                        
                        <div>
                            <label for="date-to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                            <input type="text" name="date_to" id="date-to" class="datepicker w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="YYYY-MM-DD" value="{{ request('date_to') }}">
                        </div>
                        
                        <div>
                            <label for="sort-by" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                            <select name="sort_by" id="sort-by" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                                <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>Last Updated</option>
                                <option value="priority" {{ request('sort_by') == 'priority' ? 'selected' : '' }}>Priority</option>
                                <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="sort-order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                            <select name="sort_order" id="sort-order" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-end action-buttons">
                        <button type="button" id="reset-filters" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out mr-2">
                            Reset Filters
                        </button>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($tickets->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority/Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned To</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $ticket->ticket_number }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $ticket->title }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($ticket->description, 50) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $ticket->requester_name }}</div>
                            <div class="text-sm text-gray-500">{{ $ticket->requester_email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $ticket->category ? $ticket->category->color : 'gray-100' }} text-{{ $ticket->category ? 'white' : 'gray-800' }}">
                                {{ $ticket->category ? $ticket->category->name : 'No Category' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $ticket->priority == 'high' ? 'bg-red-100 text-red-800' : 
                                    ($ticket->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 
                                    ($ticket->priority == 'critical' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $ticket->status == 'waiting' ? 'bg-gray-100 text-gray-800' :
                                    ($ticket->status == 'in_progress' ? 'bg-yellow-100 text-yellow-800' :
                                    'bg-green-100 text-green-800') }}">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $ticket->staff ? $ticket->staff->name : 'Unassigned' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('tickets.show', $ticket->id) }}" class="text-indigo-600 hover:text-indigo-900" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('tickets.edit', $ticket->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" onclick="showDeleteModal('{{ $ticket->ticket_number }}', '{{ $ticket->id }}')" class="text-red-600 hover:text-red-900" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-6 text-center">
            <div class="text-gray-500 mb-4">
                <i class="fas fa-ticket-alt text-5xl mb-4"></i>
                <p class="text-xl font-medium mb-2">No tickets found</p>
                <p class="text-gray-400">Try adjusting your search or filter criteria</p>
            </div>
            <a href="{{ route('tickets.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out mt-4">
                <i class="fas fa-plus mr-2"></i> Create New Ticket
            </a>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $tickets->appends(request()->query())->links() }}
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-sm w-full mx-4">
        <div class="text-center">
            <i class="fas fa-exclamation-triangle text-5xl text-red-500 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus tiket <span id="ticketNumberToDelete" class="font-semibold"></span>?</p>
            
            <div class="flex justify-center space-x-4">
                <button onclick="hideDeleteModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition-colors duration-150 ease-in-out">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors duration-150 ease-in-out">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize date pickers
    const datePickerConfig = {
        dateFormat: "Y-m-d",
        allowInput: true,
        altInput: true,
        altFormat: "F j, Y",
        disableMobile: true
    };
    
    flatpickr("#date-from", datePickerConfig);
    flatpickr("#date-to", datePickerConfig);
    
    // Toggle advanced filters
    const toggleFiltersBtn = document.getElementById('toggle-filters');
    const advancedFilters = document.getElementById('advanced-filters');
    const filterArrow = document.getElementById('filter-arrow');
    
    toggleFiltersBtn.addEventListener('click', function() {
        advancedFilters.classList.toggle('show');
        filterArrow.classList.toggle('transform');
        filterArrow.classList.toggle('rotate-180');
    });
    
    // Show advanced filters if any are active
    if (document.querySelector('.active-filters')) {
        advancedFilters.classList.add('show');
        filterArrow.classList.add('transform', 'rotate-180');
    }
    
    // Clear individual filter
    const closeIcons = document.querySelectorAll('.close-icon');
    closeIcons.forEach(icon => {
        icon.addEventListener('click', function() {
            const filter = this.dataset.filter;
            
            if (filter === 'date') {
                document.querySelector('input[name="date_from"]').value = '';
                document.querySelector('input[name="date_to"]').value = '';
            } else {
                document.querySelector(`input[name="${filter}"]`).value = '';
            }
            
            document.getElementById('filter-form').submit();
        });
    });
    
    // Clear all filters
    const clearAllBtn = document.getElementById('clear-all-filters');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function() {
            const searchValue = document.querySelector('input[name="search"]').value;
            window.location.href = "{{ route('tickets.index') }}?search=" + searchValue;
        });
    }
    
    // Clear search
    const clearSearchBtn = document.getElementById('clear-search');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            document.querySelector('input[name="search"]').value = '';
            document.getElementById('search-form').submit();
        });
    }
    
    // Reset filters
    document.getElementById('reset-filters').addEventListener('click', function() {
        const inputs = document.querySelectorAll('#filter-form select, #filter-form input');
        inputs.forEach(input => {
            input.value = '';
        });
        
        // Preserve search
        const searchValue = document.querySelector('input[name="search"]').value;
        document.querySelector('#filter-form input[name="search"]').value = searchValue;
    });
    
    // Add hover effect to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.classList.add('bg-gray-50');
        });
        
        row.addEventListener('mouseleave', function() {
            this.classList.remove('bg-gray-50');
        });
    });
});

function showDeleteModal(ticketNumber, ticketId) {
    document.getElementById('ticketNumberToDelete').textContent = ticketNumber;
    document.getElementById('deleteForm').action = `{{ url('tickets') }}/${ticketId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function hideDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideDeleteModal();
    }
});

// Close modal when pressing Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
        hideDeleteModal();
    }
});
</script>
@endsection
