<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Ticket Status - IT Support</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        
        .ticket-card {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
            transition: all 0.3s ease;
        }
        
        .ticket-card:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .input-focus:focus {
            border-color: #2e4c92;
            box-shadow: 0 0 0 3px rgba(46, 76, 146, 0.2);
        }
        
        .btn-brand {
            background-color: #2e4c92;
            transition: all 0.2s ease;
        }
        
        .btn-brand:hover {
            background-color: #253d7a;
            transform: translateY(-1px);
        }
        
        .btn-brand:active {
            transform: translateY(0);
        }

        /* Animation for error messages */
        @keyframes errorShake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        .error-shake {
            animation: errorShake 0.6s;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="ticket-card bg-white rounded-xl overflow-hidden p-8">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-brand/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-ticket-perforated-fill text-brand text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Check Ticket Status</h1>
                    <p class="text-gray-500 mt-2">Enter your ticket number to check its status</p>
                </div>
                
                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded error-shake">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded error-shake">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <ul class="text-sm text-red-700 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <form action="{{ route('public.ticket.status') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label for="ticket_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Ticket Number <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-hash text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="ticket_number" 
                                name="ticket_number" 
                                class="input-focus block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand" 
                                placeholder="e.g. WG-250415-0123"
                                required
                                value="{{ old('ticket_number') }}"
                            >
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Enter the ticket number you received when submitting your request</p>
                    </div>
                    
                    <button 
                        type="submit" 
                        class="btn-brand w-full text-white font-medium py-3 px-4 rounded-lg flex items-center justify-center"
                    >
                        <i class="bi bi-search mr-2"></i> Check Status
                    </button>
                </form>
                
                <div class="mt-6 text-center">
                    <a href="{{ route('public.create.ticket') }}" class="inline-flex items-center text-sm font-medium text-brand hover:text-brand-dark">
                        <i class="bi bi-plus-circle mr-2"></i> Submit a New Ticket
                    </a>
                </div>
            </div>
            
            <div class="mt-6 text-center text-xs text-gray-400">
                <p>Need help? Contact the IT Support team for assistance</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Focus input field on page load
            document.getElementById('ticket_number').focus();
            
            // Add pulse effect to the button
            const submitButton = document.querySelector('button[type="submit"]');
            
            submitButton.addEventListener('mouseenter', () => {
                submitButton.classList.add('shadow-md');
            });
            
            submitButton.addEventListener('mouseleave', () => {
                submitButton.classList.remove('shadow-md');
            });
            
            // Validate input format on form submission
            document.querySelector('form').addEventListener('submit', function(e) {
                const ticketNumber = document.getElementById('ticket_number').value.trim();
                
                if (!ticketNumber) {
                    e.preventDefault();
                    document.getElementById('ticket_number').classList.add('border-red-500');
                    
                    // Shake the input field
                    document.getElementById('ticket_number').classList.add('error-shake');
                    setTimeout(() => {
                        document.getElementById('ticket_number').classList.remove('error-shake');
                    }, 600);
                }
            });
        });
    </script>
</body>
</html>
