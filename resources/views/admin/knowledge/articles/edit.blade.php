@extends('layouts.admin')

@section('title', 'Edit Artikel Knowledge Base')

@section('styles')
<!-- Summernote CSS -->
<link href="{{ asset('vendor/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<!-- Select2 CSS -->
<link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}" rel="stylesheet">
<style>
    #content {
        min-height: 300px;
    }
    .note-editor.note-frame {
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
    }
    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: #4e73df;
        border: none;
        color: #fff;
        padding: 2px 5px;
        margin-top: 4px;
    }
    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff;
        margin-right: 5px;
    }
    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff;
    }
    .slug-preview {
        font-family: monospace;
        padding: 6px 12px;
        background-color: #f8f9fc;
        border: 1px solid #e3e6f0;
        border-radius: 0.35rem;
        color: #6e707e;
    }
    #image-upload-button {
        margin-right: 5px;
    }
    .badge-tag {
        font-size: 0.8rem;
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Artikel Knowledge Base</h1>
        @if($article->is_published)
        <a href="{{ route('knowledge.article.show', $article->slug) }}" class="btn btn-info" target="_blank">
            <i class="fas fa-external-link-alt mr-1"></i> Lihat di Knowledge Base
        </a>
        @endif
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.index') }}">Knowledge Base</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.articles.index') }}">Artikel</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Artikel</li>
        </ol>
    </nav>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('admin.knowledge.articles.update', $article->id) }}" method="POST" id="articleForm">
                @csrf
                @method('PUT')
                
                <!-- Main Article Content -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Artikel</h6>
                    </div>
                    <div class="card-body">
                        <!-- Title -->
                        <div class="form-group">
                            <label for="title">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $article->title) }}" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Slug -->
                        <div class="form-group">
                            <label for="slug">Slug <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $article->slug) }}" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="generateSlug">Generate</button>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                URL: <span class="slug-preview">{{ url('knowledge') }}/<span id="slugPreview">{{ old('slug', $article->slug) }}</span></span>
                            </small>
                            @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Category -->
                        <div class="form-group">
                            <label for="category_id">Kategori <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ (old('category_id', $article->category_id) == $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Excerpt -->
                        <div class="form-group">
                            <label for="excerpt">Ringkasan</label>
                            <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $article->excerpt) }}</textarea>
                            <small class="form-text text-muted">Berikan deskripsi singkat tentang artikel ini (150-200 karakter).</small>
                            @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Content -->
                        <div class="form-group">
                            <label for="content">Konten <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" required>{{ old('content', $article->content) }}</textarea>
                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Tags & Related Articles -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tags & Artikel Terkait</h6>
                    </div>
                    <div class="card-body">
                        <!-- Tags -->
                        <div class="form-group">
                            <label for="tags">Tags</label>
                            <select class="form-control select2-tags @error('tags') is-invalid @enderror" id="tags" name="tags[]" multiple>
                                @foreach(old('tags', $article->tags ?? []) as $tag)
                                    <option value="{{ $tag }}" selected>{{ $tag }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Ketik tag dan tekan Enter untuk menambahkannya.</small>
                            @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Related Articles -->
                        <div class="form-group">
                            <label for="related_articles">Artikel Terkait</label>
                            <select class="form-control select2 @error('related_articles') is-invalid @enderror" id="related_articles" name="related_articles[]" multiple>
                                @foreach($relatedArticles as $relatedArticle)
                                    <option value="{{ $relatedArticle->id }}" 
                                        {{ in_array($relatedArticle->id, old('related_articles', $article->relatedArticles->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $relatedArticle->title }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Pilih artikel yang terkait dengan artikel ini.</small>
                            @error('related_articles')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- SEO Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi SEO (Opsional)</h6>
                    </div>
                    <div class="card-body">
                        <!-- SEO Title -->
                        <div class="form-group">
                            <label for="seo_title">SEO Title</label>
                            <input type="text" class="form-control @error('seo_title') is-invalid @enderror" id="seo_title" name="seo_title" value="{{ old('seo_title', $article->seo_title) }}">
                            <small class="form-text text-muted">Judul untuk SEO. Jika kosong, judul artikel akan digunakan.</small>
                            @error('seo_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- SEO Description -->
                        <div class="form-group">
                            <label for="seo_description">SEO Description</label>
                            <textarea class="form-control @error('seo_description') is-invalid @enderror" id="seo_description" name="seo_description" rows="3">{{ old('seo_description', $article->seo_description) }}</textarea>
                            <small class="form-text text-muted">Deskripsi untuk SEO. Jika kosong, ringkasan artikel akan digunakan.</small>
                            @error('seo_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Publish Options -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Pengaturan Publikasi</h6>
                    </div>
                    <div class="card-body">
                        <!-- Order -->
                        <div class="form-group">
                            <label for="order">Urutan</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $article->order) }}" min="0">
                            <small class="form-text text-muted">Urutan untuk menampilkan artikel. Nilai yang lebih rendah akan ditampilkan lebih dulu.</small>
                            @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Featured Status -->
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_featured">Jadikan Artikel Unggulan</label>
                            </div>
                            <small class="form-text text-muted">Artikel unggulan akan ditampilkan di bagian atas halaman utama knowledge base.</small>
                        </div>
                        
                        <!-- Publication Status -->
                        <div class="form-group mb-4">
                            <label class="d-block">Status Publikasi</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_published" name="status" class="custom-control-input" value="published" {{ old('status', $article->is_published ? 'published' : 'draft') == 'published' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status_published">Publikasikan</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_draft" name="status" class="custom-control-input" value="draft" {{ old('status', $article->is_published ? 'published' : 'draft') == 'draft' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="status_draft">Simpan sebagai Draft</label>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.knowledge.articles.show', $article->id) }}" class="btn btn-secondary btn-block mt-2">
                                <i class="fas fa-times mr-1"></i> Batal
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Tips Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tips Penulisan</h6>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0 pl-3">
                            <li class="mb-2">Gunakan judul yang jelas dan deskriptif.</li>
                            <li class="mb-2">Buat struktur konten dengan heading (H2, H3) yang terorganisir.</li>
                            <li class="mb-2">Tambahkan gambar untuk menjelaskan langkah-langkah yang kompleks.</li>
                            <li class="mb-2">Gunakan poin-poin dan nomor untuk langkah-langkah.</li>
                            <li class="mb-2">Hindari jargon teknis yang berlebihan.</li>
                            <li>Berikan contoh jika memungkinkan.</li>
                        </ul>
                    </div>
                </div>
                
                <!-- Last Updated Info -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Informasi Revisi</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-0">
                            <label>Terakhir Diperbarui:</label>
                            <p class="mb-1">{{ $article->updated_at->format('d M Y H:i') }}</p>
                            <small class="text-muted">oleh {{ $article->updatedBy->name ?? $article->author->name ?? 'Unknown' }}</small>
                        </div>
                        
                        @if($article->created_at->ne($article->updated_at))
                        <hr>
                        <div class="form-group mb-0">
                            <label>Dibuat:</label>
                            <p class="mb-1">{{ $article->created_at->format('d M Y H:i') }}</p>
                            <small class="text-muted">oleh {{ $article->author->name ?? 'Unknown' }}</small>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- Summernote JS -->
<script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
<!-- Select2 JS -->
<script src="{{ asset('vendor/select2/select2.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize Summernote editor
    $('#content').summernote({
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
            onImageUpload: function(files) {
                uploadImage(files[0]);
            }
        }
    });
    
    function uploadImage(file) {
        var formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        $.ajax({
            url: '{{ route('admin.knowledge.upload-image') }}',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#content').summernote('insertImage', data.url);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error(textStatus + ': ' + errorThrown);
                alert('Gagal mengupload gambar. Silakan coba lagi.');
            }
        });
    }
    
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4'
    });
    
    // Initialize Select2 for tags
    $('.select2-tags').select2({
        theme: 'bootstrap4',
        tags: true,
        tokenSeparators: [',']
    });
    
    // Generate slug from title
    $('#generateSlug').click(function() {
        var title = $('#title').val();
        if(title) {
            $.ajax({
                url: '{{ route('admin.knowledge.generate-slug') }}',
                method: 'POST',
                data: {
                    title: title,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#slug').val(data.slug);
                    $('#slugPreview').text(data.slug);
                }
            });
        }
    });
    
    // Update slug preview
    $('#slug').on('input', function() {
        $('#slugPreview').text($(this).val());
    });
});
</script>
@endsection 