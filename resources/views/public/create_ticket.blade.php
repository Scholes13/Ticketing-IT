<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Ticket - IT Support</title>
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
                        },
                        priority: {
                            low: '#dcfce7',
                            medium: '#ffedd5',
                            high: '#fee2e2',
                            critical: '#fecaca'
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

        /* File Upload Styles */
        .file-upload {
            border: 2px dashed #d1d5db;
            transition: all 0.2s ease;
        }
        
        .file-upload:hover {
            border-color: #2e4c92;
            background-color: rgba(46, 76, 146, 0.05);
        }

        /* Priority Indicators */
        .priority-indicator.low {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .priority-indicator.medium {
            background-color: #ffedd5;
            color: #9a3412;
        }
        
        .priority-indicator.high {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        
        .priority-indicator.critical {
            background-color: #fecaca;
            color: #991b1b;
            font-weight: 600;
        }

        /* Animation for form */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
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
    <div class="min-h-screen py-12 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto">
            <div class="ticket-card bg-white rounded-xl overflow-hidden p-8 animate-fade-in">
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-brand/10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-ticket-perforated-fill text-brand text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">IT Support Ticket</h1>
                    <p class="text-gray-500 mt-2">Submit a new support request</p>
            </div>
            
            @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded animate-fade-in">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-check-circle text-green-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
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
            
                <form action="{{ route('public.store.ticket') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="ticketForm" autocomplete="off" novalidate>
                @csrf
                    <!-- Add a unique submission token to prevent duplicate submissions -->
                    <input type="hidden" name="_submission_token" value="{{ Str::random(32) }}">
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="requester_name" class="block text-sm font-medium text-gray-700 mb-1">
                                Your Name <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-person text-gray-400"></i>
                                </div>
                                <select 
                                    class="input-focus block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand" 
                                    id="requester_name" 
                                    name="requester_name" 
                                    required
                                >
                            <option value="">-- Select Your Name --</option>
                            @foreach($staffMembers as $staff)
                                <option value="{{ $staff->id }}" {{ old('requester_name') == $staff->id ? 'selected' : '' }}>
                                    {{ $staff->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                        </div>
                        
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 mb-1">
                                Department
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-building text-gray-400"></i>
                                </div>
                                <select 
                                    class="input-focus block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand" 
                                    id="department" 
                                    name="department"
                                >
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
                
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            Issue Title <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="bi bi-card-heading text-gray-400"></i>
                            </div>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                class="input-focus block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand" 
                                value="{{ old('title') }}" 
                                placeholder="Brief description of your issue" 
                                required
                            >
                        </div>
                </div>
                
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Category
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-tag text-gray-400"></i>
                                </div>
                                <select 
                                    class="input-focus block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand" 
                                    id="category_id" 
                                    name="category_id"
                                >
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                        </div>
                        
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                                Priority <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="bi bi-flag text-gray-400"></i>
                                </div>
                                <select 
                                    class="input-focus block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand" 
                                    id="priority" 
                                    name="priority" 
                                    required
                                >
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                            </div>
                            <div id="priorityIndicator" class="mt-2 text-xs hidden">
                                <span class="priority-indicator px-2 py-1 rounded-md"></span>
                            </div>
                    </div>
                </div>
                
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                            Description <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            class="input-focus block w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-brand" 
                            id="description" 
                            name="description" 
                            rows="5" 
                            placeholder="Please provide details about your issue..."
                            required
                        >{{ old('description') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Please provide as much detail as possible about your issue</p>
                </div>
                
                    <div>
                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-1">
                            Attachments
                        </label>
                        <div class="file-upload rounded-lg p-5 text-center">
                            <label for="attachments" class="cursor-pointer block">
                                <i class="bi bi-cloud-arrow-up text-4xl text-gray-500 mb-3"></i>
                                <p class="text-gray-700 mb-1">Drag and drop files here or click to browse</p>
                                <p class="text-xs text-gray-500">(Maximum size: 10MB per file)</p>
                                <input class="sr-only" type="file" id="attachments" name="attachments[]" multiple>
                        </label>
                    </div>
                        <div id="fileList" class="mt-3 space-y-2"></div>
                </div>
                
                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('public.check.ticket') }}" class="inline-flex items-center text-sm font-medium text-brand hover:text-brand-dark">
                            <i class="bi bi-search mr-2"></i> Check Ticket Status
                        </a>
                        <button 
                            type="submit" 
                            class="btn-brand text-white font-medium py-2.5 px-5 rounded-lg flex items-center"
                        >
                            <i class="bi bi-send mr-2"></i> Submit Ticket
                    </button>
                </div>
            </form>
            </div>
            
            <div class="mt-6 text-center text-xs text-gray-400">
                <p>Need help? Contact the IT Support team for assistance</p>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // File upload handling
            const fileInput = document.getElementById('attachments');
            const fileList = document.getElementById('fileList');
            
            // Form submission protection variables
            let isSubmitting = false;
            let submitTimeout = null;
            const form = document.querySelector('form');
            const submitBtn = form.querySelector('button[type="submit"]');
            
            fileInput.addEventListener('change', function() {
                fileList.innerHTML = '';
                
                for (let i = 0; i < this.files.length; i++) {
                    const file = this.files[i];
                    const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
                    
                    const fileItem = document.createElement('div');
                    fileItem.className = 'bg-blue-50 border border-blue-100 rounded-lg p-2 flex justify-between items-center animate-fade-in';
                    fileItem.innerHTML = `
                        <div class="flex items-center">
                            <i class="bi bi-file-earmark text-blue-500 mr-2"></i>
                            <span class="text-sm text-gray-700">${file.name} <span class="text-xs text-gray-500">(${fileSize} MB)</span></span>
                        </div>
                        <button type="button" class="text-gray-400 hover:text-gray-600">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    `;
                    
                    fileList.appendChild(fileItem);
                    
                    // Add remove functionality
                    const removeBtn = fileItem.querySelector('button');
                    removeBtn.addEventListener('click', function() {
                        fileItem.classList.add('opacity-0');
                        setTimeout(() => {
                        fileItem.remove();
                        }, 300);
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
            
            // Priority indicator
            const prioritySelect = document.getElementById('priority');
            const priorityIndicator = document.getElementById('priorityIndicator');
            const priorityIndicatorSpan = priorityIndicator.querySelector('.priority-indicator');
            
            prioritySelect.addEventListener('change', updatePriorityIndicator);
            
            function updatePriorityIndicator() {
                const selectedValue = prioritySelect.value;
                priorityIndicator.classList.remove('hidden');
                
                // Remove all classes
                priorityIndicatorSpan.classList.remove('low', 'medium', 'high', 'critical');
                
                // Add the appropriate class
                priorityIndicatorSpan.classList.add(selectedValue);
                
                // Update text
                let text = '';
                let icon = '';
                
                if (selectedValue === 'low') {
                    text = 'Low Priority';
                    icon = 'bi-arrow-down-circle';
                } else if (selectedValue === 'medium') {
                    text = 'Medium Priority';
                    icon = 'bi-dash-circle';
                } else if (selectedValue === 'high') {
                    text = 'High Priority';
                    icon = 'bi-arrow-up-circle';
                } else if (selectedValue === 'critical') {
                    text = 'Critical Priority';
                    icon = 'bi-exclamation-circle';
                }
                
                priorityIndicatorSpan.innerHTML = `<i class="bi ${icon} mr-1"></i> ${text}`;
            }
            
            // Initialize on load if there's a selected value
            if (prioritySelect.value) {
                updatePriorityIndicator();
            }
            
            // Form submission protection against multiple submits
            form.addEventListener('submit', function(e) {
                if (isSubmitting) {
                    // Prevent multiple submissions
                    e.preventDefault();
                    console.log('Preventing duplicate submission');
                    return false;
                }
                
                // Set submission lock
                isSubmitting = true;
                
                // Disable submit button and show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split animate-spin mr-2"></i> Submitting...';
                
                // Add a class to the form to visually indicate it's submitting
                form.classList.add('submitting');
                
                // Set a timeout to reset the form if submission takes too long (e.g., network issues)
                submitTimeout = setTimeout(function() {
                    // If form hasn't completed submission after 30 seconds, reset the form state
                    if (isSubmitting) {
                        isSubmitting = false;
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-send mr-2"></i> Submit Ticket';
                        form.classList.remove('submitting');
                        
                        // Show an error message
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded error-shake animate-fade-in';
                        errorDiv.innerHTML = `
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-exclamation-circle text-red-500"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">The form submission is taking longer than expected. Please try again.</p>
                                </div>
                            </div>
                        `;
                        form.insertBefore(errorDiv, form.firstChild);
                        
                        // Remove the error message after 5 seconds
                        setTimeout(() => {
                            errorDiv.remove();
                        }, 5000);
                    }
                }, 30000); // 30 seconds timeout
                
                // Allow form submission to continue
                return true;
            });
            
            // Reset submission state if form submission fails or user navigates back to the page
            window.addEventListener('pageshow', function(event) {
                // When navigating back to the page via browser history, reset the form state
                if (event.persisted) {
                    resetFormSubmissionState();
                }
            });
            
            // Clear the timeout and reset form state
            function resetFormSubmissionState() {
                if (submitTimeout) {
                    clearTimeout(submitTimeout);
                    submitTimeout = null;
                }
                isSubmitting = false;
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-send mr-2"></i> Submit Ticket';
                form.classList.remove('submitting');
            }
            
            // Also reset on page load
            resetFormSubmissionState();
            
            // File upload drag and drop
            const dropZone = document.querySelector('.file-upload');
            
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                dropZone.classList.add('border-brand', 'bg-brand/5');
            }
            
            function unhighlight() {
                dropZone.classList.remove('border-brand', 'bg-brand/5');
            }
            
            dropZone.addEventListener('drop', handleDrop, false);
            
            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        });
    </script>
</body>
</html>
