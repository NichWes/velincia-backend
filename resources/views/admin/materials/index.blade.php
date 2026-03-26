@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Materials</h1>
        <a href="{{ route('admin.materials.create') }}" class="bg-slate-900 text-white px-4 py-2 rounded-lg">+ Tambah</a>
    </div>

    <form method="GET" class="bg-white rounded-xl shadow p-4 mb-4">
        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Cari material..." class="border rounded-lg px-4 py-2 w-full md:w-80">
    </form>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-left">
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $material)
                    <tr class="border-b">
                        <td class="px-4 py-3">{{ $material->name }}</td>
                        <td class="px-4 py-3">{{ $material->category }}</td>
                        <td class="px-4 py-3">{{ $material->unit }}</td>
                        <td class="px-4 py-3">Rp {{ number_format($material->price_estimate ?? 0, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">{{ $material->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-4 py-3 flex gap-3">
                            <a href="{{ route('admin.materials.edit', $material) }}" class="text-blue-600 hover:underline">Edit</a>

                            <form method="POST" action="{{ route('admin.materials.destroy', $material) }}" onsubmit="return confirm('Yakin hapus material ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">Belum ada material.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $materials->links() }}
    </div>
@endsection