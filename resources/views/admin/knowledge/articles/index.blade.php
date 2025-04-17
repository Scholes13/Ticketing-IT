@extends('layouts.admin')

@section('title', 'Manajemen Artikel Knowledge Base')

@section('styles')
<style>
    .status-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    .tag-badge {
        font-size: 0.75rem;
        font-weight: normal;
        margin-right: 3px;
        margin-bottom: 3px;
        display: inline-block;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Artikel Knowledge Base</h1>
        <a href="{{ route('admin.knowledge.articles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle mr-1"></i> Tambah Artikel Baru
        </a>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.index') }}">Knowledge Base</a></li>
            <li class="breadcrumb-item active" aria-current="page">Artikel</li>
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

    <!-- Search & Filter -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Artikel</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.knowledge.articles.index') }}" method="GET" class="mb-0">
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="form-group mb-0">
                            <label for="search" class="small">Cari Judul</label>
                            <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Masukkan kata kunci...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="form-group mb-0">
                            <label for="category" class="small">Kategori</label>
                            <select class="form-control" id="category" name="category">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    @if($category->parent_id === null)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @foreach($category->children as $child)
                                            <option value="{{ $child->id }}" {{ request('category') == $child->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;└ {{ $child->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <div class="form-group mb-0">
                            <label for="status" class="small">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="featured" {{ request('status') == 'featured' ? 'selected' : '' }}>Unggulan</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search mr-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Articles Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Artikel</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                    aria-labelledby="dropdownMenuLink">
                    <div class="dropdown-header">Opsi:</div>
                    <a class="dropdown-item" href="{{ route('admin.knowledge.articles.index', ['sort' => 'newest']) }}">
                        <i class="fas fa-sort-amount-down mr-1"></i> Terbaru
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.knowledge.articles.index', ['sort' => 'oldest']) }}">
                        <i class="fas fa-sort-amount-up mr-1"></i> Terlama
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.knowledge.articles.index', ['sort' => 'most_viewed']) }}">
                        <i class="fas fa-eye mr-1"></i> Paling Banyak Dilihat
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('admin.knowledge.articles.index') }}">
                        <i class="fas fa-sync-alt mr-1"></i> Reset Filter
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(count($articles) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="35%">Judul</th>
                            <th width="15%">Kategori</th>
                            <th width="8%">Dilihat</th>
                            <th width="10%">Status</th>
                            <th width="12%">Diupdate</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($articles as $article)
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($article->is_featured)
                                    <span class="mr-2" data-toggle="tooltip" data-placement="top" title="Artikel Unggulan">
                                        <i class="fas fa-star text-warning"></i>
                                    </span>
                                    @endif
                                    <div>
                                        <a href="{{ route('admin.knowledge.articles.show', $article->id) }}" class="font-weight-bold">
                                            {{ $article->title }}
                                        </a>
                                        @if(count($article->tags) > 0)
                                        <div class="mt-1">
                                            @foreach($article->tags as $tag)
                                            <span class="badge badge-secondary tag-badge">{{ $tag }}</span>
                                            @endforeach
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($article->category)
                                <a href="{{ route('admin.knowledge.categories.show', $article->category->id) }}">
                                    {{ $article->category->name }}
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $article->view_count }}</td>
                            <td>
                                @if($article->is_published)
                                <span class="badge badge-success status-badge">Dipublikasikan</span>
                                @else
                                <span class="badge badge-secondary status-badge">Draft</span>
                                @endif
                            </td>
                            <td>{{ $article->updated_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.knowledge.articles.edit', $article->id) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($article->is_published)
                                <a href="{{ route('knowledge.article.show', $article->slug) }}" class="btn btn-sm btn-info" target="_blank" data-toggle="tooltip" data-placement="top" title="Lihat di Knowledge Base">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                @endif
                                <a href="{{ route('admin.knowledge.articles.show', $article->id) }}" class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal{{ $article->id }}" data-toggle="tooltip" data-placement="top" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $article->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $article->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $article->id }}">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Anda yakin ingin menghapus artikel "<strong>{{ $article->title }}</strong>"?</p>
                                                @if($article->tickets_count > 0)
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Artikel ini terkait dengan {{ $article->tickets_count }} tiket.
                                                </div>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.knowledge.articles.destroy', $article->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $articles->withQueryString()->links() }}
            </div>
            @else
            <div class="text-center py-4">
                <img src="{{ asset('img/illustrations/empty.svg') }}" alt="Tidak ada artikel" class="img-fluid mb-3" style="max-height: 150px;">
                <h5>Belum ada artikel</h5>
                <p class="text-muted">
                    Belum ada artikel yang tersedia. Mulailah dengan menambahkan artikel pertama.
                </p>
                <a href="{{ route('admin.knowledge.articles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle mr-1"></i> Tambah Artikel Baru
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Tips Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tips Pengelolaan Artikel</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="font-weight-bold"><i class="fas fa-lightbulb text-warning mr-2"></i>Praktik Terbaik</h5>
                    <ul class="mb-0">
                        <li>Gunakan judul yang jelas dan deskriptif</li>
                        <li>Strukturkan konten dengan heading yang baik</li>
                        <li>Tambahkan gambar atau diagram untuk memperjelas</li>
                        <li>Gunakan kategori yang tepat untuk memudahkan pencarian</li>
                        <li>Tambahkan tag yang relevan untuk meningkatkan discoverability</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5 class="font-weight-bold"><i class="fas fa-chart-line text-info mr-2"></i>Meningkatkan Penggunaan</h5>
                    <ul class="mb-0">
                        <li>Perbarui artikel secara berkala agar tetap relevan</li>
                        <li>Kaitkan artikel dengan artikel terkait lainnya</li>
                        <li>Gunakan fitur "Artikel Unggulan" untuk konten penting</li>
                        <li>Pantau statistik view untuk melihat artikel popular</li>
                        <li>Tambahkan artikel berdasarkan tiket yang sering dibuat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection 