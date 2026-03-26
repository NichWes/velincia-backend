<div>
    <label class="block mb-1">Kategori</label>
    <input type="text" name="category" value="{{ old('category', $material->category ?? '') }}" class="w-full border rounded-lg px-4 py-2" required>
</div>

<div>
    <label class="block mb-1">Nama</label>
    <input type="text" name="name" value="{{ old('name', $material->name ?? '') }}" class="w-full border rounded-lg px-4 py-2" required>
</div>

<div>
    <label class="block mb-1">Brand</label>
    <input type="text" name="brand" value="{{ old('brand', $material->brand ?? '') }}" class="w-full border rounded-lg px-4 py-2">
</div>

<div>
    <label class="block mb-1">Variant</label>
    <input type="text" name="variant" value="{{ old('variant', $material->variant ?? '') }}" class="w-full border rounded-lg px-4 py-2">
</div>

<div>
    <label class="block mb-1">Unit</label>
    <input type="text" name="unit" value="{{ old('unit', $material->unit ?? '') }}" class="w-full border rounded-lg px-4 py-2" required>
</div>

<div>
    <label class="block mb-1">Harga Estimasi</label>
    <input type="number" step="0.01" name="price_estimate" value="{{ old('price_estimate', $material->price_estimate ?? '') }}" class="w-full border rounded-lg px-4 py-2">
</div>

<div>
    <label class="block mb-1">Status</label>
    <select name="is_active" class="w-full border rounded-lg px-4 py-2">
        <option value="1" @selected(old('is_active', $material->is_active ?? 1) == 1)>Active</option>
        <option value="0" @selected(old('is_active', $material->is_active ?? 1) == 0)>Inactive</option>
    </select>
</div>

<button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg">
    Simpan
</button>