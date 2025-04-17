@extends('layouts.admin')

@section('title', 'Manajemen Knowledge Base')

@section('styles')
<style>
    /* Modern Dashboard Styles */
    :root {
        --primary: #4e73df;
        --success: #1cc88a;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --info: #36b9cc;
        --dark: #5a5c69;
        --light: #f8f9fc;
        --white: #fff;
    }
    
    .dashboard-container {
        padding: 1.5rem 0;
    }
    
    .page-header {
        position: relative;
        padding: 2rem;
        background: linear-gradient(135deg, #6e8efb, #4e73df);
        border-radius: 12px;
        color: white;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    
    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-image: url('https://cdn.pixabay.com/photo/2018/05/08/08/44/artificial-intelligence-3382507_960_720.jpg');
        background-size: cover;
        opacity: 0.1;
        z-index: 0;
    }
    
    .page-header-content {
        position: relative;
        z-index: 1;
    }
    
    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .page-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }
    
    .action-button {
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-weight: 600;
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        background: white;
        color: var(--primary);
        border: none;
    }
    
    .action-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
        background: rgba(255, 255, 255, 0.9);
    }
    
    .action-button i {
        margin-right: 8px;
    }
    
    .breadcrumb-modern {
        background: white;
        border-radius: 50px;
        box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        padding: 0.5rem 1.5rem;
        display: inline-flex;
    }
    
    .stats-container {
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.12);
    }
    
    .stat-header {
        padding: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .stat-title {
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0;
    }
    
    .stat-icon {
        height: 60px;
        width: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: white;
    }
    
    .stat-footer {
        background: rgba(0, 0, 0, 0.03);
        padding: 0.75rem 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .stat-footer a {
        font-size: 0.8rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-footer a i {
        margin-left: 8px;
        transition: transform 0.3s;
    }
    
    .stat-footer a:hover i {
        transform: translateX(3px);
    }
    
    .bg-primary-gradient {
        background: linear-gradient(135deg, #6e8efb, #4e73df);
    }
    
    .bg-success-gradient {
        background: linear-gradient(135deg, #2ce69b, #1cc88a);
    }
    
    .bg-warning-gradient {
        background: linear-gradient(135deg, #ffda6a, #f6c23e);
    }
    
    .bg-info-gradient {
        background: linear-gradient(135deg, #4bd8e5, #36b9cc);
    }
    
    .alert-success-modern {
        background: rgba(28, 200, 138, 0.1);
        border-left: 4px solid var(--success);
        color: var(--dark);
        border-radius: 8px;
    }
    
    .shadow-soft {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08) !important;
    }
    
    /* Animation classes */
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="container-fluid dashboard-container fade-in">

    <!-- Page Header -->
    <div class="page-header shadow-soft">
        <div class="page-header-content d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Manajemen Knowledge Base</h1>
                <p class="page-subtitle">Kelola artikel informasi dan konten bantuan untuk pelanggan</p>
                <div class="mt-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-modern mb-0 p-0 bg-transparent">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active font-weight-bold" aria-current="page">Knowledge Base</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <a href="{{ route('admin.knowledge.create') }}" class="action-button">
                <i class="fas fa-plus"></i> Tambah Artikel Baru
            </a>
        </div>
    </div>
    
    @if(session('success'))
    <div class="alert alert-success-modern alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle mr-3 fa-2x text-success"></i>
            <div>
                <h6 class="font-weight-bold mb-1">Berhasil!</h6>
                <p class="mb-0">{{ session('success') }}</p>
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Statistics Section -->
    <div class="row stats-container">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <h6 class="stat-title">Total Artikel</h6>
                        <h2 class="stat-value">{{ $totalArticles }}</h2>
                        <p class="text-muted mb-0 small">
                            <i class="fas fa-arrow-up fa-sm"></i>
                            <span>Konten bantuan untuk pelanggan</span>
                        </p>
                    </div>
                    <div class="stat-icon bg-primary-gradient">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <a href="{{ route('admin.knowledge.articles.index') }}" class="text-primary">
                        Lihat Semua Artikel <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <h6 class="stat-title">Dipublikasikan</h6>
                        <h2 class="stat-value">{{ $publishedArticles }}</h2>
                        <p class="text-muted mb-0 small">
                            <i class="fas fa-check-circle fa-sm"></i>
                            <span>Artikel aktif dan tersedia</span>
                        </p>
                    </div>
                    <div class="stat-icon bg-success-gradient">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <a href="{{ route('admin.knowledge.articles.published') }}" class="text-success">
                        Lihat Artikel Publikasi <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <h6 class="stat-title">Draft</h6>
                        <h2 class="stat-value">{{ $draftArticles }}</h2>
                        <p class="text-muted mb-0 small">
                            <i class="fas fa-pencil-alt fa-sm"></i>
                            <span>Artikel dalam pengembangan</span>
                        </p>
                    </div>
                    <div class="stat-icon bg-warning-gradient">
                        <i class="fas fa-pencil-alt"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <a href="{{ route('admin.knowledge.articles.drafts') }}" class="text-warning">
                        Lihat Draft <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <h6 class="stat-title">Kategori</h6>
                        <h2 class="stat-value">{{ $categoriesCount }}</h2>
                        <p class="text-muted mb-0 small">
                            <i class="fas fa-folder fa-sm"></i>
                            <span>Untuk organisasi konten</span>
                        </p>
                    </div>
                    <div class="stat-icon bg-info-gradient">
                        <i class="fas fa-folder"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <a href="{{ route('admin.knowledge.categories.index') }}" class="text-info">
                        Kelola Kategori <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="row">
        <!-- Article Management -->
        <div class="col-lg-8">
            <div class="card shadow-soft mb-4 border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header py-3 bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-book mr-2"></i>Daftar Artikel Knowledge Base
                        </h6>
                        <div>
                            <span class="badge badge-pill badge-primary px-3 py-2">Total: {{ $totalArticles }}</span>
                            <span class="badge badge-pill badge-success px-3 py-2 ml-1">Dipublikasikan: {{ $publishedArticles }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search & Filter -->
                    <div class="bg-light p-4 mb-4" style="border-radius: 12px;">
                        <form method="GET" action="{{ route('admin.knowledge.index') }}">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="input-group shadow-sm" style="border-radius: 50px; overflow: hidden;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-0">
                                                <i class="fas fa-search text-primary"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control border-0" name="search" 
                                            value="{{ request('search') }}" placeholder="Cari judul artikel...">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="input-group shadow-sm" style="border-radius: 50px; overflow: hidden;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-0">
                                                <i class="fas fa-folder text-primary"></i>
                                            </span>
                                        </div>
                                        <select class="form-control border-0" name="category_id">
                                            <option value="">Semua Kategori</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="input-group shadow-sm" style="border-radius: 50px; overflow: hidden;">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-0">
                                                <i class="fas fa-filter text-primary"></i>
                                            </span>
                                        </div>
                                        <select class="form-control border-0" name="status">
                                            <option value="">Semua Status</option>
                                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <button type="submit" class="btn btn-primary btn-block px-4" style="border-radius: 50px; box-shadow: 0 4px 7px rgba(0,0,0,0.1);">
                                        <i class="fas fa-search mr-1"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Articles Table -->
                    <div class="table-responsive">
                        <table class="table" id="articlesTable" width="100%" cellspacing="0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 rounded-left">ID</th>
                                    <th class="border-0">Judul</th>
                                    <th class="border-0">Kategori</th>
                                    <th class="border-0 text-center">Dilihat</th>
                                    <th class="border-0 text-center">Status</th>
                                    <th class="border-0 text-center rounded-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articles as $article)
                                <tr class="border-bottom">
                                    <td class="align-middle">{{ $article->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center py-2">
                                            <div class="mr-3">
                                                <div style="width: 40px; height: 40px; border-radius: 10px; background-color: #e8f0fe; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-file-alt text-primary"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.knowledge.articles.show', $article->id) }}" class="text-dark font-weight-bold text-decoration-none">
                                                    {{ $article->title }}
                                                </a>
                                                @if($article->is_featured)
                                                    <span class="badge badge-primary badge-pill ml-2">Unggulan</span>
                                                @endif
                                                <div class="text-muted small mt-1">
                                                    <i class="far fa-clock mr-1"></i> {{ $article->updated_at->format('d M Y, H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        @if($article->category)
                                            <span class="badge badge-light text-primary py-2 px-3">
                                                <i class="fas fa-folder mr-1"></i> {{ $article->category->name }}
                                            </span>
                                        @else
                                            <span class="badge badge-light text-muted py-2 px-3">
                                                <i class="fas fa-question-circle mr-1"></i> Tidak ada kategori
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-light text-info py-2 px-3">
                                            <i class="fas fa-eye mr-1"></i> {{ $article->views_count }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($article->is_published)
                                            <span class="badge badge-success py-2 px-3" style="border-radius: 50px;">Dipublikasikan</span>
                                        @else
                                            <span class="badge badge-warning py-2 px-3" style="border-radius: 50px;">Draft</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.knowledge.articles.edit', $article->id) }}" class="btn btn-sm btn-outline-primary rounded-circle mr-1" data-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('knowledge.article.show', $article->slug) }}" class="btn btn-sm btn-outline-info rounded-circle mr-1" target="_blank" data-toggle="tooltip" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle" data-toggle="modal" data-target="#deleteModal-{{ $article->id }}" data-toggle="tooltip" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal-{{ $article->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Hapus
                                                </h5>
                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body p-4">
                                                <div class="text-center mb-4">
                                                    <div class="mb-3" style="height: 100px; width: 100px; background-color: #f8d7da; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                                        <i class="fas fa-trash text-danger fa-3x"></i>
                                                    </div>
                                                    <h5>Apakah Anda yakin ingin menghapus artikel ini?</h5>
                                                    <p class="text-muted">{{ $article->title }}</p>
                                                </div>
                                                
                                                <div class="alert alert-warning" role="alert">
                                                    <i class="fas fa-info-circle mr-2"></i> Tindakan ini tidak dapat dikembalikan dan akan menghapus artikel secara permanen.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                                    <i class="fas fa-times mr-1"></i> Batal
                                                </button>
                                                <form action="{{ route('admin.knowledge.articles.destroy', $article->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash mr-1"></i> Hapus Artikel
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-4">
                                            <div class="mb-3">
                                                <img src="https://cdn.iconscout.com/icon/free/png-256/free-data-not-found-1965034-1662569.png" alt="No articles" width="120" class="img-fluid">
                                            </div>
                                            <h5 class="text-muted">Belum ada artikel</h5>
                                            <p class="text-muted mb-3">
                                                Buat artikel pertama Anda untuk membantu pengguna menemukan informasi.
                                            </p>
                                            <a href="{{ route('admin.knowledge.create') }}" class="btn btn-primary" style="border-radius: 50px; padding: 8px 24px;">
                                                <i class="fas fa-plus-circle mr-1"></i> Tambah Artikel Baru
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $articles->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Popular Articles Card -->
            <div class="card shadow-soft mb-4 border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header py-3 bg-white border-0">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-star mr-2"></i>Artikel Populer
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($popularArticles->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($popularArticles as $key => $article)
                        <a href="{{ route('admin.knowledge.articles.show', $article->id) }}" class="list-group-item list-group-item-action border-0 p-3">
                            <div class="d-flex w-100 align-items-center">
                                <div class="mr-3">
                                    <div style="width: 36px; height: 36px; border-radius: 10px; background-color: rgba(78, 115, 223, 0.1); display: flex; align-items: center; justify-content: center;">
                                        <span class="font-weight-bold text-primary">{{ $key + 1 }}</span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 text-dark">{{ Str::limit($article->title, 40) }}</h6>
                                    <div class="d-flex align-items-center mt-1">
                                        <span class="badge badge-light mr-2">
                                            <i class="fas fa-eye text-info mr-1"></i> {{ $article->views_count }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="fas fa-folder text-primary mr-1"></i> {{ $article->category->name ?? 'Tidak ada kategori' }}
                                        </small>
                                    </div>
                                </div>
                                <div class="ml-auto">
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center p-5">
                        <div style="width: 80px; height: 80px; border-radius: 50%; background-color: #e8f0fe; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                            <i class="fas fa-chart-line text-primary fa-2x"></i>
                        </div>
                        <p class="text-muted mb-0">Belum ada data artikel populer.</p>
                    </div>
                    @endif
                </div>
                <div class="card-footer bg-light text-center">
                    <a href="{{ route('admin.knowledge.articles.index') }}" class="text-primary font-weight-bold">
                        Lihat Semua Artikel <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            
            <!-- Tips Card -->
            <div class="card shadow-soft mb-4 border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header py-3 bg-white border-0">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-lightbulb mr-2"></i>Tips Pengelolaan Knowledge Base
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-4 pb-4 border-bottom">
                        <div class="d-flex">
                            <div class="mr-3">
                                <div style="width: 50px; height: 50px; border-radius: 10px; background-color: rgba(78, 115, 223, 0.1); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-pen text-primary fa-lg"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="font-weight-bold">Penulisan Efektif</h6>
                                <p class="text-muted small mb-0">
                                    Gunakan judul yang jelas dan deskriptif. Strukturkan konten dengan heading dan tambahkan gambar untuk memperjelas.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4 pb-4 border-bottom">
                        <div class="d-flex">
                            <div class="mr-3">
                                <div style="width: 50px; height: 50px; border-radius: 10px; background-color: rgba(28, 200, 138, 0.1); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-tags text-success fa-lg"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="font-weight-bold">Kategorisasi</h6>
                                <p class="text-muted small mb-0">
                                    Gunakan kategori dan tag yang tepat untuk memudahkan pencarian. Pastikan setiap artikel masuk dalam kategori yang sesuai.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="d-flex">
                            <div class="mr-3">
                                <div style="width: 50px; height: 50px; border-radius: 10px; background-color: rgba(54, 185, 204, 0.1); display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-sync-alt text-info fa-lg"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="font-weight-bold">Perbarui Rutin</h6>
                                <p class="text-muted small mb-0">
                                    Tinjau dan perbarui artikel secara berkala untuk memastikan informasi tetap akurat dan relevan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow-soft border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header py-3 bg-white border-0">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.knowledge.create') }}" class="btn btn-light btn-block py-3 px-2 h-100 text-left">
                                <div class="d-flex flex-column align-items-center text-primary">
                                    <i class="fas fa-plus-circle fa-2x mb-2"></i>
                                    <span class="small font-weight-bold">Tambah Artikel</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-6 mb-3">
                            <a href="{{ route('admin.knowledge.categories.index') }}" class="btn btn-light btn-block py-3 px-2 h-100 text-left">
                                <div class="d-flex flex-column align-items-center text-success">
                                    <i class="fas fa-folder-plus fa-2x mb-2"></i>
                                    <span class="small font-weight-bold">Kelola Kategori</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('admin.knowledge.articles.featured') }}" class="btn btn-light btn-block py-3 px-2 h-100 text-left">
                                <div class="d-flex flex-column align-items-center text-warning">
                                    <i class="fas fa-star fa-2x mb-2"></i>
                                    <span class="small font-weight-bold">Artikel Unggulan</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="{{ route('knowledge.home') }}" target="_blank" class="btn btn-light btn-block py-3 px-2 h-100 text-left">
                                <div class="d-flex flex-column align-items-center text-info">
                                    <i class="fas fa-external-link-alt fa-2x mb-2"></i>
                                    <span class="small font-weight-bold">Lihat Knowledge Base</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Datatable with custom options
    $('#articlesTable').DataTable({
        "paging": false,
        "ordering": true,
        "info": false,
        "searching": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        },
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [5] }
        ]
    });
    
    // Add smooth scroll to links
    $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(event) {
        if (
            location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && 
            location.hostname == this.hostname
        ) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 72
                }, 1000);
            }
        }
    });
});
</script>
@endsection 