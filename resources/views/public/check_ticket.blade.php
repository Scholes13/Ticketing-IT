<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Ticket Status - IT Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .ticket-form-container {
            max-width: 600px;
            margin: 50px auto;
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
        .form-label {
            font-weight: 500;
        }
        .btn-check {
            background-color: #0d6efd;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
        }
        .btn-check:hover {
            background-color: #0b5ed7;
        }
        .nav-links {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="ticket-form-container">
            <div class="header">
                <h1>Check Ticket Status</h1>
                <p class="text-muted">Enter your ticket number to check its status</p>
            </div>
            
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form action="{{ route('public.ticket.status') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="ticket_number" class="form-label">Ticket Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg" id="ticket_number" name="ticket_number" value="{{ old('ticket_number') }}" placeholder="e.g. TKT-20250415-ABCDE" required>
                    <div class="form-text">Enter the ticket number you received when you submitted your ticket</div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-check">Check Status</button>
                </div>
            </form>
            
            <div class="nav-links mt-4">
                <a href="{{ route('public.create.ticket') }}" class="btn btn-outline-secondary">Submit a New Ticket</a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
