@extends('layouts.admin')

@section('title', 'Manajemen Kategori Knowledge Base')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Kategori Knowledge Base</h1>
        <a href="{{ route('admin.knowledge.categories.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Tambah Kategori Baru
        </a>
    </div>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.knowledge.index') }}">Knowledge Base</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kategori</li>
        </ol>
    </nav>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Kategori</h6>
            <div>
                <span class="badge badge-info mr-1">Total: {{ $categories->count() }}</span>
                <span class="badge badge-success">Aktif: {{ $categories->where('is_active', true)->count() }}</span>
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

            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="categoriesTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th width="60">Icon</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Kategori Induk</th>
                            <th>Artikel</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td class="text-center">
                                <i class="fas {{ $category->icon }} fa-lg"></i>
                            </td>
                            <td>
                                <a href="{{ route('admin.knowledge.categories.show', $category->id) }}">
                                    {{ $category->name }}
                                </a>
                                @if(!$category->is_active)
                                <span class="badge badge-secondary ml-1">Non-aktif</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($category->description, 50) }}</td>
                            <td>
                                @if($category->parent)
                                <a href="{{ route('admin.knowledge.categories.show', $category->parent->id) }}">
                                    {{ $category->parent->name }}
                                </a>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $category->articles_count ?? 0 }}</td>
                            <td>
                                <a href="{{ route('admin.knowledge.categories.edit', $category->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal-{{ $category->id }}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        
                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal-{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $category->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel-{{ $category->id }}">Konfirmasi Hapus</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus kategori <strong>{{ $category->name }}</strong>?</p>
                                        
                                        @if($category->articles_count > 0)
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Kategori ini memiliki {{ $category->articles_count }} artikel. Menghapus kategori ini akan membuat artikel tersebut tidak memiliki kategori.
                                        </div>
                                        @endif
                                        
                                        @if($category->children_count > 0)
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle mr-1"></i> Kategori ini memiliki {{ $category->children_count }} sub-kategori. Menghapus kategori ini akan membuat sub-kategori tersebut menjadi kategori utama.
                                        </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <form action="{{ route('admin.knowledge.categories.destroy', $category->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus Kategori</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <p class="my-3 text-muted">Belum ada kategori. Silakan tambahkan kategori pertama Anda.</p>
                                <a href="{{ route('admin.knowledge.categories.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus mr-1"></i> Tambah Kategori Baru
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#categoriesTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "pageLength": 25,
            "order": [[ 0, "desc" ]]
        });
    });
</script>
@endsection 