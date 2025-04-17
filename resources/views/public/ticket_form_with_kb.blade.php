@extends('layouts.public')

@section('title', 'Buat Tiket Bantuan - IT Support')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h2 class="mb-0">Buat Tiket Bantuan</h2>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form id="ticket-form" action="{{ route('public.store.ticket') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Form token to prevent duplicate submissions -->
                        <input type="hidden" name="form_token" value="{{ Str::random(32) }}">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading"></i> Judul Tiket
                            </label>
                            <input type="text" class="form-control" id="title" name="title" required value="{{ old('title') }}" placeholder="Jelaskan masalah IT Anda secara singkat">
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i> Deskripsi Masalah
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="5" required placeholder="Jelaskan masalah IT Anda secara detail">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="requester_name" class="form-label">
                                    <i class="fas fa-user"></i> Nama Pemohon
                                </label>
                                <select class="form-select" id="requester_name" name="requester_name" required>
                                    <option value="">Pilih Nama Anda</option>
                                    @foreach($staffMembers as $staff)
                                        <option value="{{ $staff->id }}" {{ old('requester_name') == $staff->id ? 'selected' : '' }}>
                                            {{ $staff->name }} ({{ $staff->department }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="department" class="form-label">
                                    <i class="fas fa-building"></i> Departemen
                                </label>
                                <select class="form-select" id="department" name="department">
                                    <option value="">Pilih Departemen</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->name }}" {{ old('department') == $department->name ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="category_id" class="form-label">
                                    <i class="fas fa-tags"></i> Kategori
                                </label>
                                <select class="form-select" id="category_id" name="category_id">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="priority" class="form-label">
                                    <i class="fas fa-flag"></i> Prioritas
                                </label>
                                <select class="form-select" id="priority" name="priority" required>
                                    <option value="">Pilih Prioritas</option>
                                    <option value="low" class="priority-low" {{ old('priority') == 'low' ? 'selected' : '' }}>
                                        Rendah - Tidak mengganggu pekerjaan
                                    </option>
                                    <option value="medium" class="priority-medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>
                                        Sedang - Pekerjaan terganggu tapi masih bisa berjalan
                                    </option>
                                    <option value="high" class="priority-high" {{ old('priority') == 'high' ? 'selected' : '' }}>
                                        Tinggi - Pekerjaan terhambat signifikan
                                    </option>
                                    <option value="critical" class="priority-critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>
                                        Kritis - Tidak bisa bekerja sama sekali
                                    </option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="attachments" class="form-label">
                                <i class="fas fa-paperclip"></i> Lampiran (Opsional)
                            </label>
                            <div class="file-upload">
                                <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                                <div class="text-muted mt-2">
                                    <small>Format file yang didukung: JPG, PNG, PDF, DOC, DOCX, XLS, XLSX. Maks. 10MB per file.</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('public.check.ticket') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-search"></i> Cek Tiket yang Ada
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Kirim Tiket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Knowledge Base Suggestions -->
            <div id="knowledge-suggestions" class="card shadow-sm mb-4 d-none">
                <!-- Content will be filled dynamically via JavaScript -->
            </div>
            
            <!-- Help Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i> Butuh Bantuan?</h5>
                </div>
                <div class="card-body">
                    <p>Informasi panduan untuk mengisi form tiket:</p>
                    <ul class="mb-0">
                        <li><strong>Judul Tiket:</strong> Berikan judul singkat yang menjelaskan masalah IT Anda.</li>
                        <li><strong>Deskripsi:</strong> Jelaskan secara detail masalah yang Anda alami.</li>
                        <li><strong>Prioritas:</strong> Pilih tingkat urgensi masalah Anda.</li>
                    </ul>
                </div>
            </div>
            
            <!-- Knowledge Base Info -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-book me-2"></i> Knowledge Base</h5>
                </div>
                <div class="card-body">
                    <p>Coba cari solusi untuk masalah IT Anda di Knowledge Base kami sebelum membuat tiket:</p>
                    <a href="{{ route('knowledge.home') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-search me-2"></i> Jelajahi Knowledge Base
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/knowledge-suggestions.js') }}"></script>
@endsection 