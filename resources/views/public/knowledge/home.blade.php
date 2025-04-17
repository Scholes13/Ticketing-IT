@extends('layouts.public')

@section('title', 'Knowledge Base - IT Support')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-4 mb-4">Pusat Pengetahuan IT Support</h1>
            <p class="lead">Temukan solusi untuk masalah umum IT atau panduan langkah demi langkah.</p>
            
            <!-- Search Box -->
            <div class="card shadow-sm mb-5">
                <div class="card-body p-4">
                    <form action="{{ route('knowledge.search') }}" method="GET">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control" name="query" placeholder="Cari solusi atau panduan..." aria-label="Search" required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Categories -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">Kategori</h2>
        </div>
        
        @forelse($categories as $category)
            <div class="col-md-4 mb-4">
                <a href="{{ route('knowledge.category', $category->slug) }}" class="text-decoration-none">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                @if($category->icon)
                                    <i class="fas fa-{{ $category->icon }} fa-2x text-primary me-3"></i>
                                @else
                                    <i class="fas fa-folder fa-2x text-primary me-3"></i>
                                @endif
                                <h3 class="mb-0">{{ $category->name }}</h3>
                            </div>
                            
                            @if($category->description)
                                <p class="text-muted">{{ $category->description }}</p>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="text-primary">Lihat artikel</span>
                                <span class="badge bg-light text-dark">{{ $category->articles_count }} artikel</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Belum ada kategori yang ditambahkan ke knowledge base.
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Popular Articles -->
    <div class="row mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h3 class="mb-0">Artikel Populer</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($popularArticles as $article)
                            <a href="{{ route('knowledge.article', ['categorySlug' => $article->category ? $article->category->slug : 'uncategorized', 'articleSlug' => $article->slug]) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $article->title }}</h5>
                                    <small class="text-muted">{{ $article->views_count }} dilihat</small>
                                </div>
                                @if($article->meta_description)
                                    <p class="mb-1 text-muted">{{ Str::limit($article->meta_description, 100) }}</p>
                                @endif
                            </a>
                        @empty
                            <div class="list-group-item">
                                <p class="mb-0">Belum ada artikel yang dilihat.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Articles -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h3 class="mb-0">Artikel Terbaru</h3>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentArticles as $article)
                            <a href="{{ route('knowledge.article', ['categorySlug' => $article->category ? $article->category->slug : 'uncategorized', 'articleSlug' => $article->slug]) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $article->title }}</h5>
                                    <small class="text-muted">{{ $article->published_at->diffForHumans() }}</small>
                                </div>
                                @if($article->meta_description)
                                    <p class="mb-1 text-muted">{{ Str::limit($article->meta_description, 100) }}</p>
                                @endif
                            </a>
                        @empty
                            <div class="list-group-item">
                                <p class="mb-0">Belum ada artikel yang dipublikasikan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Need Support -->
    <div class="row">
        <div class="col-md-8 mx-auto text-center">
            <div class="card bg-primary text-white shadow">
                <div class="card-body p-4">
                    <h3>Tidak menemukan apa yang Anda cari?</h3>
                    <p>Jika Anda tidak menemukan solusi dari permasalahan IT Anda, buat tiket untuk mendapatkan bantuan dari tim IT kami.</p>
                    <a href="{{ route('public.create.ticket') }}" class="btn btn-light">Buat Tiket Bantuan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 