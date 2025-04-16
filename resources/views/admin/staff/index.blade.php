@extends('layouts.admin')

@section('title', 'Manajemen Staff')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="flex justify-between items-center">
        <h2 class="my-6 text-2xl font-semibold text-gray-700">
            Manajemen Staff
        </h2>
        <a href="{{ route('staff.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
            <i class="fas fa-plus mr-1"></i> Tambah Staff
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Jabatan</th>
                        <th class="px-4 py-3">Jenis Kelamin</th>
                        <th class="px-4 py-3">No. HP</th>
                        <th class="px-4 py-3">Departemen</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    @forelse($staff as $member)
                        <tr class="text-gray-700">
                            <td class="px-4 py-3">
                                <div class="flex items-center text-sm">
                                    <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                        <img class="object-cover w-full h-full rounded-full" src="https://ui-avatars.com/api/?name={{ $member->name }}&background=random" alt="{{ $member->name }}" loading="lazy" />
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $member->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $member->email }}</td>
                            <td class="px-4 py-3 text-sm">{{ $member->position }}</td>
                            <td class="px-4 py-3 text-sm">{{ $member->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $member->phone }}</td>
                            <td class="px-4 py-3 text-sm">{{ $member->department }}</td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full {{ $member->is_active ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100' }}">
                                    {{ $member->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('staff.edit', $member->id) }}" class="text-blue-500 hover:text-blue-600">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('staff.show', $member->id) }}" class="text-green-500 hover:text-green-600">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('staff.destroy', $member->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this staff?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-3 text-center text-gray-500">
                                Tidak ada data staff tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 