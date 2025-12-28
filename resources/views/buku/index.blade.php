@extends('layouts.admin')

@section('title', 'Kelola Buku')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Kelola Buku</h1>
    <div class="flex space-x-2">
        <a href="{{ route('admin.buku.download-template') }}"
           class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center text-sm">
            <i class="fas fa-file-download mr-2"></i> Download Template
        </a>
        <a href="{{ route('buku.create') }}"
           class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 flex items-center text-sm">
            <i class="fas fa-file-excel mr-2"></i> Import Excel
        </a>
    </div>
</div>

<div class="overflow-x-auto">
    <table id="buku-table" class="datatable w-full">
        <thead>
            <tr>
                <th class="no-sort">No</th>
                <th>Kode Buku</th>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Stok</th>
                <th class="no-sort no-search text-center">Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-bold mb-4">Edit Buku</h3>
        <form id="editForm">
            @csrf @method('PUT')
            <input type="hidden" id="buku_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Kode Buku</label>
                <input type="text" id="kode_buku" name="kode_buku" class="mt-1 block w-full border rounded-md px-3 py-2" maxlength="20" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Judul</label>
                <input type="text" id="judul" name="judul" class="mt-1 block w-full border rounded-md px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Pengarang</label>
                <input type="text" id="pengarang" name="pengarang" class="mt-1 block w-full border rounded-md px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Stok</label>
                <input type="number" id="stok" name="stok" class="mt-1 block w-full border rounded-md px-3 py-2" min="0" required>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" class="btn-cancel bg-gray-500 text-white px-4 py-2 rounded-md">Batal</button>
                <button type="submit" class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Edit
    $('body').on('click', '.edit', function() {
        let id = $(this).data('id');
        $.get('{{ url("admin/buku") }}/' + id + '/edit', function(data) {
            $('#buku_id').val(data.id);
            $('#kode_buku').val(data.kode_buku);
            $('#judul').val(data.judul);
            $('#pengarang').val(data.pengarang);
            $('#stok').val(data.stok);
            $('#editModal').removeClass('hidden');
        });
    });

    // Update
    $('#editForm').submit(function(e) {
        e.preventDefault();
        let id = $('#buku_id').val();
        $.ajax({
            url: '{{ url("admin/buku") }}/' + id,
            type: 'PUT',
            data: $(this).serialize(),
            success: function(res) {
                $('#editModal').addClass('hidden');
                $('#buku-table').DataTable().ajax.reload();
                alert(res.success);
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Gagal update'));
            }
        });
    });

    // Delete
    $('body').on('click', '.delete', function() {
        if (confirm('Hapus buku ini?')) {
            let id = $(this).data('id');
            $.ajax({
                url: '{{ url("admin/buku") }}/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    $('#buku-table').DataTable().ajax.reload();
                    alert(res.success);
                }
            });
        }
    });

    // Close modal
    $('.btn-cancel, #editModal').on('click', function(e) {
        if (e.target === this) {
            $('#editModal').addClass('hidden');
        }
    });
});
</script>
@endpush
