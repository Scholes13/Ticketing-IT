@extends('layouts.admin')

@section('title', 'Detail Staff')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center">
        <h2 class="my-6 text-2xl font-semibold text-gray-700">
            Detail Staff
        </h2>
        <div class="flex space-x-2">
            <a href="{{ route('staff.edit', $staff->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <a href="{{ route('staff.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="w-full overflow-hidden rounded-lg shadow-md">
        <div class="p-6 bg-white">
            <div class="flex flex-col md:flex-row">
                <div class="md:w-1/4 mb-6 md:mb-0 flex justify-center">
                    <div class="w-40 h-40 rounded-full overflow-hidden bg-gray-200">
                        <img class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ $staff->name }}&background=random&size=150" alt="{{ $staff->name }}">
                    </div>
                </div>
                <div class="md:w-3/4 md:pl-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600 text-sm">Nama Lengkap</p>
                            <p class="font-semibold text-lg">{{ $staff->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Email</p>
                            <p class="font-semibold">{{ $staff->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Jabatan</p>
                            <p class="font-semibold">{{ $staff->position }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Jenis Kelamin</p>
                            <p class="font-semibold">{{ $staff->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">No. HP</p>
                            <p class="font-semibold">{{ $staff->phone }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Departemen</p>
                            <p class="font-semibold">{{ $staff->department ?? 'Tidak ada' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Status</p>
                            <p class="font-semibold">
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $staff->is_active ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' }}">
                                    {{ $staff->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Tanggal Dibuat</p>
                            <p class="font-semibold">{{ $staff->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    @if($staff->tickets->count() > 0)
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-2">Tiket Ditugaskan ({{ $staff->tickets->count() }})</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600">No. Tiket</th>
                                            <th class="px-4 py-2 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600">Judul</th>
                                            <th class="px-4 py-2 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600">Status</th>
                                            <th class="px-4 py-2 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600">Prioritas</th>
                                            <th class="px-4 py-2 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($staff->tickets as $ticket)
                                            <tr>
                                                <td class="px-4 py-2 border-b border-gray-200 text-sm">{{ $ticket->ticket_number }}</td>
                                                <td class="px-4 py-2 border-b border-gray-200 text-sm">{{ $ticket->title }}</td>
                                                <td class="px-4 py-2 border-b border-gray-200 text-sm">
                                                    @if($ticket->status == 'waiting')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Menunggu</span>
                                                    @elseif($ticket->status == 'in_progress')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Dalam Proses</span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Selesai</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 border-b border-gray-200 text-sm">
                                                    @if($ticket->priority == 'low')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Rendah</span>
                                                    @elseif($ticket->priority == 'medium')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Sedang</span>
                                                    @elseif($ticket->priority == 'high')
                                                        <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">Tinggi</span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Kritis</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 border-b border-gray-200 text-sm">
                                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-500 hover:text-blue-700">
                                                        <i class="fas fa-eye"></i> Lihat
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 