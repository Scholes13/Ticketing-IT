/**
 * Dropzone.js configuration for file upload
 * This enables drag-and-drop file uploads across the application
 */

document.addEventListener('DOMContentLoaded', function() {
    // Check if the file upload dropzone exists
    if (document.getElementById('file-dropzone')) {
        // Initialize Dropzone
        const dropzoneOptions = {
            url: uploadUrl,
            autoProcessQueue: false, // Don't upload automatically
            parallelUploads: 10,     // Maximum files to process in parallel
            maxFilesize: 10,         // Maximum file size in MB
            acceptedFiles: '.jpg, .jpeg, .png, .gif, .pdf, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .zip, .rar',
            addRemoveLinks: true,    // Show remove links
            dictDefaultMessage: "Drag files here or click to upload",
            dictFileTooBig: 'File too big ({{filesize}}MB). Max size: {{maxFilesize}}MB',
            previewTemplate: document.querySelector('#dropzone-preview-template').innerHTML,
            init: function() {
                const myDropzone = this;
                const submitButton = document.querySelector('#submit-form');
                
                // When the form is submitted, process the file queue
                submitButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    if (myDropzone.getQueuedFiles().length > 0) {
                        myDropzone.processQueue();
                    } else {
                        document.querySelector('#ticket-form').submit();
                    }
                });
                
                // When all files are processed, submit the form
                this.on('queuecomplete', function() {
                    document.querySelector('#ticket-form').submit();
                });
                
                // When a file is added, show the preview
                this.on('addedfile', function(file) {
                    console.log('File added:', file.name);
                });
                
                // Handle file removal
                this.on('removedfile', function(file) {
                    console.log('File removed:', file.name);
                    
                    // If file was already uploaded, send request to delete it
                    if (file.serverFileName) {
                        fetch(deleteFileUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                filename: file.serverFileName
                            })
                        });
                    }
                });
                
                // Show error message when file fails to upload
                this.on('error', function(file, errorMessage) {
                    const errorElement = file.previewElement.querySelector('.dz-error-message');
                    errorElement.textContent = errorMessage;
                });
                
                // For existing files (when editing ticket)
                if (existingFiles && existingFiles.length > 0) {
                    existingFiles.forEach(function(fileUrl) {
                        // Create a mock file
                        const mockFile = {
                            name: fileUrl.split('/').pop(),
                            size: 12345, // Placeholder size
                            accepted: true,
                            serverFileName: fileUrl
                        };
                        
                        // Add the mock file to dropzone
                        myDropzone.emit('addedfile', mockFile);
                        myDropzone.emit('thumbnail', mockFile, fileUrl);
                        myDropzone.emit('complete', mockFile);
                        myDropzone.files.push(mockFile);
                    });
                }
            }
        };
        
        // Initialize Dropzone with the options
        new Dropzone('#file-dropzone', dropzoneOptions);
    }
    
    // Handle direct file input as well for browsers without drag-and-drop support
    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            // Update the file counter
            const fileCounter = document.querySelector('.file-counter');
            if (fileCounter) {
                fileCounter.textContent = this.files.length + ' file(s) selected';
            }
        });
    }
}); 