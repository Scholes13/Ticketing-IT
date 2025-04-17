@extends('layouts.admin')

@section('title', 'Detail Artikel Knowledge Base')

@section('styles')
<style>
    .article-content img {
        max-width: 100%;
        height: auto;
    }
    .badge-tag {
        font-size: 0.8rem;
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
    }
    .metadata-item {
        margin-bottom: 0.75rem;
    }
    .metadata-label {
        font-weight: bold;
        color: #555;
    }
    .status-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $article->title }}</h1>
        <div>
            <a href="{{ route('admin.knowledge.articles.edit', $article->id) }}" class="btn btn-primary">
                <i class="fas fa-edit mr-1"></i> Edit Artikel
            </a>
            @if($article->is_published)
            <a href="{{ route('knowledge.article.show', $article->slug) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-external-link-alt mr-1"></i> Lihat di Knowledge Base
            </a>
            @endif
        </div>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.index') }}">Knowledge Base</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.articles.index') }}">Artikel</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($article->title, 30) }}</li>
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

    <!-- Article Overview & Stats -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Status</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($article->is_published)
                                <span class="badge badge-success status-badge">Dipublikasikan</span>
                                @else
                                <span class="badge badge-secondary status-badge">Draft</span>
                                @endif
                                @if($article->is_featured)
                                <span class="badge badge-warning status-badge">Unggulan</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bookmark fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tampilan</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($article->view_count) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Tiket Terkait</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $article->tickets->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Terakhir Diupdate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $article->updated_at->format('d M Y') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Article Content -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Konten Artikel</h6>
                </div>
                <div class="card-body">
                    @if($article->excerpt)
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="font-weight-bold">Ringkasan:</h6>
                            <p class="mb-0">{{ $article->excerpt }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="article-content">
                        {!! $article->content !!}
                    </div>
                </div>
            </div>

            <!-- Related Tickets -->
            @if($article->tickets->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tiket Terkait ({{ $article->tickets->count() }})</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Subjek</th>
                                    <th>Status</th>
                                    <th>Prioritas</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($article->tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->id }}</td>
                                    <td>{{ $ticket->subject }}</td>
                                    <td>
                                        <span class="badge badge-{{ $ticket->status_color }}">
                                            {{ $ticket->status_name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $ticket->priority_color }}">
                                            {{ $ticket->priority_name }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.tickets.show', $ticket->id) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Related Articles -->
            @if(count($article->relatedArticles) > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Artikel Terkait ({{ count($article->relatedArticles) }})</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($article->relatedArticles as $related)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title font-weight-bold mb-2">
                                        <a href="{{ route('admin.knowledge.articles.show', $related->id) }}">
                                            {{ $related->title }}
                                        </a>
                                    </h6>
                                    <p class="card-text small text-muted">{{ Str::limit($related->excerpt, 100) }}</p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <small class="text-muted">
                                        <i class="fas fa-eye mr-1"></i> {{ $related->view_count }} tampilan
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Article Metadata -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Artikel</h6>
                </div>
                <div class="card-body">
                    <div class="metadata-item">
                        <div class="metadata-label">Kategori:</div>
                        @if($article->category)
                        <div>
                            <a href="{{ route('admin.knowledge.categories.show', $article->category->id) }}">
                                <i class="{{ $article->category->icon ?? 'fas fa-folder' }} mr-1"></i>
                                {{ $article->category->name }}
                            </a>
                        </div>
                        @else
                        <div class="text-muted">-</div>
                        @endif
                    </div>

                    <div class="metadata-item">
                        <div class="metadata-label">Slug:</div>
                        <div class="text-monospace">{{ $article->slug }}</div>
                    </div>

                    <div class="metadata-item">
                        <div class="metadata-label">Tags:</div>
                        <div>
                            @if(count($article->tags) > 0)
                                @foreach($article->tags as $tag)
                                <span class="badge badge-secondary badge-tag">{{ $tag }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">Tidak ada tag</span>
                            @endif
                        </div>
                    </div>

                    <div class="metadata-item">
                        <div class="metadata-label">Penulis:</div>
                        <div>{{ $article->author->name ?? 'Tidak diketahui' }}</div>
                    </div>

                    <div class="metadata-item">
                        <div class="metadata-label">Tanggal Dibuat:</div>
                        <div>{{ $article->created_at->format('d M Y H:i') }}</div>
                    </div>

                    <div class="metadata-item">
                        <div class="metadata-label">Terakhir Diupdate:</div>
                        <div>{{ $article->updated_at->format('d M Y H:i') }}</div>
                    </div>

                    <div class="metadata-item">
                        <div class="metadata-label">Urutan:</div>
                        <div>{{ $article->order ?? 'Default' }}</div>
                    </div>

                    <div class="metadata-item">
                        <div class="metadata-label">Seo Title:</div>
                        <div>{{ $article->seo_title ?? $article->title }}</div>
                    </div>

                    <div class="metadata-item">
                        <div class="metadata-label">Seo Description:</div>
                        <div>{{ $article->seo_description ?? Str::limit($article->excerpt, 160) }}</div>
                    </div>
                </div>
            </div>

            <!-- Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.knowledge.articles.edit', $article->id) }}" class="btn btn-primary btn-block mb-2">
                            <i class="fas fa-edit mr-1"></i> Edit Artikel
                        </a>
                        
                        @if($article->is_published)
                        <form action="{{ route('admin.knowledge.articles.unpublish', $article->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning btn-block">
                                <i class="fas fa-eye-slash mr-1"></i> Ubah ke Draft
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.knowledge.articles.publish', $article->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check-circle mr-1"></i> Publikasikan
                            </button>
                        </form>
                        @endif
                        
                        @if($article->is_featured)
                        <form action="{{ route('admin.knowledge.articles.unfeature', $article->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-warning btn-block">
                                <i class="far fa-star mr-1"></i> Hapus dari Unggulan
                            </button>
                        </form>
                        @else
                        <form action="{{ route('admin.knowledge.articles.feature', $article->id) }}" method="POST" class="mb-2">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-star mr-1"></i> Jadikan Unggulan
                            </button>
                        </form>
                        @endif
                        
                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash mr-1"></i> Hapus Artikel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Article Modal -->
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
                <p>Anda yakin ingin menghapus artikel "<strong>{{ $article->title }}</strong>"?</p>
                @if($article->tickets->count() > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Artikel ini terkait dengan {{ $article->tickets->count() }} tiket.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form action="{{ route('admin.knowledge.articles.destroy', $article->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 