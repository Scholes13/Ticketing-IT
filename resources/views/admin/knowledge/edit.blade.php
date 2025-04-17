@extends('layouts.admin')

@section('title', 'Edit Artikel Knowledge Base')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .note-editor {
        margin-bottom: 0;
    }
    .tag-input {
        display: block;
        width: 100%;
        padding: .375rem .75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #6e707e;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #d1d3e2;
        border-radius: .35rem;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .tag-input .tag {
        display: inline-block;
        background-color: #4e73df;
        color: white;
        padding: 2px 8px;
        margin-right: 5px;
        margin-bottom: 5px;
        border-radius: 3px;
    }
    .tag-input .tag .remove {
        margin-left: 5px;
        cursor: pointer;
    }
    .tag-input input {
        border: none;
        outline: none;
        background-color: transparent;
        padding: 3px;
        min-width: 60px;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Artikel Knowledge Base</h1>
        
        <div>
            <a href="{{ route('knowledge.show', $article->slug) }}" class="btn btn-sm btn-info" target="_blank">
                <i class="fas fa-eye"></i> Lihat Artikel
            </a>
        </div>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.index') }}">Knowledge Base</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Artikel</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Form Edit Artikel</h6>
                    <div class="text-muted small">
                        ID: {{ $article->id }} | Dibuat: {{ $article->created_at->format('d M Y') }}
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.knowledge.update', $article->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="title">Judul Artikel <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $article->title) }}" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Judul artikel yang akan ditampilkan di Knowledge Base.</small>
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $article->slug) }}">
                            @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">URL-friendly nama artikel (otomatis diisi berdasarkan judul jika dikosongkan).</small>
                        </div>

                        <div class="form-group">
                            <label for="category_id">Kategori</label>
                            <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                <option value="">-- Tidak ada --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @if($category->children && $category->children->count() > 0)
                                        @foreach($category->children as $child)
                                            <option value="{{ $child->id }}" {{ old('category_id', $article->category_id) == $child->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;└─ {{ $child->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Pilih kategori untuk artikel ini.</small>
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Deskripsi Singkat</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', $article->meta_description) }}</textarea>
                            @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Deskripsi singkat tentang artikel ini (akan digunakan untuk meta description dan preview).</small>
                        </div>

                        <div class="form-group">
                            <label for="content">Konten Artikel <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content', $article->content) }}</textarea>
                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tags">Tag</label>
                            <div class="tag-input @error('tags') is-invalid @enderror" id="tagContainer">
                                <input type="text" id="tagInput" placeholder="Tambah tag dan tekan Enter">
                            </div>
                            <input type="hidden" name="tags" id="tagsHidden" value="{{ old('tags', $article->tags) }}">
                            @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Tambahkan tag yang relevan untuk membantu pencarian artikel (tekan Enter setelah mengetik).</small>
                        </div>

                        <div class="form-group mb-4">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_published">Publikasikan</label>
                                <small class="form-text text-muted">Artikel yang tidak dipublikasikan akan disimpan sebagai draft.</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Artikel
                        </button>
                        <a href="{{ route('admin.knowledge.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik Artikel</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="small text-muted">Total Views</div>
                        <div class="h5 mb-0">{{ number_format($article->views_count) }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="small text-muted">Status</div>
                        <div class="h5 mb-0">
                            @if($article->is_published)
                                <span class="badge badge-success">Dipublikasikan</span>
                                @if($article->published_at)
                                    <div class="small text-muted mt-1">Tanggal: {{ $article->published_at->format('d M Y H:i') }}</div>
                                @endif
                            @else
                                <span class="badge badge-secondary">Draft</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="small text-muted">Diperbarui</div>
                        <div class="h5 mb-0">{{ $article->updated_at->format('d M Y H:i') }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="small text-muted">Penulis</div>
                        <div class="h5 mb-0">{{ $article->author->name ?? 'Tidak Ada' }}</div>
                    </div>
                    
                    @if($article->tickets_count > 0)
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-link"></i> Artikel ini terhubung dengan {{ $article->tickets_count }} tiket.
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Panduan Penulisan</h6>
                </div>
                <div class="card-body">
                    <h5>Tips Menulis Artikel yang Efektif:</h5>
                    <ul>
                        <li>Gunakan judul yang deskriptif dan jelas</li>
                        <li>Buat pendahuluan yang menjelaskan masalah</li>
                        <li>Gunakan sub-judul untuk memisahkan bagian</li>
                        <li>Sertakan langkah-langkah yang jelas</li>
                        <li>Tambahkan gambar jika diperlukan</li>
                        <li>Tutup dengan kesimpulan atau pertanyaan umum</li>
                    </ul>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tindakan</h6>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-danger btn-block mb-2" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash"></i> Hapus Artikel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus artikel <strong>{{ $article->title }}</strong>?</p>
                <p class="text-danger">Tindakan ini tidak dapat dibatalkan.</p>
                
                @if($article->tickets_count > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Artikel ini terhubung dengan {{ $article->tickets_count }} tiket. Menghapus artikel ini akan menghapus referensi pada tiket-tiket tersebut.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('admin.knowledge.destroy', $article->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Artikel</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Summernote WYSIWYG editor
    $('#content').summernote({
        placeholder: 'Tulis konten artikel di sini...',
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Handle tags
    const tagsHidden = document.getElementById('tagsHidden');
    const tagContainer = document.getElementById('tagContainer');
    const tagInput = document.getElementById('tagInput');
    
    let tags = [];
    
    // If there are existing tags from old input, parse them
    if (tagsHidden.value) {
        try {
            tags = tagsHidden.value.split(',').map(tag => tag.trim());
            renderTags();
        } catch (e) {
            console.error('Error parsing tags:', e);
        }
    }
    
    function renderTags() {
        // Remove all tag elements
        const existingTags = tagContainer.querySelectorAll('.tag');
        existingTags.forEach(tag => tag.remove());
        
        // Add tags before the input
        tags.forEach(tag => {
            const tagElement = document.createElement('span');
            tagElement.classList.add('tag');
            tagElement.innerHTML = `${tag}<span class="remove">×</span>`;
            
            tagElement.querySelector('.remove').addEventListener('click', function() {
                tags = tags.filter(t => t !== tag);
                renderTags();
                updateHiddenInput();
            });
            
            tagContainer.insertBefore(tagElement, tagInput);
        });
    }
    
    function updateHiddenInput() {
        tagsHidden.value = tags.join(',');
    }
    
    tagInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ',') {
            e.preventDefault();
            
            const value = this.value.trim();
            if (value && !tags.includes(value)) {
                tags.push(value);
                this.value = '';
                renderTags();
                updateHiddenInput();
            }
        }
    });
    
    // Add tag when input loses focus
    tagInput.addEventListener('blur', function() {
        const value = this.value.trim();
        if (value && !tags.includes(value)) {
            tags.push(value);
            this.value = '';
            renderTags();
            updateHiddenInput();
        }
    });
    
    // Auto-generate slug from title
    const titleInput = document.getElementById('title');
    const slugInput = document.getElementById('slug');
    
    titleInput.addEventListener('blur', function() {
        if (slugInput.value === '') {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            
            slugInput.value = slug;
        }
    });
});
</script>
@endsection 