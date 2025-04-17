<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Status - {{ $ticket->ticket_number }} - IT Support</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#2e4c92',
                            light: '#3a5ca9',
                            dark: '#243d7a'
                        },
                        waiting: {
                            DEFAULT: '#f59e0b',
                            light: '#fbbf24'
                        },
                        in_progress: {
                            DEFAULT: '#3b82f6',
                            light: '#60a5fa'
                        },
                        done: {
                            DEFAULT: '#10b981',
                            light: '#34d399'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        @keyframes timelineDot {
            0% {
                box-shadow: 0 0 0 0 rgba(46, 76, 146, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(46, 76, 146, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(46, 76, 146, 0);
            }
        }
        
        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .animate-pulse {
            animation: pulse 2s infinite;
        }
        
        .animate-timelineDot {
            animation: timelineDot 1.5s infinite;
        }
        
        .timeline-item {
            opacity: 0;
        }
        
        .status-badge {
            transition: all 0.3s ease;
        }
        
        .status-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 76, 146, 0.2);
        }
        
        .attachment-card {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .attachment-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(46, 76, 146, 0.1);
        }
        
        .comment-item {
            transition: all 0.3s ease;
        }
        
        .comment-item:hover {
            transform: translateX(5px);
            border-left-color: #2e4c92;
        }
        
        .ticket-container {
            background: linear-gradient(to bottom right, #ffffff, #f8fafc);
        }
        
        .timeline-badge {
            box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.9);
        }
        
        .gradient-header {
            background: linear-gradient(135deg, #2e4c92 0%, #3a5ca9 100%);
        }
        
        .priority-badge {
            transition: all 0.3s ease;
        }
        
        @media (max-width: 640px) {
            .ticket-container {
                padding: 1rem;
                margin: 0.5rem;
                border-radius: 0.75rem;
            }
            
            .header h1 {
                font-size: 1.25rem;
            }
            
            .timeline-content {
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen py-4 sm:py-8">
    <div class="max-w-4xl mx-auto px-2 sm:px-4">
        <div class="ticket-container bg-white rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
            <!-- Header with brand gradient background -->
            <div class="header gradient-header text-white py-6 px-6 text-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.2\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                <h1 class="text-2xl font-bold mb-2 relative z-10">Ticket Status</h1>
                <div class="ticket-number text-blue-100 font-medium mb-3 relative z-10">{{ $ticket->ticket_number }}</div>
                <span class="status-badge inline-block px-4 py-2 rounded-full font-semibold shadow-md relative z-10
                    {{ $ticket->status === 'waiting' ? 'bg-waiting text-white' : '' }}
                    {{ $ticket->status === 'in_progress' ? 'bg-in_progress text-white' : '' }}
                    {{ $ticket->status === 'done' ? 'bg-done text-white' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                </span>
            </div>
            
            <div class="p-4 sm:p-6">
                <!-- Ticket Title and Meta -->
                <div class="mb-6 animate-fadeInUp" style="animation-delay: 0.1s">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $ticket->title }}</h2>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="priority-badge px-3 py-1 rounded-full text-xs font-semibold shadow-sm hover:shadow-md
                            {{ $ticket->priority === 'low' ? 'bg-green-500 text-white' : '' }}
                            {{ $ticket->priority === 'medium' ? 'bg-orange-500 text-white' : '' }}
                            {{ $ticket->priority === 'high' ? 'bg-red-500 text-white' : '' }}
                            {{ $ticket->priority === 'critical' ? 'bg-purple-600 text-white' : '' }}">
                            {{ ucfirst($ticket->priority) }} Priority
                        </span>
                        
                        @if($ticket->category)
                            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $ticket->category->name }}
                            </span>
                        @endif
                        
                        <span class="text-gray-500 text-sm flex items-center">
                            <i class="bi bi-clock mr-1"></i> Dikirim pada {{ $ticket->created_at->formatIndonesianShort() }}
                        </span>
                    </div>
                </div>
                
                <!-- Ticket Details -->
                <div class="bg-blue-50 rounded-lg p-4 sm:p-5 mb-6 animate-fadeInUp" style="animation-delay: 0.2s">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="bi bi-person-circle mr-2 text-brand"></i> Requester Details
                            </h3>
                            <div class="space-y-1 text-gray-600">
                                <p><span class="font-medium">Name:</span> {{ $ticket->requester_name }}</p>
                                <p><span class="font-medium">Email:</span> {{ $ticket->requester_email }}</p>
                                @if($ticket->requester_phone)
                                    <p><span class="font-medium">Phone:</span> {{ $ticket->requester_phone }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="font-semibold text-gray-700 mb-2 flex items-center">
                                <i class="bi bi-info-circle mr-2 text-brand"></i> Ticket Info
                            </h3>
                            <div class="space-y-1 text-gray-600">
                                @if($ticket->department)
                                    <p><span class="font-medium">Department:</span> {{ $ticket->department }}</p>
                                @endif
                                <p><span class="font-medium">Status:</span> {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</p>
                                <p><span class="font-medium">Assigned To:</span> 
                                    {{ $ticket->staff ? $ticket->staff->name : 'Not assigned yet' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-700 mb-2 flex items-center">
                            <i class="bi bi-card-text mr-2 text-brand"></i> Description
                        </h3>
                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                            <p class="text-gray-700 whitespace-pre-line">{{ $ticket->description }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Animated Timeline -->
                <div class="mb-8 animate-fadeInUp" style="animation-delay: 0.3s">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-clock-history mr-2 text-brand"></i> Ticket Timeline
                    </h3>
                    
                    <div class="relative pl-8">
                        <!-- Vertical line -->
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-blue-200"></div>
                        
                        <!-- Timeline items -->
                        <div class="timeline-item relative mb-6" style="animation-delay: 0.4s">
                            <div class="timeline-badge absolute left-0 top-0 w-8 h-8 rounded-full bg-waiting flex items-center justify-center text-white -ml-4 animate-timelineDot">
                                <i class="bi bi-plus-circle"></i>
                            </div>
                            <div class="timeline-content bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:border-waiting transition-colors">
                                <div class="text-sm text-waiting mb-1 font-medium">{{ $ticket->created_at->formatIndonesianShort() }}</div>
                                <div class="font-medium">Ticket created</div>
                                <div class="text-xs text-gray-500 mt-1">Ticket was submitted to the system</div>
                            </div>
                        </div>
                        
                        @if($ticket->follow_up_at)
                        <div class="timeline-item relative mb-6" style="animation-delay: 0.6s">
                            <div class="timeline-badge absolute left-0 top-0 w-8 h-8 rounded-full bg-in_progress flex items-center justify-center text-white -ml-4 animate-timelineDot">
                                <i class="bi bi-hourglass-split"></i>
                            </div>
                            <div class="timeline-content bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:border-in_progress transition-colors">
                                <div class="text-sm text-in_progress mb-1 font-medium">{{ $ticket->follow_up_at->formatIndonesianShort() }}</div>
                                <div class="font-medium">Ticket processing started</div>
                                <div class="text-xs text-gray-500 mt-1">Assigned to {{ $ticket->staff ? $ticket->staff->name : 'support team' }}</div>
                            </div>
                        </div>
                        @endif
                        
                        @if($ticket->resolved_at)
                        <div class="timeline-item relative" style="animation-delay: 0.8s">
                            <div class="timeline-badge absolute left-0 top-0 w-8 h-8 rounded-full bg-done flex items-center justify-center text-white -ml-4 animate-timelineDot">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="timeline-content bg-white p-4 rounded-lg border border-gray-200 shadow-sm hover:border-done transition-colors">
                                <div class="text-sm text-done mb-1 font-medium">{{ $ticket->resolved_at->formatIndonesianShort() }}</div>
                                <div class="font-medium">Ticket resolved</div>
                                <div class="text-xs text-gray-500 mt-1">Issue has been successfully resolved</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Comments Section -->
                @if(count($ticket->comments->where('is_private', false)) > 0)
                <div class="mb-8 animate-fadeInUp" style="animation-delay: 0.9s">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-chat-left-text mr-2 text-brand"></i> Comments
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($ticket->comments->where('is_private', false) as $comment)
                        <div class="comment-item bg-white p-4 rounded-lg border-l-4 border-blue-300 shadow-sm">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center text-sm text-gray-500 mb-1">
                                        <i class="bi bi-person-circle mr-2 text-brand"></i>
                                        <span>{{ $ticket->staff ? $ticket->staff->name : 'IT Support' }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="bi bi-calendar mr-1"></i>
                                        <span>{{ $comment->created_at->formatIndonesianShort() }}</span>
                                    </div>
                                    <div class="text-gray-700">{{ $comment->content }}</div>
                                </div>
                                @if($comment->attachment_path)
                                <a href="{{ Storage::url($comment->attachment_path) }}" target="_blank" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-50 text-brand rounded-full text-sm hover:bg-blue-100 transition-colors">
                                    <i class="bi bi-paperclip mr-1"></i> View
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Attachments Section -->
                @if(count($ticket->attachments) > 0)
                <div class="mb-8 animate-fadeInUp" style="animation-delay: 1s">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="bi bi-paperclip mr-2 text-brand"></i> Attachments
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($ticket->attachments as $attachment)
                        <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" 
                           class="attachment-card bg-white p-3 rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                <i class="bi bi-file-earmark-text text-brand text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-gray-800 truncate">{{ $attachment->original_filename }}</div>
                                <div class="text-xs text-gray-500">{{ number_format($attachment->file_size / 1024, 2) }} KB</div>
                            </div>
                            <i class="bi bi-download text-gray-400 ml-2"></i>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <!-- Navigation Links -->
                <div class="flex flex-col sm:flex-row justify-center gap-3 mt-8 animate-fadeInUp" style="animation-delay: 1.1s">
                    <a href="{{ route('public.check.ticket') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors text-center flex items-center justify-center">
                        <i class="bi bi-search mr-2"></i> Check Another Ticket
                    </a>
                    <a href="{{ route('public.create.ticket') }}" 
                       class="px-6 py-2 bg-brand rounded-lg text-white font-medium hover:bg-brand-dark transition-colors text-center flex items-center justify-center animate-pulse">
                        <i class="bi bi-plus-circle mr-2"></i> Submit New Ticket
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Animate timeline items sequentially with staggered delays
        document.addEventListener('DOMContentLoaded', function() {
            const timelineItems = document.querySelectorAll('.timeline-item');
            
            timelineItems.forEach((item, index) => {
                // Apply animation with increasing delay
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.classList.add('animate-fadeInUp');
                }, 300 * index);
            });
            
            // Add hover effects to priority badge
            const priorityBadge = document.querySelector('.priority-badge');
            if (priorityBadge) {
                priorityBadge.addEventListener('mouseenter', () => {
                    priorityBadge.classList.add('shadow-md', 'scale-105');
                });
                priorityBadge.addEventListener('mouseleave', () => {
                    priorityBadge.classList.remove('shadow-md', 'scale-105');
                });
            }
            
            // Add ripple effect to status badge
            const statusBadge = document.querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.addEventListener('mouseenter', () => {
                    statusBadge.classList.add('shadow-lg');
                });
                statusBadge.addEventListener('mouseleave', () => {
                    statusBadge.classList.remove('shadow-lg');
                });
            }
        });
    </script>
</body>
</html>
