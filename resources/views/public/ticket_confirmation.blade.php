<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Submitted - IT Support</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .floating {
            animation: float 3s ease-in-out infinite;
        }
        .pulse {
            animation: pulse 2s ease-in-out infinite;
        }
        .status-waiting {
            background-color: #f59e0b;
            color: white;
        }
        .status-in_progress {
            background-color: #3b82f6;
            color: white;
        }
        .status-done {
            background-color: #10b981;
            color: white;
        }
        .brand-gradient {
            background: linear-gradient(135deg, #2e4c92 0%, #4a6fdc 100%);
        }
        .hover-scale {
            transition: transform 0.3s ease;
        }
        .hover-scale:hover {
            transform: scale(1.03);
        }
        .ticket-card {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .ticket-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .ticket-number {
            background: linear-gradient(135deg, #2e4c92 0%, #4a6fdc 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 2px 4px rgba(46, 76, 146, 0.2);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-2xl">
            <div class="brand-gradient text-white rounded-t-xl p-6 text-center animate__animated animate__fadeIn">
                <div class="flex justify-center mb-4">
                    <div class="floating">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold mb-2 animate__animated animate__fadeInUp">Ticket Submitted Successfully!</h1>
                <p class="text-blue-100 animate__animated animate__fadeInUp animate__delay-1s">Thank you for contacting IT Support. We have received your ticket and will address it as soon as possible.</p>
            </div>

            <div class="bg-white rounded-b-xl p-6 shadow-lg ticket-card animate__animated animate__fadeIn animate__delay-1s">
                <div class="text-center mb-8">
                    <div class="inline-block px-4 py-2 bg-blue-50 rounded-full mb-4">
                        <span class="font-mono text-2xl font-bold ticket-number">{{ $ticket->ticket_number }}</span>
                    </div>
                    <p class="text-gray-600">Please save this ticket number for future reference. You can use it to check the status of your request.</p>
                </div>

                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-6 flex items-center justify-center">
                        <i class="bi bi-card-checklist mr-2 text-blue-600 text-xl"></i> Ticket Summary
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="flex items-start bg-blue-50 p-4 rounded-lg hover-scale">
                                <i class="bi bi-card-heading mt-1 mr-3 text-blue-600 text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Title</p>
                                    <p class="font-medium">{{ $ticket->title }}</p>
                                </div>
                            </div>
                            <div class="flex items-start bg-blue-50 p-4 rounded-lg hover-scale">
                                <i class="bi bi-exclamation-triangle mt-1 mr-3 text-blue-600 text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Priority</p>
                                    <p class="font-medium">{{ ucfirst($ticket->priority) }}</p>
                                </div>
                            </div>
                            <div class="flex items-start bg-blue-50 p-4 rounded-lg hover-scale">
                                <i class="bi bi-hourglass-split mt-1 mr-3 text-blue-600 text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <span class="px-3 py-1 rounded-full text-sm font-medium status-{{ $ticket->status }} pulse">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start bg-blue-50 p-4 rounded-lg hover-scale">
                                <i class="bi bi-person mt-1 mr-3 text-blue-600 text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Submitted by</p>
                                    <p class="font-medium">{{ $ticket->requester_name }}</p>
                                </div>
                            </div>
                            <div class="flex items-start bg-blue-50 p-4 rounded-lg hover-scale">
                                <i class="bi bi-envelope mt-1 mr-3 text-blue-600 text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-medium">{{ $ticket->requester_email }}</p>
                                </div>
                            </div>
                            <div class="flex items-start bg-blue-50 p-4 rounded-lg hover-scale">
                                <i class="bi bi-calendar mt-1 mr-3 text-blue-600 text-xl"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Submitted on</p>
                                    <p class="font-medium">{{ $ticket->created_at->formatIndonesianShort() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
                    <a href="{{ route('public.check.ticket') }}" class="hover-scale bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium flex items-center justify-center transition-all duration-300 shadow-md hover:shadow-lg">
                        <i class="bi bi-search mr-2"></i> Check Ticket Status
                    </a>
                    <a href="{{ route('public.create.ticket') }}" class="hover-scale bg-white border border-blue-600 text-blue-600 hover:bg-blue-50 px-6 py-3 rounded-lg font-medium flex items-center justify-center transition-all duration-300 shadow-md hover:shadow-lg">
                        <i class="bi bi-plus-circle mr-2"></i> Submit Another Ticket
                    </a>
                </div>
            </div>

            <div class="text-center mt-6 text-gray-500 text-sm animate__animated animate__fadeIn animate__delay-2s">
                <p>If you need immediate assistance, please contact the IT Support team directly.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animations to various elements
            const ticketNumber = document.querySelector('.ticket-number');
            if (ticketNumber) {
                ticketNumber.addEventListener('mouseenter', () => {
                    ticketNumber.style.transform = 'scale(1.05)';
                });
                ticketNumber.addEventListener('mouseleave', () => {
                    ticketNumber.style.transform = 'scale(1)';
                });
            }
        });
    </script>
</body>
</html>
