<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block mb-1 text-sm font-medium">Kategori</label>
        <input
            type="text"
            name="category"
            value="{{ old('category', $material->category ?? '') }}"
            class="w-full border rounded-lg px-4 py-2"
            required
        >
        @error('category')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium">Nama Material</label>
        <input
            type="text"
            name="name"
            value="{{ old('name', $material->name ?? '') }}"
            class="w-full border rounded-lg px-4 py-2"
            required
        >
        @error('name')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium">Brand</label>
        <input
            type="text"
            name="brand"
            value="{{ old('brand', $material->brand ?? '') }}"
            class="w-full border rounded-lg px-4 py-2"
        >
        @error('brand')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium">Variant</label>
        <input
            type="text"
            name="variant"
            value="{{ old('variant', $material->variant ?? '') }}"
            class="w-full border rounded-lg px-4 py-2"
        >
        @error('variant')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium">Unit</label>
        <input
            type="text"
            name="unit"
            value="{{ old('unit', $material->unit ?? '') }}"
            class="w-full border rounded-lg px-4 py-2"
            placeholder="contoh: lbr / pcs / bks"
            required
        >
        @error('unit')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium">Harga Estimasi</label>
        <input
            type="number"
            step="0.01"
            min="0"
            name="price_estimate"
            value="{{ old('price_estimate', $material->price_estimate ?? '') }}"
            class="w-full border rounded-lg px-4 py-2"
        >
        @error('price_estimate')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block mb-1 text-sm font-medium">Status</label>
        <select name="is_active" class="w-full border rounded-lg px-4 py-2" required>
            <option value="1" @selected(old('is_active', isset($material) ? (string) $material->is_active : '1') === '1')>
                Active
            </option>
            <option value="0" @selected(old('is_active', isset($material) ? (string) $material->is_active : '1') === '0')>
                Inactive
            </option>
        </select>
        @error('is_active')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="pt-4 flex items-center gap-3">
    <button type="submit" class="bg-slate-900 text-white px-5 py-2 rounded-lg hover:bg-slate-800">
        Simpan
    </button>

    <a href="{{ route('admin.materials.index') }}" class="bg-gray-200 text-gray-800 px-5 py-2 rounded-lg">
        Kembali
    </a>
</div>