@extends('layouts.admin')

@section('title', 'View Ticket')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Detail Tiket</h1>
                <p class="text-sm text-gray-600 mt-1">{{ $ticket->ticket_number }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('tickets.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to List
                </a>
                <a href="{{ route('tickets.edit', $ticket->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out">
                    <i class="fas fa-edit mr-2"></i>
                    Edit
                </a>
                <button type="button" onclick="showDeleteModal('{{ $ticket->ticket_number }}', '{{ $ticket->id }}')" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors duration-150 ease-in-out">
                    <i class="fas fa-trash mr-2"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm">{{ session('success') }}</p>
            </div>
            <button class="ml-auto -mx-1.5 -my-1.5 bg-green-100 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8" onclick="this.parentElement.parentElement.style.display = 'none'">
                <span class="sr-only">Close</span>
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Ticket Status Bar -->
    <div class="bg-white rounded-xl p-4 mb-6 shadow-sm border border-gray-200">
        <div class="flex flex-wrap justify-between items-center">
            <div class="flex items-center mb-2 md:mb-0">
                <h2 class="text-xl font-semibold text-gray-800 mr-3">{{ $ticket->title }}</h2>
                
                @if($ticket->status == 'waiting')
                <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-medium">Menunggu</span>
                @elseif($ticket->status == 'in_progress')
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Dalam Proses</span>
                @elseif($ticket->status == 'done')
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Selesai</span>
                @endif
            </div>
            <div class="flex space-x-2">
                <form action="{{ route('tickets.change-status', $ticket->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="waiting">
                    <button type="submit" class="px-3 py-1 text-xs font-medium rounded-md {{ $ticket->status == 'waiting' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                        <i class="fas fa-clock mr-1"></i> Waiting
                    </button>
                </form>
                <form action="{{ route('tickets.change-status', $ticket->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" class="px-3 py-1 text-xs font-medium rounded-md {{ $ticket->status == 'in_progress' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                        <i class="fas fa-spinner mr-1"></i> In Progress
                    </button>
                </form>
                <form action="{{ route('tickets.change-status', $ticket->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="done">
                    <button type="submit" class="px-3 py-1 text-xs font-medium rounded-md {{ $ticket->status == 'done' ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                        <i class="fas fa-check mr-1"></i> Done
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Ticket Info Column -->
        <div class="md:col-span-1 space-y-6">
            <!-- Requester Information -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-700"><i class="fas fa-user text-blue-500 mr-2"></i>Informasi Pemohon</h3>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        <div class="flex items-center text-sm">
                            <span class="w-24 text-gray-500">Nama</span>
                            <span class="font-medium text-gray-800">{{ $ticket->requester_name }}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="w-24 text-gray-500">Email</span>
                            <span class="font-medium text-gray-800">{{ $ticket->requester_email }}</span>
                        </div>
                        @if($ticket->requester_phone)
                        <div class="flex items-center text-sm">
                            <span class="w-24 text-gray-500">Telepon</span>
                            <span class="font-medium text-gray-800">{{ $ticket->requester_phone }}</span>
                        </div>
                        @endif
                        @if($ticket->department)
                        <div class="flex items-center text-sm">
                            <span class="w-24 text-gray-500">Departemen</span>
                            <span class="font-medium text-gray-800">{{ $ticket->department }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Ticket Details -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-700"><i class="fas fa-info-circle text-blue-500 mr-2"></i>Detail Tiket</h3>
                </div>
                <div class="p-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Prioritas</span>
                            @if($ticket->priority == 'low')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">Rendah</span>
                            @elseif($ticket->priority == 'medium')
                            <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs font-medium">Sedang</span>
                            @elseif($ticket->priority == 'high')
                            <span class="px-2 py-1 bg-orange-100 text-orange-800 rounded text-xs font-medium">Tinggi</span>
                            @elseif($ticket->priority == 'critical')
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">Kritis</span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Kategori</span>
                            <span class="font-medium text-gray-800">{{ $ticket->category ? $ticket->category->name : '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Staf</span>
                            <span class="font-medium text-gray-800">{{ $ticket->staff ? $ticket->staff->name : 'Belum ditugaskan' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-500">Dibuat</span>
                            <span class="font-medium text-gray-800">{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                    
                    @if($ticket->staff)
                    <div class="mt-4 text-right">
                        <span class="text-sm text-gray-500">Ditugaskan kepada:</span>
                        <div class="mt-1 inline-flex items-center px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">
                            <i class="fas fa-user-tie mr-2"></i>
                            {{ $ticket->staff->name }}
                        </div>
                    </div>
                    @else
                    <div class="mt-4">
                        <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="flex space-x-2">
                                <select name="assigned_to" class="flex-grow text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">-- Pilih Staff --</option>
                                    @foreach($staff as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md">
                                    <i class="fas fa-user-plus mr-1"></i> Assign
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Attachments Section -->
            @if(count($ticket->attachments) > 0)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-700"><i class="fas fa-paperclip text-blue-500 mr-2"></i>Attachments</h3>
                </div>
                <div class="p-4">
                    <div class="space-y-2">
                        @foreach($ticket->attachments as $attachment)
                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-md group transition-colors">
                            <div class="flex items-center">
                                <div class="bg-blue-100 text-blue-600 p-2 rounded mr-3">
                                    <i class="fas fa-file"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-blue-500 transition-colors">{{ $attachment->original_filename }}</span>
                            </div>
                            <span class="text-xs text-gray-500">{{ number_format($attachment->file_size / 1024, 2) }} KB</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content Column -->
        <div class="md:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-700"><i class="fas fa-align-left text-blue-500 mr-2"></i>Deskripsi</h3>
                </div>
                <div class="p-6">
                    <div class="bg-gray-50 p-4 rounded-md text-gray-700 whitespace-pre-line">
                        {{ $ticket->description }}
                    </div>
                </div>
            </div>
            
            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-700"><i class="fas fa-history text-blue-500 mr-2"></i>Timeline</h3>
                </div>
                <div class="p-4">
                    <div class="relative border-l-2 border-gray-200 ml-3 pl-8 pb-1">
                        <!-- Ticket Created -->
                        <div class="mb-6 relative">
                            <div class="absolute -left-10 mt-1.5 w-5 h-5 rounded-full bg-blue-500 border-4 border-white flex items-center justify-center">
                                <i class="fas fa-plus text-xs text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-800">Tiket Dibuat</h4>
                                <p class="text-xs text-gray-500">{{ $ticket->created_at->format('d M Y, H:i') }}</p>
                                <p class="mt-1 text-sm text-gray-600">Tiket dibuat oleh {{ $ticket->requester_name }}</p>
                            </div>
                        </div>
                        
                        <!-- Processing Started -->
                        @if($ticket->follow_up_at)
                        <div class="mb-6 relative">
                            <div class="absolute -left-10 mt-1.5 w-5 h-5 rounded-full bg-amber-500 border-4 border-white flex items-center justify-center">
                                <i class="fas fa-spinner text-xs text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-800">Mulai Diproses</h4>
                                <p class="text-xs text-gray-500">{{ $ticket->follow_up_at->format('d M Y, H:i') }}</p>
                                <p class="mt-1 text-sm text-gray-600">Status berubah menjadi Dalam Proses</p>
                                @if($ticket->follow_up_at->diffInMinutes($ticket->created_at) < 60)
                                <p class="text-xs text-blue-500">{{ $ticket->follow_up_at->diffInMinutes($ticket->created_at) }} menit setelah dibuat</p>
                                @else
                                <p class="text-xs text-blue-500">{{ $ticket->follow_up_at->diffInHours($ticket->created_at) }} jam setelah dibuat</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <!-- Ticket Resolved -->
                        @if($ticket->resolved_at)
                        <div class="relative">
                            <div class="absolute -left-10 mt-1.5 w-5 h-5 rounded-full bg-green-500 border-4 border-white flex items-center justify-center">
                                <i class="fas fa-check text-xs text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-800">Tiket Diselesaikan</h4>
                                <p class="text-xs text-gray-500">{{ $ticket->resolved_at->format('d M Y, H:i') }}</p>
                                <p class="mt-1 text-sm text-gray-600">Status berubah menjadi Selesai</p>
                                @if($ticket->follow_up_at)
                                    @if($ticket->resolved_at->diffInMinutes($ticket->follow_up_at) < 60)
                                    <p class="text-xs text-green-500">{{ $ticket->resolved_at->diffInMinutes($ticket->follow_up_at) }} menit proses</p>
                                    @else
                                    <p class="text-xs text-green-500">{{ $ticket->resolved_at->diffInHours($ticket->follow_up_at) }} jam proses</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Comments Section -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100">
                    <h3 class="text-sm font-medium text-gray-700"><i class="fas fa-comments text-blue-500 mr-2"></i>Komentar</h3>
                </div>
                <div class="p-4">
                    @if(count($ticket->comments) > 0)
                    <div class="space-y-4 mb-6">
                        @foreach($ticket->comments as $comment)
                        <div class="p-4 rounded-lg {{ $comment->is_private ? 'bg-amber-50 border border-amber-100' : 'bg-blue-50 border border-blue-100' }}">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-2">
                                        <i class="fas fa-user text-gray-500"></i>
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-800 text-sm">{{ $comment->user ? $comment->user->name : 'System' }}</div>
                                        <div class="text-xs text-gray-500">{{ $comment->created_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>
                                @if($comment->is_private)
                                <span class="px-2 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-medium">
                                    <i class="fas fa-eye-slash mr-1"></i> Private
                                </span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-700 mt-2 whitespace-pre-line">{{ $comment->content }}</div>
                            @if($comment->attachment_path)
                            <div class="mt-3">
                                <a href="{{ Storage::url($comment->attachment_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-white border border-gray-300 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-50">
                                    <i class="fas fa-paperclip mr-1"></i> Lihat Lampiran
                                </a>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-6">
                        <div class="text-gray-400 mb-2"><i class="fas fa-comments text-3xl"></i></div>
                        <p class="text-gray-500 text-sm">Belum ada komentar</p>
                    </div>
                    @endif
                    
                    <!-- Add Comment Form -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Tambahkan Komentar</h4>
                        <form action="{{ route('tickets.comment', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <textarea name="content" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 text-sm" placeholder="Tulis komentar Anda di sini..." required></textarea>
                            </div>
                            <div class="flex flex-wrap justify-between items-center">
                                <div class="flex items-center">
                                    <input type="checkbox" id="is_private" name="is_private" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <label for="is_private" class="ml-2 text-sm text-gray-700">Komentar private (tidak terlihat oleh pemohon)</label>
                                </div>
                                <div class="mt-3 sm:mt-0 flex items-center space-x-3">
                                    <div class="relative">
                                        <input type="file" id="attachment" name="attachment" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                        <label for="attachment" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                            <i class="fas fa-paperclip mr-2"></i> Lampirkan Berkas
                                        </label>
                                    </div>
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-blue-700">
                                        <i class="fas fa-paper-plane mr-2"></i> Kirim
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
                <button type="button" onclick="hideDeleteModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Batal
                </button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showDeleteModal(ticketNumber, ticketId) {
        document.getElementById('ticketNumberToDelete').textContent = ticketNumber;
        document.getElementById('deleteForm').action = `/tickets/${ticketId}`;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }
    
    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.remove('flex');
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            hideDeleteModal();
        }
    });
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
            hideDeleteModal();
        }
    });
    
    // File input display
    const fileInput = document.getElementById('attachment');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const fileName = this.files[0]?.name;
            if (fileName) {
                const label = this.nextElementSibling;
                label.innerHTML = `<i class="fas fa-paperclip mr-2"></i> ${fileName.length > 20 ? fileName.substring(0, 17) + '...' : fileName}`;
            }
        });
    }
</script>
@endsection
