@extends('layouts.public')

@section('title', $category->name . ' - Knowledge Base')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('knowledge.home') }}">Pusat Pengetahuan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
            
            <div class="d-flex align-items-center mb-4">
                @if($category->icon)
                    <i class="fas fa-{{ $category->icon }} fa-2x text-primary me-3"></i>
                @else
                    <i class="fas fa-folder fa-2x text-primary me-3"></i>
                @endif
                <h1 class="mb-0">{{ $category->name }}</h1>
            </div>
            
            @if($category->description)
                <p class="lead text-muted mb-4">{{ $category->description }}</p>
            @endif
            
            <!-- Search within category -->
            <div class="card shadow-sm mb-5">
                <div class="card-body p-3">
                    <form action="{{ route('knowledge.search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="query" placeholder="Cari dalam kategori {{ $category->name }}..." aria-label="Search">
                            <input type="hidden" name="category_id" value="{{ $category->id }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Child Categories -->
    @if($childCategories->count() > 0)
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="mb-4">Sub Kategori</h2>
            </div>
            
            @foreach($childCategories as $childCategory)
                <div class="col-md-4 mb-4">
                    <a href="{{ route('knowledge.category', $childCategory->slug) }}" class="text-decoration-none">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    @if($childCategory->icon)
                                        <i class="fas fa-{{ $childCategory->icon }} fa-2x text-primary me-3"></i>
                                    @else
                                        <i class="fas fa-folder fa-2x text-primary me-3"></i>
                                    @endif
                                    <h3 class="mb-0">{{ $childCategory->name }}</h3>
                                </div>
                                
                                @if($childCategory->description)
                                    <p class="text-muted">{{ Str::limit($childCategory->description, 100) }}</p>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="text-primary">Lihat artikel</span>
                                    <span class="badge bg-light text-dark">{{ $childCategory->articles_count }} artikel</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
    
    <!-- Articles in this category -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">Artikel dalam kategori ini</h2>
        </div>
        
        @if($articles->count() > 0)
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="list-group list-group-flush">
                        @foreach($articles as $article)
                            <a href="{{ route('knowledge.article', ['categorySlug' => $category->slug, 'articleSlug' => $article->slug]) }}" class="list-group-item list-group-item-action p-4">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <h4 class="mb-1">{{ $article->title }}</h4>
                                    <span class="badge bg-light text-dark">{{ $article->views_count }} dilihat</span>
                                </div>
                                
                                @if($article->meta_description)
                                    <p class="mb-2 text-muted">{{ $article->meta_description }}</p>
                                @endif
                                
                                <div class="d-flex text-muted small">
                                    <span class="me-3">
                                        <i class="far fa-calendar-alt me-1"></i>
                                        {{ $article->published_at->format('d M Y') }}
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
                    {{ $articles->links() }}
                </div>
            </div>
        @else
            <div class="col-12">
                <div class="alert alert-info">
                    Belum ada artikel yang dipublikasikan dalam kategori ini.
                </div>
            </div>
        @endif
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