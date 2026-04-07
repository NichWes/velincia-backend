@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Material</h1>

    <div class="bg-white rounded-xl shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.materials.update', $material) }}" class="space-y-4">
            @csrf
            @method('PUT')
            @include('admin.materials.partials.form', ['material' => $material])
        </form>
    </div>
@endsection