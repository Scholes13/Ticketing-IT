<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Ticket - IT Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .ticket-form-container {
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
        .form-label {
            font-weight: 500;
        }
        .btn-submit {
            background-color: #0d6efd;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
        }
        .btn-submit:hover {
            background-color: #0b5ed7;
        }
        .nav-links {
            text-align: center;
            margin-top: 20px;
        }
        .priority-high {
            color: #dc3545;
        }
        .priority-medium {
            color: #fd7e14;
        }
        .priority-low {
            color: #198754;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="ticket-form-container">
            <div class="header">
                <h1>IT Support Ticket</h1>
                <p class="text-muted">Submit a new support request</p>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
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
            
            <form action="{{ route('public.store.ticket') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="requester_name" class="form-label">Your Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="requester_name" name="requester_name" value="{{ old('requester_name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="requester_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="requester_email" name="requester_email" value="{{ old('requester_email') }}" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="requester_phone" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="requester_phone" name="requester_phone" value="{{ old('requester_phone') }}">
                    </div>
                    <div class="col-md-6">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" class="form-control" id="department" name="department" value="{{ old('department') }}">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="title" class="form-label">Issue Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                        <select class="form-select" id="priority" name="priority" required>
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }} class="priority-low">Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} class="priority-medium">Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }} class="priority-high">High</option>
                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }} class="priority-high">Critical</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                    <div class="form-text">Please provide as much detail as possible about your issue</div>
                </div>
                
                <div class="mb-3">
                    <label for="attachments" class="form-label">Attachments</label>
                    <input class="form-control" type="file" id="attachments" name="attachments[]" multiple>
                    <div class="form-text">You can upload screenshots or documents related to your issue (max 10MB per file)</div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary btn-submit">Submit Ticket</button>
                </div>
            </form>
            
            <div class="nav-links mt-4">
                <a href="{{ route('public.check.ticket') }}" class="btn btn-outline-secondary">Check Ticket Status</a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
