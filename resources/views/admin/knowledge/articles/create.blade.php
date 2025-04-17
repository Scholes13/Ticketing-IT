@extends('layouts.admin')

@section('title', 'Tambah Artikel Knowledge Base')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css">
<style>
    .note-editor {
        margin-bottom: 15px;
    }
    .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
        background-color: #4e73df;
        border: none;
        color: #fff;
    }
    .tag-badge {
        background-color: #4e73df;
        color: white;
        font-size: 0.85rem;
        border-radius: 5px;
        padding: 3px 8px;
        margin-right: 5px;
        margin-bottom: 5px;
        display: inline-block;
    }
    .tag-badge .close {
        margin-left: 5px;
        color: white;
        font-size: 0.9rem;
        font-weight: bold;
        opacity: 0.8;
    }
    .tag-badge .close:hover {
        opacity: 1;
        text-decoration: none;
    }
    #generated-slug {
        font-weight: bold;
        color: #4e73df;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Artikel Baru</h1>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.index') }}">Knowledge Base</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Artikel</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tulis Artikel Baru</h6>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.knowledge.articles.store') }}" method="POST" id="article-form">
                        @csrf
                        
                        <div class="form-group">
                            <label for="title">Judul Artikel <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" 
                                value="{{ old('title') }}" required placeholder="Masukkan judul artikel">
                            <small class="form-text text-muted">
                                Judul yang baik harus jelas, spesifik, dan mengandung kata kunci yang relevan.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="slug" name="slug" 
                                    value="{{ old('slug') }}" placeholder="judul-artikel">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="generate-slug">
                                        <i class="fas fa-sync-alt"></i> Generate
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                URL friendly dari judul. Dibuat otomatis jika tidak diisi. 
                                <span id="slug-preview"></span>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="category_id">Kategori <span class="text-danger">*</span></label>
                            <select class="form-control" id="category_id" name="category_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Pilih kategori yang paling sesuai dengan isi artikel.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="excerpt">Ringkasan</label>
                            <textarea class="form-control" id="excerpt" name="excerpt" 
                                rows="3" placeholder="Ringkasan singkat dari isi artikel">{{ old('excerpt') }}</textarea>
                            <small class="form-text text-muted">
                                Ringkasan singkat artikel yang akan ditampilkan di halaman daftar dan hasil pencarian (150-200 karakter).
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="content">Isi Artikel <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="content" name="content" required>{{ old('content') }}</textarea>
                            <small class="form-text text-muted">
                                Tulis artikel dengan format yang jelas. Gunakan heading, list, dan tabel sesuai kebutuhan.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="tags">Tag</label>
                            <select class="form-control" id="tags" name="tags[]" multiple>
                                @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}" {{ (old('tags') && in_array($tag->id, old('tags'))) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Tambahkan tag yang relevan untuk memudahkan pencarian. Bisa input tag baru.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="related_articles">Artikel Terkait</label>
                            <select class="form-control" id="related_articles" name="related_articles[]" multiple>
                                @foreach ($articles as $article)
                                <option value="{{ $article->id }}" {{ (old('related_articles') && in_array($article->id, old('related_articles'))) ? 'selected' : '' }}>
                                    {{ $article->title }}
                                </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Pilih artikel yang berkaitan dengan artikel ini.
                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pengaturan Publikasi</h6>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="order">Urutan</label>
                        <input type="number" class="form-control" id="order" name="order" form="article-form"
                            value="{{ old('order', 0) }}" min="0">
                        <small class="form-text text-muted">
                            Tentukan urutan tampilan artikel. Nilai lebih rendah akan ditampilkan lebih awal.
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" 
                                value="1" form="article-form" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_featured">Artikel Unggulan</label>
                        </div>
                        <small class="form-text text-muted">
                            Artikel unggulan akan ditampilkan di bagian teratas halaman Knowledge Base.
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" 
                                value="1" form="article-form" {{ old('is_published', true) ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_published">Publikasikan</label>
                        </div>
                        <small class="form-text text-muted">
                            Artikel yang tidak dipublikasikan hanya akan terlihat oleh admin (draft).
                        </small>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <button type="submit" form="article-form" class="btn btn-primary btn-block">
                            <i class="fas fa-save mr-1"></i> Simpan Artikel
                        </button>
                        <button type="submit" form="article-form" name="save_type" value="preview" class="btn btn-info btn-block">
                            <i class="fas fa-eye mr-1"></i> Simpan & Preview
                        </button>
                    </div>
                    
                    <a href="{{ route('admin.knowledge.index') }}" class="btn btn-secondary btn-block">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tips Penulisan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <h6 class="font-weight-bold"><i class="fas fa-check-circle text-success mr-1"></i> Judul Jelas</h6>
                        <p class="small">Gunakan judul yang jelas dan langsung menggambarkan isi artikel.</p>
                    </div>
                    <div class="mb-2">
                        <h6 class="font-weight-bold"><i class="fas fa-check-circle text-success mr-1"></i> Struktur</h6>
                        <p class="small">Gunakan heading untuk membagi artikel menjadi bagian-bagian yang mudah dibaca.</p>
                    </div>
                    <div class="mb-2">
                        <h6 class="font-weight-bold"><i class="fas fa-check-circle text-success mr-1"></i> Simpel</h6>
                        <p class="small">Tulis dengan bahasa yang sederhana dan mudah dipahami oleh pengguna.</p>
                    </div>
                    <div class="mb-2">
                        <h6 class="font-weight-bold"><i class="fas fa-check-circle text-success mr-1"></i> Visual</h6>
                        <p class="small">Sertakan gambar, video, atau ilustrasi untuk menjelaskan konsep yang kompleks.</p>
                    </div>
                    <div>
                        <h6 class="font-weight-bold"><i class="fas fa-check-circle text-success mr-1"></i> Contoh</h6>
                        <p class="small">Berikan contoh nyata dan langkah-langkah yang jelas jika menjelaskan prosedur.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Summernote editor
    $('#content').summernote({
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
            onImageUpload: function(files) {
                // Handle image upload
                for (let i = 0; i < files.length; i++) {
                    uploadImage(files[i]);
                }
            }
        }
    });
    
    // Upload image function
    function uploadImage(file) {
        let formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        $.ajax({
            url: '{{ route("admin.knowledge.upload-image") }}',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                $('#content').summernote('insertImage', data.url);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Gagal mengupload gambar. Silakan coba lagi.');
            }
        });
    }
    
    // Initialize Select2 for categories
    $('#category_id').select2({
        theme: 'bootstrap4',
        placeholder: "Pilih kategori",
    });
    
    // Initialize Select2 for tags with tag creation
    $('#tags').select2({
        theme: 'bootstrap4',
        placeholder: "Pilih atau tambahkan tag",
        tags: true,
        tokenSeparators: [',', ' '],
        createTag: function(params) {
            return {
                id: params.term,
                text: params.term,
                newTag: true
            };
        }
    });
    
    // Initialize Select2 for related articles
    $('#related_articles').select2({
        theme: 'bootstrap4',
        placeholder: "Pilih artikel terkait",
    });
    
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    const generateSlugBtn = document.getElementById('generate-slug');
    const slugPreview = document.getElementById('slug-preview');
    
    function generateSlug(title) {
        return title.toLowerCase()
            .replace(/[^\w\s-]/g, '')  // Remove special characters
            .replace(/\s+/g, '-')      // Replace spaces with hyphens
            .replace(/-+/g, '-');      // Replace multiple hyphens with single hyphen
    }
    
    titleInput.addEventListener('input', function() {
        if (!slugInput.value) {
            const slug = generateSlug(this.value);
            slugInput.value = slug;
            updateSlugPreview(slug);
        }
    });
    
    generateSlugBtn.addEventListener('click', function() {
        const slug = generateSlug(titleInput.value);
        slugInput.value = slug;
        updateSlugPreview(slug);
    });
    
    slugInput.addEventListener('input', function() {
        updateSlugPreview(this.value);
    });
    
    function updateSlugPreview(slug) {
        if (slug) {
            slugPreview.textContent = `URL: /knowledge/articles/${slug}`;
        } else {
            slugPreview.textContent = '';
        }
    }
    
    // Initialize preview
    if (slugInput.value) {
        updateSlugPreview(slugInput.value);
    }
});
</script>
@endsection 