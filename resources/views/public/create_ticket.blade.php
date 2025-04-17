<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Ticket - IT Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --secondary-color: #f9fafb;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }
        
        body {
            background-color: #eef2ff;
            font-family: 'Inter', sans-serif;
            color: var(--gray-700);
            line-height: 1.6;
            min-height: 100vh;
            position: relative;
            padding-bottom: 60px;
        }
        
        .ticket-form-container {
            max-width: 850px;
            margin: 40px auto;
            background: white;
            padding: 0;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .header {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: white;
            position: relative;
        }
        
        .header h1 {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 2;
        }
        
        .header p {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            position: relative;
            z-index: 2;
        }
        
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
            opacity: 0.5;
            z-index: 1;
        }
        
        .form-content {
            padding: 2.5rem;
        }
        
        .form-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            color: var(--primary-color);
            margin-right: 0.5rem;
            font-size: 1rem;
        }
        
        .form-control, .form-select {
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            line-height: 1.5;
            border-radius: 0.5rem;
            border: 1px solid var(--gray-300);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
        }
        
        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            letter-spacing: 0.025em;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2), 0 2px 4px -1px rgba(59, 130, 246, 0.1);
        }
        
        .btn-submit:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3), 0 4px 6px -2px rgba(59, 130, 246, 0.2);
        }
        
        .btn-submit:active {
            transform: translateY(0px);
            box-shadow: 0 5px 10px -3px rgba(59, 130, 246, 0.3);
        }
        
        .nav-links {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-200);
        }
        
        .priority-low {
            background-color: #dcfce7;
            color: #166534;
            border-color: #d1fae5;
        }
        
        .priority-medium {
            background-color: #ffedd5;
            color: #9a3412;
            border-color: #fed7aa;
        }
        
        .priority-high {
            background-color: #fee2e2;
            color: #b91c1c;
            border-color: #fecaca;
        }
        
        .priority-critical {
            background-color: #fecaca;
            color: #991b1b;
            border-color: #fca5a5;
            font-weight: 600;
        }
        
        .file-upload {
            border: 2px dashed var(--gray-300);
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            background-color: var(--gray-50);
        }
        
        .file-upload:hover {
            border-color: var(--primary-color);
            background-color: rgba(59, 130, 246, 0.05);
        }
        
        .file-upload i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            opacity: 0.8;
        }
        
        .file-upload p {
            margin-bottom: 0.5rem;
            color: var(--gray-600);
        }
        
        .alert {
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .alert-danger {
            background-color: #fef2f2;
            color: #b91c1c;
            border-left: 4px solid #ef4444;
        }
        
        .form-text {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-top: 0.5rem;
        }
        
        .btn-outline-secondary {
            color: var(--gray-700);
            background-color: white;
            border: 1px solid var(--gray-300);
            padding: 0.625rem 1.25rem;
            transition: all 0.2s ease;
            border-radius: 0.5rem;
            font-weight: 500;
            letter-spacing: 0.025em;
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--gray-100);
            color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background-color: #eff6ff;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            border-left: 3px solid #3b82f6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .file-item i {
            color: #3b82f6;
            margin-right: 0.5rem;
        }
        
        .file-item button {
            background: none;
            border: none;
            color: var(--gray-500);
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 0.25rem;
            transition: all 0.2s ease;
        }
        
        .file-item button:hover {
            color: var(--danger-color);
            background-color: rgba(239, 68, 68, 0.1);
        }
        
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .form-group {
            margin-bottom: 1.75rem;
        }
        
        .form-group:last-of-type {
            margin-bottom: 0;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 1rem;
            color: var(--gray-500);
        }
        
        .input-with-icon input,
        .input-with-icon select {
            padding-left: 2.5rem;
        }
        
        .form-footer {
            background-color: var(--gray-50);
            padding: 1.5rem 2.5rem;
            border-top: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .ticket-form-container {
                margin: 20px auto;
            }
            
            .header {
                padding: 1.5rem;
            }
            
            .header h1 {
                font-size: 1.5rem;
            }
            
            .form-content {
                padding: 1.5rem;
            }
            
            .form-footer {
                padding: 1rem 1.5rem;
                flex-direction: column;
                gap: 1rem;
            }
            
            body {
                padding-bottom: 30px;
            }
        }
        
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 1rem;
            color: var(--gray-500);
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="ticket-form-container">
            <div class="header">
                <h1><i class="fas fa-ticket-alt me-2"></i>IT Support Ticket</h1>
                <p>Submit a new support request</p>
            </div>
            
            <div class="form-content">
                @if(session('success'))
                    <div class="alert alert-success">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-check-circle fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">Success!</h5>
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-circle fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold">Error!</h5>
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <form action="{{ route('public.store.ticket') }}" method="POST" enctype="multipart/form-data" id="ticketForm">
                    @csrf
                    <input type="hidden" name="form_token" value="{{ uniqid() }}">
                    
                    <div class="section-title"><i class="fas fa-user-circle text-primary me-2"></i>Requester Information</div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="form-group">
                                <label for="requester_name" class="form-label">
                                    <i class="fas fa-user"></i> Your Name <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="requester_name" name="requester_name" required>
                                    <option value="">-- Select Your Name --</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->id }}" {{ old('requester_name') == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department" class="form-label">
                                    <i class="fas fa-building"></i> Department
                                </label>
                                <select class="form-select" id="department" name="department">
                                    <option value="">-- Select Department --</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->name }}" {{ old('department') == $department->name ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="section-title"><i class="fas fa-clipboard-list text-primary me-2"></i>Ticket Details</div>
                    
                    <div class="form-group">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading"></i> Issue Title <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Briefly describe your issue" required>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="form-group">
                                <label for="category_id" class="form-label">
                                    <i class="fas fa-tag"></i> Category
                                </label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="priority" class="form-label">
                                    <i class="fas fa-flag"></i> Priority <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left"></i> Description <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" id="description" name="description" rows="6" placeholder="Please describe your issue in detail..." required>{{ old('description') }}</textarea>
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i> Please provide as much detail as possible about your issue
                        </div>
                    </div>
                    
                    <div class="section-title"><i class="fas fa-paperclip text-primary me-2"></i>Attachments</div>
                    
                    <div class="form-group">
                        <div class="file-upload">
                            <label for="attachments" class="w-100">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p class="mb-1 fw-medium">Drag and drop files here or click to browse</p>
                                <p class="small text-muted">(Maximum size: 10MB per file)</p>
                                <input class="d-none" type="file" id="attachments" name="attachments[]" multiple>
                            </label>
                        </div>
                        <div id="fileList" class="mt-3"></div>
                    </div>
                </form>
            </div>
            
            <div class="form-footer">
                <a href="{{ route('public.check.ticket') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-search me-1"></i> Check Ticket Status
                </a>
                
                <button type="submit" form="ticketForm" class="btn btn-submit" id="submitBtn">
                    <i class="fas fa-paper-plane me-2"></i> Submit Ticket
                </button>
            </div>
        </div>
    </div>
    
    <div class="footer">
        &copy; {{ date('Y') }} WGTicket Support System. All rights reserved.
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // File upload handling
            const fileInput = document.getElementById('attachments');
            const fileList = document.getElementById('fileList');
            
            fileInput.addEventListener('change', function() {
                fileList.innerHTML = '';
                
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
                    
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';
                    fileItem.innerHTML = `
                        <div>
                            <i class="fas fa-file"></i>
                            <span class="small">${file.name} (${fileSize} MB)</span>
                        </div>
                        <button type="button" class="remove-file">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    fileList.appendChild(fileItem);
                    
                    // Add remove functionality
                    const removeBtn = fileItem.querySelector('.remove-file');
                    removeBtn.addEventListener('click', function() {
                        fileItem.remove();
                    });
                }
            });
            
            // Auto-populate department when staff is selected
            const staffSelect = document.getElementById('requester_name');
            const departmentSelect = document.getElementById('department');
            
            // Create a mapping of staff IDs to departments
            const staffDepartments = {
                @foreach($staffMembers as $staff)
                    {{ $staff->id }}: "{{ $staff->department }}",
                @endforeach
            };
            
            staffSelect.addEventListener('change', function() {
                const selectedStaffId = this.value;
                if (selectedStaffId && staffDepartments[selectedStaffId]) {
                    // Find the department option that matches the staff's department
                    const departmentOptions = departmentSelect.options;
                    for (let i = 0; i < departmentOptions.length; i++) {
                        if (departmentOptions[i].value === staffDepartments[selectedStaffId]) {
                            departmentSelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            });
            
            // Add color indicators to priority options
            const prioritySelect = document.getElementById('priority');
            prioritySelect.addEventListener('change', updatePriorityClass);
            
            function updatePriorityClass() {
                const selectedOption = prioritySelect.options[prioritySelect.selectedIndex];
                
                // Remove all classes
                prioritySelect.classList.remove('priority-low', 'priority-medium', 'priority-high', 'priority-critical');
                
                // Add the appropriate class
                if (selectedOption.value === 'low') {
                    prioritySelect.classList.add('priority-low');
                } else if (selectedOption.value === 'medium') {
                    prioritySelect.classList.add('priority-medium');
                } else if (selectedOption.value === 'high') {
                    prioritySelect.classList.add('priority-high');
                } else if (selectedOption.value === 'critical') {
                    prioritySelect.classList.add('priority-critical');
                }
            }
            
            // Initialize on load
            updatePriorityClass();
            
            // Form submission protection
            const form = document.getElementById('ticketForm');
            const submitBtn = document.getElementById('submitBtn');
            
            form.addEventListener('submit', function(e) {
                // Prevent multiple form submissions
                if (form.getAttribute('data-submitting') === 'true') {
                    e.preventDefault();
                    return false;
                }
                
                // Mark the form as being submitted
                form.setAttribute('data-submitting', 'true');
                
                // Disable the submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';
                
                // Store form submission in localStorage to check for duplicate submissions
                const formToken = form.querySelector('input[name="form_token"]').value;
                
                // Check if this form was already submitted
                if (localStorage.getItem('submitted_form_' + formToken)) {
                    e.preventDefault();
                    alert('Your ticket is already being processed. Please wait.');
                    return false;
                }
                
                // Mark this form as submitted in localStorage
                localStorage.setItem('submitted_form_' + formToken, 'true');
                
                // Allow the form to submit
                return true;
            });
            
            // Clear form submission status when page is loaded (for back button case)
            window.addEventListener('pageshow', function(event) {
                // If the page is loaded from cache (back button)
                if (event.persisted) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Submit Ticket';
                    form.removeAttribute('data-submitting');
                }
            });
        });
    </script>
</body>
</html>
