<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Ticket - IT Support</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
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
            background-color: #6366f1;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .btn-submit:hover {
            background-color: #4f46e5;
            transform: translateY(-1px);
        }
        .nav-links {
            text-align: center;
            margin-top: 20px;
        }
        .priority-low {
            background-color: #dcfce7;
            color: #166534;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .priority-medium {
            background-color: #ffedd5;
            color: #9a3412;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .priority-high {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .priority-critical {
            background-color: #fecaca;
            color: #991b1b;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
        }
        .form-select {
            padding: 0.5rem 0.75rem;
            font-size: 0.975rem;
            line-height: 1.5;
            border-radius: 0.375rem;
            border: 1px solid #d1d5db;
            transition: all 0.2s ease;
        }
        .form-select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        .file-upload {
            border: 2px dashed #d1d5db;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .file-upload:hover {
            border-color: #6366f1;
        }
        .file-upload i {
            font-size: 2rem;
            color: #6b7280;
            margin-bottom: 8px;
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
                        <select class="form-select" id="requester_name" name="requester_name" required>
                            <option value="">-- Select Your Name --</option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}" {{ old('requester_name') == $staff->id ? 'selected' : '' }}>
                                    {{ $staff->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="department" class="form-label">Department</label>
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
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
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
                    <div class="file-upload">
                        <label for="attachments" class="w-100">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p class="mb-1">Drag and drop files here or click to browse</p>
                            <p class="small text-muted">(Maximum size: 10MB per file)</p>
                            <input class="d-none" type="file" id="attachments" name="attachments[]" multiple>
                        </label>
                    </div>
                    <div id="fileList" class="mt-2"></div>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-paper-plane me-2"></i> Submit Ticket
                    </button>
                </div>
            </form>
            
            <div class="nav-links mt-4">
                <a href="{{ route('public.check.ticket') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-search me-1"></i> Check Ticket Status
                </a>
            </div>
        </div>
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
                    fileItem.className = 'alert alert-info d-flex justify-content-between align-items-center mt-2 mb-0 py-2';
                    fileItem.innerHTML = `
                        <div>
                            <i class="fas fa-file me-2"></i>
                            <span class="small">${file.name} (${fileSize} MB)</span>
                        </div>
                        <button type="button" class="btn-close" aria-label="Close"></button>
                    `;
                    
                    fileList.appendChild(fileItem);
                    
                    // Add remove functionality
                    const removeBtn = fileItem.querySelector('.btn-close');
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
        });
    </script>
</body>
</html>
