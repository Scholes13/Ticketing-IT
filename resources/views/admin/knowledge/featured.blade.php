@extends('layouts.admin')

@section('title', 'Artikel Unggulan - Knowledge Base')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Artikel Unggulan</h1>
        <a href="{{ route('admin.knowledge.articles.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Artikel Baru
        </a>
    </div>
    
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.index') }}">Knowledge Base</a></li>
            <li class="breadcrumb-item active" aria-current="page">Artikel Unggulan</li>
        </ol>
    </nav>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Artikel</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalArticles }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center p-2">
                    <a href="{{ route('admin.knowledge.articles.index') }}" class="small-box-footer">
                        Lihat Semua <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Dipublikasikan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $publishedArticles }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center p-2">
                    <a href="{{ route('admin.knowledge.articles.published') }}" class="small-box-footer">
                        Lihat Artikel Publikasi <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Draft</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $draftArticles }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pencil-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center p-2">
                    <a href="{{ route('admin.knowledge.articles.drafts') }}" class="small-box-footer">
                        Lihat Draft <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Unggulan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $featuredArticles }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center p-2">
                    <a href="{{ route('admin.knowledge.articles.featured') }}" class="small-box-footer">
                        Anda sedang melihat ini <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Artikel -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Artikel Unggulan</h6>
            <div>
                <span class="badge badge-primary">Total: {{ $featuredArticles }}</span>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif

            <!-- Filter Form -->
            <form method="GET" action="{{ route('admin.knowledge.articles.featured') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Judul</span>
                            </div>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari judul...">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Kategori</span>
                            </div>
                            <select class="form-control" name="category_id">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Status</span>
                            </div>
                            <select class="form-control" name="status">
                                <option value="">Semua Status</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Dipublikasikan</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search mr-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

            <!-- Articles Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="articlesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th width="70">Dilihat</th>
                            <th width="100">Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                        <tr>
                            <td>{{ $article->id }}</td>
                            <td>
                                <a href="{{ route('admin.knowledge.articles.show', $article->id) }}">
                                    {{ $article->title }}
                                </a>
                                <span class="badge badge-primary ml-1">Unggulan</span>
                            </td>
                            <td>
                                @if($article->category)
                                    <a href="{{ route('admin.knowledge.categories.show', $article->category->id) }}">
                                        {{ $article->category->name }}
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada kategori</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $article->views_count }}</td>
                            <td>
                                @if($article->is_published)
                                    <span class="badge badge-success">Dipublikasikan</span>
                                @else
                                    <span class="badge badge-warning">Draft</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.knowledge.articles.edit', $article->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($article->is_published)
                                <a href="{{ route('knowledge.article.show', $article->slug) }}" class="btn btn-sm btn-info" target="_blank" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                <button type="button" class="btn btn-sm btn-warning" title="Hapus dari Unggulan" data-toggle="modal" data-target="#unfeatureModal-{{ $article->id }}">
                                    <i class="fas fa-star-half-alt"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Unfeature Modal -->
                        <div class="modal fade" id="unfeatureModal-{{ $article->id }}" tabindex="-1" role="dialog" aria-labelledby="unfeatureModalLabel-{{ $article->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="unfeatureModalLabel-{{ $article->id }}">Konfirmasi Hapus dari Unggulan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus artikel <strong>{{ $article->title }}</strong> dari daftar artikel unggulan?</p>
                                        <p>Artikel ini tidak akan lagi ditampilkan di bagian unggulan Knowledge Base.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <form action="{{ route('admin.knowledge.articles.unfeature', $article->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-warning">Hapus dari Unggulan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                <p class="my-3 text-muted">Belum ada artikel unggulan.</p>
                                <p>Tandai artikel sebagai unggulan untuk menampilkannya di halaman depan Knowledge Base.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $articles->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
    
    <!-- Tips Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tips Artikel Unggulan</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="font-weight-bold"><i class="fas fa-lightbulb text-warning mr-2"></i>Apa itu Artikel Unggulan?</h6>
                    <p>Artikel unggulan adalah konten yang ditampilkan di bagian atas halaman utama Knowledge Base. Ini digunakan untuk:</p>
                    <ul class="mb-0">
                        <li>Menyoroti informasi penting atau konten terbaru</li>
                        <li>Memberikan akses cepat ke artikel yang paling sering dibutuhkan</li>
                        <li>Mempromosikan konten dengan kualitas tinggi</li>
                        <li>Mengarahkan pengguna ke informasi penting</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="font-weight-bold"><i class="fas fa-star text-primary mr-2"></i>Kriteria Artikel Unggulan</h6>
                    <p>Tidak semua artikel harus ditandai sebagai unggulan. Pertimbangkan kriteria berikut:</p>
                    <ul class="mb-0">
                        <li>Artikel dengan kualitas dan kelengkapan terbaik</li>
                        <li>Konten yang relevan untuk sebagian besar pengguna</li>
                        <li>Topik atau masalah yang sering ditanyakan pengguna</li>
                        <li>Batasi jumlah artikel unggulan (3-5 artikel) untuk efektivitas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#articlesTable').DataTable({
            "paging": false,
            "searching": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "order": [[ 0, "desc" ]]
        });
    });
</script>
@endsection 