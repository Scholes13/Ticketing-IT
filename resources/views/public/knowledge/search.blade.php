@extends('layouts.public')

@section('title', 'Hasil Pencarian: ' . $query . ' - Knowledge Base')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('knowledge.home') }}">Pusat Pengetahuan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hasil Pencarian</li>
                </ol>
            </nav>
            
            <h1 class="mb-4">Hasil Pencarian: "{{ $query }}"</h1>
            
            <!-- Search box -->
            <div class="card shadow-sm mb-5">
                <div class="card-body p-3">
                    <form action="{{ route('knowledge.search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="query" value="{{ $query }}" placeholder="Cari artikel..." aria-label="Search">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Search Results -->
    <div class="row mb-5">
        <div class="col-12">
            @if($articles->count() > 0)
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">{{ $articles->total() }} hasil ditemukan</h2>
                </div>
                
                <div class="card shadow-sm">
                    <div class="list-group list-group-flush">
                        @foreach($articles as $article)
                            <a href="{{ route('knowledge.article', ['categorySlug' => $article->category ? $article->category->slug : 'uncategorized', 'articleSlug' => $article->slug]) }}" class="list-group-item list-group-item-action p-4">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <h4 class="mb-1">{{ $article->title }}</h4>
                                    @if($article->category)
                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                    @endif
                                </div>
                                
                                @if($article->meta_description)
                                    <p class="mb-2 text-muted">{{ $article->meta_description }}</p>
                                @endif
                                
                                <div class="d-flex text-muted small">
                                    <span class="me-3">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $article->published_at->format('d M Y') }}
                                    </span>
                                    <span class="me-3">
                                        <i class="far fa-eye me-1"></i>
                                        {{ $article->views_count }} dilihat
                                    </span>
                                    
                                    @if(!empty($article->tags))
                                        <span>
                                            <i class="fas fa-tags me-1"></i>
                                            {{ implode(', ', array_slice($article->tags, 0, 3)) }}
                                            @if(count($article->tags) > 3)
                                                ...
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                
                <div class="mt-4">
                    {{ $articles->appends(['query' => $query])->links() }}
                </div>
            @else
                <div class="card shadow-sm p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h3>Tidak ditemukan hasil untuk "{{ $query }}"</h3>
                        <p class="text-muted">Silakan coba kata kunci lain atau istilah yang lebih umum</p>
                    </div>
                    
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="mb-3">Tips pencarian:</h5>
                                    <ul class="text-start mb-0">
                                        <li>Periksa ejaan kata kunci Anda</li>
                                        <li>Gunakan kata-kata yang lebih umum</li>
                                        <li>Coba cari dengan satu kata kunci saja</li>
                                        <li>Jelajahi kategori di <a href="{{ route('knowledge.home') }}">halaman utama</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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