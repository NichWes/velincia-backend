@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Materials</h1>

        <a href="{{ route('admin.materials.create') }}"
           class="bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-slate-800">
            + Tambah Material
        </a>
    </div>

    <form method="GET" class="bg-white rounded-xl shadow p-4 mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
        <div>
            <label class="block text-sm font-medium mb-1">Cari Material</label>
            <input
                type="text"
                name="keyword"
                value="{{ request('keyword') }}"
                placeholder="Nama / kategori / brand / variant"
                class="w-full border rounded-lg px-4 py-2"
            >
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Status</label>
            <select name="status" class="w-full border rounded-lg px-4 py-2">
                <option value="">Semua</option>
                <option value="1" @selected(request('status') === '1')>Active</option>
                <option value="0" @selected(request('status') === '0')>Inactive</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg">
                Filter
            </button>

            <a href="{{ route('admin.materials.index') }}"
               class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg">
                Reset
            </a>
        </div>
    </form>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b text-left">
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Brand</th>
                    <th class="px-4 py-3">Variant</th>
                    <th class="px-4 py-3">Unit</th>
                    <th class="px-4 py-3">Harga Estimasi</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $material)
                    <tr class="border-b">
                        <td class="px-4 py-3">{{ $material->name }}</td>
                        <td class="px-4 py-3">{{ $material->category }}</td>
                        <td class="px-4 py-3">{{ $material->brand ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $material->variant ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $material->unit }}</td>
                        <td class="px-4 py-3">
                            Rp {{ number_format($material->price_estimate ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($material->is_active)
                                <span class="inline-block px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-medium">
                                    Active
                                </span>
                            @else
                                <span class="inline-block px-2 py-1 rounded bg-gray-200 text-gray-700 text-xs font-medium">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.materials.edit', $material) }}"
                                   class="text-blue-600 hover:underline">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.materials.destroy', $material) }}"
                                      onsubmit="return confirm('Yakin nonaktifkan material ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        Nonaktifkan
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-4 text-center text-gray-500">
                            Belum ada material.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $materials->links() }}
    </div>
@endsection