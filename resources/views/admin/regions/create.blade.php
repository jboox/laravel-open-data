@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Tambah Wilayah</h1>

<form action="{{ route('admin.regions.store') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block">Nama</label>
        <input type="text" name="name" class="w-full border px-3 py-2" required>
    </div>
    <div>
        <label class="block">Level</label>
        <select name="level" class="w-full border px-3 py-2">
            <option value="1">Kabupaten</option>
            <option value="2">Kecamatan</option>
            <option value="3">Kelurahan/Desa</option>
            <option value="4">RT/RW</option>
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
</form>
@endsection
