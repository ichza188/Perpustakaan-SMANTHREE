@extends('layouts.admin')
@section('title', 'Import Buku via Excel')
@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Import Buku</h1>
</div>

<div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
    <form action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">File Excel (.xlsx)</label>
            <input type="file" name="excel_file" required accept=".xlsx,.xls" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
        </div>
        <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-md hover:bg-teal-700">
            Import Excel
        </button>
        <a href="{{ route('buku.index') }}" class="ml-2 text-gray-600 hover:underline">Kembali</a>
    </form>
</div>
@endsection
