@extends('layouts.admin')

@section('title', 'Detail Kategori Knowledge Base')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Kategori: {{ $category->name }}</h1>
        <div>
            <a href="{{ route('admin.knowledge.categories.edit', $category->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit fa-sm"></i> Edit Kategori
            </a>
            <a href="{{ route('knowledge.category', $category->slug) }}" class="btn btn-success btn-sm" target="_blank">
                <i class="fas fa-external-link-alt fa-sm"></i> Lihat di Knowledge Base
            </a>
        </div>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.index') }}">Knowledge Base</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.categories.index') }}">Kategori</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Artikel</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $category->articles_count ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Sub-Kategori</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $category->children_count ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Status</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @if($category->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Non-aktif</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-toggle-on fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Kategori</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('admin.knowledge.categories.edit', $category->id) }}">
                                <i class="fas fa-edit fa-sm fa-fw mr-2 text-gray-400"></i> Edit Kategori
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#deleteModal">
                                <i class="fas fa-trash fa-sm fa-fw mr-2 text-gray-400"></i> Hapus Kategori
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="mb-2">Icon</h5>
                                <div class="d-flex align-items-center">
                                    @if($category->icon)
                                        <div class="bg-light rounded p-3 mr-3">
                                            <i class="fas {{ $category->icon }} fa-2x"></i>
                                        </div>
                                        <div>
                                            <code>{{ $category->icon }}</code>
                                        </div>
                                    @else
                                        <div class="text-muted">Tidak ada icon</div>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="mb-2">Nama</h5>
                                <p>{{ $category->name }}</p>
                            </div>

                            <div class="mb-4">
                                <h5 class="mb-2">Slug</h5>
                                <code>{{ $category->slug }}</code>
                            </div>

                            <div class="mb-4">
                                <h5 class="mb-2">Urutan</h5>
                                <p>{{ $category->order ?? 0 }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <h5 class="mb-2">Kategori Induk</h5>
                                @if($category->parent)
                                    <a href="{{ route('admin.knowledge.categories.show', $category->parent_id) }}">
                                        {{ $category->parent->name }}
                                    </a>
                                @else
                                    <span class="text-muted">Tidak ada kategori induk</span>
                                @endif
                            </div>

                            <div class="mb-4">
                                <h5 class="mb-2">Dibuat Pada</h5>
                                <p>{{ $category->created_at->format('d M Y H:i') }}</p>
                            </div>

                            <div class="mb-4">
                                <h5 class="mb-2">Diperbarui Pada</h5>
                                <p>{{ $category->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-2">Deskripsi</h5>
                        @if($category->description)
                            <p>{{ $category->description }}</p>
                        @else
                            <p class="text-muted">Tidak ada deskripsi</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Articles in category -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Artikel dalam Kategori Ini</h6>
                    <a href="{{ route('admin.knowledge.articles.create', ['category_id' => $category->id]) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus fa-sm"></i> Tambah Artikel
                    </a>
                </div>
                <div class="card-body">
                    @if($articles->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Judul</th>
                                        <th>Status</th>
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($articles as $article)
                                    <tr>
                                        <td>{{ $article->id }}</td>
                                        <td>
                                            <a href="{{ route('admin.knowledge.articles.show', $article->id) }}">
                                                {{ $article->title }}
                                            </a>
                                        </td>
                                        <td>
                                            @if($article->is_published)
                                                <span class="badge badge-success">Dipublikasi</span>
                                            @else
                                                <span class="badge badge-secondary">Draft</span>
                                            @endif
                                        </td>
                                        <td>{{ $article->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.knowledge.articles.edit', $article->id) }}" class="btn btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.knowledge.articles.show', $article->id) }}" class="btn btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($articles->hasPages())
                            <div class="mt-4">
                                {{ $articles->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-newspaper fa-3x text-gray-300 mb-3"></i>
                            <p class="mb-0">Belum ada artikel dalam kategori ini.</p>
                            <div class="mt-3">
                                <a href="{{ route('admin.knowledge.articles.create', ['category_id' => $category->id]) }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Buat Artikel Pertama
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Sub-categories -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Sub-Kategori</h6>
                    <a href="{{ route('admin.knowledge.categories.create', ['parent_id' => $category->id]) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus fa-sm"></i> Tambah Sub-Kategori
                    </a>
                </div>
                <div class="card-body">
                    @if($children->count() > 0)
                        <div class="list-group">
                            @foreach($children as $child)
                                <a href="{{ route('admin.knowledge.categories.show', $child->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($child->icon)
                                            <i class="fas {{ $child->icon }} mr-2"></i>
                                        @endif
                                        {{ $child->name }}
                                        @if(!$child->is_active)
                                            <span class="badge badge-secondary ml-2">Tidak Aktif</span>
                                        @endif
                                    </div>
                                    <span class="badge badge-primary badge-pill">{{ $child->articles_count ?? 0 }}</span>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-folder-open fa-3x text-gray-300 mb-3"></i>
                            <p class="mb-0">Belum ada sub-kategori.</p>
                            <div class="mt-3">
                                <a href="{{ route('admin.knowledge.categories.create', ['parent_id' => $category->id]) }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Buat Sub-Kategori
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Tindakan</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.knowledge.categories.edit', $category->id) }}" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-edit fa-sm"></i> Edit Kategori
                    </a>
                    <a href="{{ route('admin.knowledge.articles.create', ['category_id' => $category->id]) }}" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-plus fa-sm"></i> Tambah Artikel
                    </a>
                    <a href="{{ route('admin.knowledge.categories.create', ['parent_id' => $category->id]) }}" class="btn btn-info btn-block mb-2">
                        <i class="fas fa-folder-plus fa-sm"></i> Tambah Sub-Kategori
                    </a>
                    <a href="{{ route('knowledge.category', $category->slug) }}" class="btn btn-outline-primary btn-block mb-2" target="_blank">
                        <i class="fas fa-external-link-alt fa-sm"></i> Lihat di Knowledge Base
                    </a>
                    <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash fa-sm"></i> Hapus Kategori
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
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus kategori <strong>{{ $category->name }}</strong>?</p>
                
                @if($articles->count() > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Kategori ini memiliki {{ $articles->count() }} artikel. Menghapus kategori ini akan menghapus hubungan dengan artikel-artikel tersebut.
                </div>
                @endif
                
                @if($children->count() > 0)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Kategori ini memiliki {{ $children->count() }} sub-kategori. Menghapus kategori ini akan memindahkan sub-kategori ke level teratas.
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <form action="{{ route('admin.knowledge.categories.destroy', $category->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 