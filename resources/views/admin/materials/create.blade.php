@extends('layouts.admin')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Tambah Material</h1>

    <div class="bg-white rounded-xl shadow p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.materials.store') }}" class="space-y-4">
            @csrf
            @include('admin.materials.partials.form', ['material' => null])
        </form>
    </div>
@endsection