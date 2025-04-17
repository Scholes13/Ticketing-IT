@extends('layouts.admin')

@section('title', 'Detail Artikel Knowledge Base')

@section('styles')
<style>
    .article-content {
        min-height: 300px;
        border: 1px solid #e3e6f0;
        border-radius: .35rem;
        padding: 1.25rem;
        background-color: #fff;
    }
    .article-metadata .badge {
        font-size: 85%;
    }
    .article-tag {
        display: inline-block;
        background-color: #f8f9fc;
        border: 1px solid #e3e6f0;
        color: #4e73df;
        padding: 2px 8px;
        margin-right: 5px;
        margin-bottom: 5px;
        border-radius: 3px;
        font-size: 85%;
    }
    .related-article {
        border-bottom: 1px solid #e3e6f0;
        padding: 8px 0;
    }
    .related-article:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $article->title }}</h1>
        
        <div>
            <a href="{{ route('admin.knowledge.edit', $article->id) }}" class="btn btn-sm btn-primary">
                <i class="fas fa-edit"></i> Edit Artikel
            </a>
            <a href="{{ route('knowledge.show', $article->slug) }}" class="btn btn-sm btn-info" target="_blank">
                <i class="fas fa-eye"></i> Lihat di Knowledge Base
            </a>
        </div>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.index') }}">Knowledge Base</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Artikel</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Article Content -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Konten Artikel</h6>
                    <div class="article-metadata">
                        @if($article->is_published)
                            <span class="badge badge-success">Dipublikasikan</span>
                        @else
                            <span class="badge badge-secondary">Draft</span>
                        @endif
                        
                        @if($article->category)
                            <span class="badge badge-info">{{ $article->category->name }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($article->meta_description)
                    <div class="mb-4 p-3 bg-light rounded">
                        <strong>Deskripsi Singkat:</strong> {{ $article->meta_description }}
                    </div>
                    @endif
                    
                    <div class="article-content">
                        {!! $article->content !!}
                    </div>
                    
                    @if($article->tags)
                    <div class="mt-4">
                        <strong>Tag:</strong>
                        <div class="mt-2">
                            @foreach(explode(',', $article->tags) as $tag)
                                <span class="article-tag">{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            @if(count($relatedArticles) > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Artikel Terkait</h6>
                </div>
                <div class="card-body">
                    @foreach($relatedArticles as $related)
                    <div class="related-article">
                        <a href="{{ route('admin.knowledge.show', $related->id) }}">{{ $related->title }}</a>
                        <div class="small text-muted">
                            @if($related->category)
                                <span>{{ $related->category->name }}</span> | 
                            @endif
                            <span>{{ $related->views_count }} views</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <!-- Article Details and Stats -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Artikel</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-5 font-weight-bold">ID:</div>
                        <div class="col-md-7">{{ $article->id }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 font-weight-bold">Slug:</div>
                        <div class="col-md-7">{{ $article->slug }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 font-weight-bold">Kategori:</div>
                        <div class="col-md-7">
                            @if($article->category)
                                <a href="{{ route('admin.knowledge.categories.show', $article->category->id) }}">
                                    {{ $article->category->name }}
                                </a>
                            @else
                                <span class="text-muted">Tidak ada</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 font-weight-bold">Status:</div>
                        <div class="col-md-7">
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
                    <div class="row mb-3">
                        <div class="col-md-5 font-weight-bold">Dibuat:</div>
                        <div class="col-md-7">{{ $article->created_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 font-weight-bold">Diperbarui:</div>
                        <div class="col-md-7">{{ $article->updated_at->format('d M Y H:i') }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5 font-weight-bold">Penulis:</div>
                        <div class="col-md-7">{{ $article->author->name ?? 'Tidak Ada' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistik</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Views
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($article->views_count) }}
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Tiket Terkait
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($article->tickets_count ?? 0) }}
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($viewsChart))
                    <div class="mt-4">
                        <canvas id="viewsChart"></canvas>
                    </div>
                    @endif
                </div>
            </div>
            
            @if($article->tickets_count > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tiket Terkait</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($relatedTickets as $ticket)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('admin.tickets.show', $ticket->id) }}">{{ $ticket->title }}</a>
                                <div class="small text-muted">{{ $ticket->created_at->format('d M Y') }}</div>
                            </div>
                            <span class="badge badge-{{ $ticket->status_color }} badge-pill">{{ $ticket->status_text }}</span>
                        </li>
                        @endforeach
                    </ul>
                    
                    @if(count($relatedTickets) < $article->tickets_count)
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.tickets.index', ['knowledge_article_id' => $article->id]) }}" class="btn btn-sm btn-primary">
                            Lihat Semua Tiket
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tindakan</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.knowledge.edit', $article->id) }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit"></i> Edit Artikel
                    </a>
                    <a href="{{ route('knowledge.show', $article->slug) }}" class="btn btn-info btn-block mb-2" target="_blank">
                        <i class="fas fa-eye"></i> Lihat di Knowledge Base
                    </a>
                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteModal">
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
@if(isset($viewsChart))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('viewsChart').getContext('2d');
    const viewsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($viewsChart['labels']) !!},
            datasets: [{
                label: 'Views',
                data: {!! json_encode($viewsChart['data']) !!},
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointHoverRadius: 3,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 7
                    }
                },
                y: {
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10
                    },
                    grid: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }
            }
        }
    });
});
</script>
@endif
@endsection 