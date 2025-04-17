@extends('layouts.admin')

@section('title', 'Tambah Kategori Knowledge Base')

@section('styles')
<style>
    .icon-preview {
        font-size: 1.5rem;
        width: 40px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        margin-right: 10px;
        border: 1px solid #d1d3e2;
        border-radius: 0.35rem;
    }
    
    .icon-card {
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .icon-card:hover {
        background-color: #f8f9fc;
        border-color: #4e73df;
    }
    
    .icon-card.selected {
        background-color: #4e73df;
        color: white;
    }
    
    .icon-card.selected i {
        color: white;
    }
    
    .fa-icon {
        font-size: 1.2rem;
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tambah Kategori Knowledge Base</h1>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.index') }}">Knowledge Base</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.categories.index') }}">Kategori</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Kategori</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Form Tambah Kategori</h6>
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

                    <form action="{{ route('admin.knowledge.categories.store') }}" method="POST" id="category-form">
                        @csrf
                        
                        <div class="form-group">
                            <label for="name">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            <small class="form-text text-muted">
                                Nama kategori yang akan ditampilkan kepada pengguna.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="generate-slug">
                                        <i class="fas fa-sync-alt"></i> Generate
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                URL friendly dari nama kategori. <span id="slug-preview"></span>
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            <small class="form-text text-muted">
                                Deskripsi singkat tentang kategori ini (opsional).
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="parent_id">Parent Kategori</label>
                            <select class="form-control" id="parent_id" name="parent_id">
                                <option value="">-- Tidak Ada Parent (Kategori Utama) --</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Pilih kategori induk jika ini adalah sub-kategori (opsional).
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="icon">Icon <small>(Font Awesome)</small></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <span class="icon-preview" id="icon-preview">
                                            <i class="fas fa-folder" id="selected-icon"></i>
                                        </span>
                                    </div>
                                </div>
                                <input type="text" class="form-control" id="icon" name="icon" value="{{ old('icon', 'fa-folder') }}">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#iconModal">
                                        <i class="fas fa-icons"></i> Pilih Icon
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted">
                                Pilih icon Font Awesome untuk kategori ini.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="order">Urutan</label>
                            <input type="number" class="form-control" id="order" name="order" value="{{ old('order', 0) }}" min="0">
                            <small class="form-text text-muted">
                                Urutan tampilan kategori (angka lebih kecil ditampilkan lebih dulu).
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktif</label>
                            </div>
                            <small class="form-text text-muted">
                                Kategori yang tidak aktif tidak akan ditampilkan kepada pengguna.
                            </small>
                        </div>

                        <hr>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Simpan Kategori
                        </button>
                        <a href="{{ route('admin.knowledge.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tips Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Struktur Kategori</h6>
                        <p>Struktur kategori yang baik membantu pengguna menemukan informasi dengan cepat.</p>
                        <ul>
                            <li>Gunakan kategori utama untuk topik besar</li>
                            <li>Buat sub-kategori untuk sub-topik yang lebih spesifik</li>
                            <li>Jaga kedalaman maksimal 2-3 level kategori</li>
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Penamaan Kategori</h6>
                        <p>Nama kategori yang baik harus:</p>
                        <ul>
                            <li>Singkat dan jelas</li>
                            <li>Mudah dipahami oleh pengguna</li>
                            <li>Mencerminkan isi artikel di dalamnya</li>
                            <li>Konsisten dengan kategori lainnya</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h6 class="font-weight-bold">Icon</h6>
                        <p>Icon membantu pengguna mengidentifikasi kategori secara visual:</p>
                        <ul>
                            <li>Pilih icon yang merepresentasikan kategori</li>
                            <li>Gunakan gaya icon yang konsisten</li>
                            <li>Icon membantu meningkatkan UX</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Icon Selection Modal -->
<div class="modal fade" id="iconModal" tabindex="-1" role="dialog" aria-labelledby="iconModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconModalLabel">Pilih Icon</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input type="text" class="form-control" id="icon-search" placeholder="Cari icon...">
                </div>
                
                <div class="row" id="icon-list">
                    <!-- Common Font Awesome Icons -->
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-folder">
                            <div class="text-center">
                                <i class="fas fa-folder fa-icon"></i>
                                <div class="small text-muted">folder</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-file">
                            <div class="text-center">
                                <i class="fas fa-file fa-icon"></i>
                                <div class="small text-muted">file</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-question-circle">
                            <div class="text-center">
                                <i class="fas fa-question-circle fa-icon"></i>
                                <div class="small text-muted">question-circle</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-info-circle">
                            <div class="text-center">
                                <i class="fas fa-info-circle fa-icon"></i>
                                <div class="small text-muted">info-circle</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-book">
                            <div class="text-center">
                                <i class="fas fa-book fa-icon"></i>
                                <div class="small text-muted">book</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-cog">
                            <div class="text-center">
                                <i class="fas fa-cog fa-icon"></i>
                                <div class="small text-muted">cog</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-user">
                            <div class="text-center">
                                <i class="fas fa-user fa-icon"></i>
                                <div class="small text-muted">user</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-users">
                            <div class="text-center">
                                <i class="fas fa-users fa-icon"></i>
                                <div class="small text-muted">users</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-server">
                            <div class="text-center">
                                <i class="fas fa-server fa-icon"></i>
                                <div class="small text-muted">server</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-database">
                            <div class="text-center">
                                <i class="fas fa-database fa-icon"></i>
                                <div class="small text-muted">database</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-laptop">
                            <div class="text-center">
                                <i class="fas fa-laptop fa-icon"></i>
                                <div class="small text-muted">laptop</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-mobile-alt">
                            <div class="text-center">
                                <i class="fas fa-mobile-alt fa-icon"></i>
                                <div class="small text-muted">mobile-alt</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-envelope">
                            <div class="text-center">
                                <i class="fas fa-envelope fa-icon"></i>
                                <div class="small text-muted">envelope</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-shield-alt">
                            <div class="text-center">
                                <i class="fas fa-shield-alt fa-icon"></i>
                                <div class="small text-muted">shield-alt</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-dollar-sign">
                            <div class="text-center">
                                <i class="fas fa-dollar-sign fa-icon"></i>
                                <div class="small text-muted">dollar-sign</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-credit-card">
                            <div class="text-center">
                                <i class="fas fa-credit-card fa-icon"></i>
                                <div class="small text-muted">credit-card</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-bug">
                            <div class="text-center">
                                <i class="fas fa-bug fa-icon"></i>
                                <div class="small text-muted">bug</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-wrench">
                            <div class="text-center">
                                <i class="fas fa-wrench fa-icon"></i>
                                <div class="small text-muted">wrench</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-cogs">
                            <div class="text-center">
                                <i class="fas fa-cogs fa-icon"></i>
                                <div class="small text-muted">cogs</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-chart-pie">
                            <div class="text-center">
                                <i class="fas fa-chart-pie fa-icon"></i>
                                <div class="small text-muted">chart-pie</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-chart-line">
                            <div class="text-center">
                                <i class="fas fa-chart-line fa-icon"></i>
                                <div class="small text-muted">chart-line</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-graduation-cap">
                            <div class="text-center">
                                <i class="fas fa-graduation-cap fa-icon"></i>
                                <div class="small text-muted">graduation-cap</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card py-2 px-3 icon-card" data-icon="fa-clipboard">
                            <div class="text-center">
                                <i class="fas fa-clipboard fa-icon"></i>
                                <div class="small text-muted">clipboard</div>
                            </div>
                        </div>
                    </div>
                    <!-- Add more icons as needed -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug from name
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    const generateSlugBtn = document.getElementById('generate-slug');
    const slugPreview = document.getElementById('slug-preview');
    
    function generateSlug(name) {
        return name.toLowerCase()
            .replace(/[^\w\s-]/g, '')  // Remove special characters
            .replace(/\s+/g, '-')      // Replace spaces with hyphens
            .replace(/-+/g, '-');      // Replace multiple hyphens with single hyphen
    }
    
    generateSlugBtn.addEventListener('click', function() {
        const slug = generateSlug(nameInput.value);
        slugInput.value = slug;
        updateSlugPreview(slug);
    });
    
    nameInput.addEventListener('blur', function() {
        if (slugInput.value === '' && nameInput.value !== '') {
            const slug = generateSlug(nameInput.value);
            slugInput.value = slug;
            updateSlugPreview(slug);
        }
    });
    
    slugInput.addEventListener('input', function() {
        updateSlugPreview(this.value);
    });
    
    function updateSlugPreview(slug) {
        if (slug) {
            slugPreview.textContent = `URL: /knowledge/categories/${slug}`;
        } else {
            slugPreview.textContent = '';
        }
    }
    
    // Initialize preview if slug has a value
    if (slugInput.value) {
        updateSlugPreview(slugInput.value);
    }
    
    // Icon selector functionality
    const iconCards = document.querySelectorAll('.icon-card');
    const iconInput = document.getElementById('icon');
    const selectedIcon = document.getElementById('selected-icon');
    const iconSearch = document.getElementById('icon-search');
    
    // Initialize selected icon
    updateSelectedIcon(iconInput.value);
    
    iconCards.forEach(card => {
        card.addEventListener('click', function() {
            const iconValue = this.getAttribute('data-icon');
            iconInput.value = iconValue;
            updateSelectedIcon(iconValue);
            
            // Remove selected class from all cards
            iconCards.forEach(c => c.classList.remove('selected'));
            // Add selected class to clicked card
            this.classList.add('selected');
            
            // Close modal
            $('#iconModal').modal('hide');
        });
    });
    
    function updateSelectedIcon(iconValue) {
        // Remove old classes
        selectedIcon.className = '';
        // Add new icon class
        selectedIcon.classList.add('fas');
        selectedIcon.classList.add(iconValue);
        
        // Mark the corresponding card as selected
        iconCards.forEach(card => {
            if (card.getAttribute('data-icon') === iconValue) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    }
    
    // Icon search functionality
    iconSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        iconCards.forEach(card => {
            const iconName = card.getAttribute('data-icon').toLowerCase();
            if (iconName.includes(searchTerm)) {
                card.parentElement.style.display = '';
            } else {
                card.parentElement.style.display = 'none';
            }
        });
    });
});
</script>
@endsection 