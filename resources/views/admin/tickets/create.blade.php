@extends('layouts.admin')

@section('title', 'Create Ticket')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
<link rel="stylesheet" href="{{ asset('css/dropzone.css') }}" type="text/css" />
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Create New Ticket</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Tickets</a></li>
        <li class="breadcrumb-item active">Create New Ticket</li>
    </ol>
    
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
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus-circle me-1"></i>
            New Ticket Details
        </div>
        <div class="card-body">
            <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" id="ticket-form">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="requester_name" class="form-label">Requester Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('requester_name') is-invalid @enderror" id="requester_name" name="requester_name" value="{{ old('requester_name') }}" required>
                            @error('requester_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="requester_email" class="form-label">Requester Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('requester_email') is-invalid @enderror" id="requester_email" name="requester_email" value="{{ old('requester_email') }}" required>
                            @error('requester_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="requester_phone" class="form-label">Requester Phone</label>
                            <input type="text" class="form-control @error('requester_phone') is-invalid @enderror" id="requester_phone" name="requester_phone" value="{{ old('requester_phone') }}">
                            @error('requester_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" value="{{ old('department') }}">
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="waiting" {{ old('status') == 'waiting' ? 'selected' : '' }}>Waiting</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="assigned_to" class="form-label">Assigned To</label>
                    <select class="form-select @error('assigned_to') is-invalid @enderror" id="assigned_to" name="assigned_to">
                        <option value="">-- Unassigned --</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->id }}" {{ old('assigned_to') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="file-dropzone" class="form-label">Attachments</label>
                    <div id="file-dropzone" class="dropzone">
                        <div class="dz-message" data-dz-message>
                            <span>Drag files here or click to upload</span>
                            <span class="text-muted d-block mt-2">You can upload multiple files (max 10MB per file)</span>
                        </div>
                    </div>
                    <div class="file-counter mt-2 text-muted small">0 file(s) selected</div>
                    @error('attachments.*')
                        <div class="text-danger mt-2 small">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('tickets.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" id="submit-form" class="btn btn-primary">Create Ticket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template for Dropzone preview -->
<div id="dropzone-preview-template" style="display: none;">
    <div class="dz-preview dz-file-preview">
        <div class="dz-image">
            <img data-dz-thumbnail />
        </div>
        <div class="dz-details">
            <div class="dz-filename"><span data-dz-name></span></div>
            <div class="dz-size"><span data-dz-size></span></div>
        </div>
        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
        <div class="dz-error-message"><span data-dz-errormessage></span></div>
        <div class="dz-success-mark">
            <svg width="54" height="54" viewBox="0 0 54 54" fill="white" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.2071 29.7929L14.2929 25.7071C14.6834 25.3166 15.3166 25.3166 15.7071 25.7071L21.2929 31.2929C21.6834 31.6834 22.3166 31.6834 22.7071 31.2929L38.2929 15.7071C38.6834 15.3166 39.3166 15.3166 39.7071 15.7071L43.7929 19.7929C44.1834 20.1834 44.1834 20.8166 43.7929 21.2071L22.7071 42.2929C22.3166 42.6834 21.6834 42.6834 21.2929 42.2929L10.2071 31.2071C9.81658 30.8166 9.81658 30.1834 10.2071 29.7929Z"/>
            </svg>
        </div>
        <div class="dz-error-mark">
            <svg width="54" height="54" viewBox="0 0 54 54" fill="white" xmlns="http://www.w3.org/2000/svg">
                <path d="M26.2929 20.2929L19.2071 13.2071C18.8166 12.8166 18.1834 12.8166 17.7929 13.2071L13.2071 17.7929C12.8166 18.1834 12.8166 18.8166 13.2071 19.2071L20.2929 26.2929C20.6834 26.6834 20.6834 27.3166 20.2929 27.7071L13.2071 34.7929C12.8166 35.1834 12.8166 35.8166 13.2071 36.2071L17.7929 40.7929C18.1834 41.1834 18.8166 41.1834 19.2071 40.7929L26.2929 33.7071C26.6834 33.3166 27.3166 33.3166 27.7071 33.7071L34.7929 40.7929C35.1834 41.1834 35.8166 41.1834 36.2071 40.7929L40.7929 36.2071C41.1834 35.8166 41.1834 35.1834 40.7929 34.7929L33.7071 27.7071C33.3166 27.3166 33.3166 26.6834 33.7071 26.2929L40.7929 19.2071C41.1834 18.8166 41.1834 18.1834 40.7929 17.7929L36.2071 13.2071C35.8166 12.8166 35.1834 12.8166 34.7929 13.2071L27.7071 20.2929C27.3166 20.6834 26.6834 20.6834 26.2929 20.2929Z"/>
            </svg>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
    // Prevent Dropzone from auto-initializing
    Dropzone.autoDiscover = false;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Upload URL for the files
        const uploadUrl = "{{ route('tickets.store') }}";
        
        // Initialize Dropzone
        const myDropzone = new Dropzone("#file-dropzone", {
            url: uploadUrl,
            autoProcessQueue: false,
            parallelUploads: 10,
            maxFilesize: 10,
            acceptedFiles: '.jpg, .jpeg, .png, .gif, .pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .zip, .rar',
            addRemoveLinks: true,
            previewTemplate: document.querySelector('#dropzone-preview-template').innerHTML,
            uploadMultiple: true,
            paramName: "attachments",
            // Force the name of the parameter to be attachments[] for Laravel
            params: function(files, xhr, chunk) {
                return { 
                    _token: "{{ csrf_token() }}"
                };
            }
        });
        
        // Update file counter when files are added or removed
        const updateFileCounter = function() {
            const fileCounter = document.querySelector('.file-counter');
            const count = myDropzone.files.length;
            fileCounter.textContent = count + ' file(s) selected';
            
            // Add visual feedback when files are added
            if (count > 0) {
                fileCounter.classList.add('text-primary');
                fileCounter.classList.remove('text-muted');
            } else {
                fileCounter.classList.remove('text-primary');
                fileCounter.classList.add('text-muted');
            }
        };
        
        myDropzone.on("addedfile", function(file) {
            updateFileCounter();
            
            // Set a type attribute for icon display
            if (file.type) {
                const preview = file.previewElement.querySelector('.dz-image');
                if (preview) {
                    preview.setAttribute('data-type', file.type);
                }
            }
        });
        
        myDropzone.on("removedfile", function(file) {
            updateFileCounter();
        });
        
        // Handle form submission
        document.querySelector('#ticket-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (myDropzone.files.length > 0) {
                myDropzone.processQueue();
            } else {
                this.submit();
            }
        });
        
        // When all files are processed, submit the form
        myDropzone.on("success", function() {
            if (myDropzone.getUploadingFiles().length === 0 && myDropzone.getQueuedFiles().length === 0) {
                document.querySelector('#ticket-form').submit();
            }
        });
        
        // Handle dragover animation
        const dropzoneElement = document.querySelector('#file-dropzone');
        
        dropzoneElement.addEventListener('dragover', function() {
            this.classList.add('pulse');
        });
        
        dropzoneElement.addEventListener('dragleave', function() {
            this.classList.remove('pulse');
        });
        
        dropzoneElement.addEventListener('drop', function() {
            this.classList.remove('pulse');
        });
    });
</script>
@endsection
