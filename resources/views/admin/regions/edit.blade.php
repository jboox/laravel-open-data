@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Wilayah</h1>

<form action="{{ route('admin.regions.update', $region) }}" method="POST" class="space-y-4">
    @csrf @method('PUT')
    <div>
        <label class="block">Nama</label>
        <input type="text" name="name" value="{{ $region->name }}" class="w-full border px-3 py-2" required>
    </div>
    <div>
        <label class="block">Level</label>
        <select name="level" class="w-full border px-3 py-2">
            <option value="1" @if($region->level==1) selected @endif>Kabupaten</option>
            <option value="2" @if($region->level==2) selected @endif>Kecamatan</option>
            <option value="3" @if($region->level==3) selected @endif>Kelurahan/Desa</option>
            <option value="4" @if($region->level==4) selected @endif>RT/RW</option>
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
</form>
@endsection
