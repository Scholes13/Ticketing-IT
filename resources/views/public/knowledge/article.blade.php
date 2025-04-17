@extends('layouts.public')

@section('title', $article->title . ' - Knowledge Base')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Article Content -->
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('knowledge.home') }}">Pusat Pengetahuan</a></li>
                    @if($article->category)
                        <li class="breadcrumb-item"><a href="{{ route('knowledge.category', $article->category->slug) }}">{{ $article->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $article->title }}</li>
                </ol>
            </nav>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body p-md-5">
                    <h1 class="mb-4">{{ $article->title }}</h1>
                    
                    <div class="d-flex align-items-center text-muted mb-4">
                        <span class="me-3">
                            <i class="far fa-calendar-alt me-1"></i> 
                            {{ $article->published_at->format('d M Y') }}
                        </span>
                        <span class="me-3">
                            <i class="far fa-eye me-1"></i> 
                            {{ $article->views_count }} kali dilihat
                        </span>
                        @if($article->author)
                            <span>
                                <i class="far fa-user me-1"></i> 
                                {{ $article->author->name }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="article-content mb-4">
                        {!! $article->content !!}
                    </div>
                    
                    @if(!empty($article->tags))
                        <div class="mt-4 pt-3 border-top">
                            <h5>Tags:</h5>
                            <div>
                                @foreach($article->tags as $tag)
                                    <span class="badge bg-light text-dark me-2 mb-2 py-2 px-3">{{ $tag }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Was this helpful -->
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4 text-center">
                    <h5 class="mb-3">Apakah artikel ini membantu Anda?</h5>
                    <div class="d-flex justify-content-center">
                        <a href="{{ route('public.create.ticket') }}" class="btn btn-outline-danger me-3">
                            <i class="far fa-thumbs-down me-2"></i> Tidak, saya masih perlu bantuan
                        </a>
                        <a href="{{ route('knowledge.home') }}" class="btn btn-outline-success">
                            <i class="far fa-thumbs-up me-2"></i> Ya, terima kasih!
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Related Articles -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Artikel Terkait</h4>
                </div>
                <div class="card-body">
                    @if($relatedArticles->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($relatedArticles as $relatedArticle)
                                <a href="{{ route('knowledge.article', ['categorySlug' => $relatedArticle->category ? $relatedArticle->category->slug : 'uncategorized', 'articleSlug' => $relatedArticle->slug]) }}" class="list-group-item list-group-item-action py-3">
                                    <h6 class="mb-1">{{ $relatedArticle->title }}</h6>
                                    @if($relatedArticle->meta_description)
                                        <p class="mb-1 small text-muted">{{ Str::limit($relatedArticle->meta_description, 80) }}</p>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Tidak ada artikel terkait.</p>
                    @endif
                </div>
            </div>
            
            <!-- Need Support -->
            <div class="card shadow-sm mb-4 bg-primary text-white">
                <div class="card-body p-4">
                    <h4 class="mb-3">Masih Perlu Bantuan?</h4>
                    <p>Jika solusi yang Anda cari tidak ada di artikel ini, silakan buat tiket untuk mendapatkan bantuan langsung dari tim IT kami.</p>
                    <a href="{{ route('public.create.ticket') }}" class="btn btn-light">Buat Tiket Bantuan</a>
                </div>
            </div>
            
            <!-- Search -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Cari Solusi Lain</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('knowledge.search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="query" placeholder="Cari artikel..." required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .article-content {
        font-size: 1.1rem;
        line-height: 1.7;
    }
    
    .article-content h2,
    .article-content h3,
    .article-content h4 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 0.375rem;
        margin: 1.5rem 0;
    }
    
    .article-content pre {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.375rem;
        margin: 1.5rem 0;
    }
    
    .article-content ul,
    .article-content ol {
        margin-bottom: 1.5rem;
    }
</style>
@endsection 