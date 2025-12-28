@extends('layouts.admin')

@section('title', 'Kelola Siswa')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-gray-800">Kelola Siswa</h1>
    <div class="flex space-x-2">
        <a href="{{ route('admin.siswa.download-template') }}"
           class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 flex items-center text-sm">
            Download Template
        </a>
        <a href="{{ route('admin.siswa.create') }}"
           class="bg-teal-600 text-white px-4 py-2 rounded-md hover:bg-teal-700 flex items-center text-sm">
            Import Excel
        </a>
    </div>
</div>

<div class="overflow-x-auto">
    <table id="siswa-table" class="datatable w-full">
        <thead>
            <tr>
                <th class="no-sort">No</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Angkatan</th>
                <th class="no-sort no-search">Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-bold mb-4">Edit Siswa</h3>
        <form id="editForm">
            @csrf @method('PUT')
            <input type="hidden" id="siswa_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">NISN</label>
                <input type="text" id="nisn" name="nisn" class="mt-1 block w-full border rounded-md px-3 py-2" maxlength="10" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" id="nama" name="nama" class="mt-1 block w-full border rounded-md px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Kelas</label>
                <input type="text" id="kelas" name="kelas" class="mt-1 block w-full border rounded-md px-3 py-2" required>
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
        $.get('{{ url("admin/siswa") }}/' + id + '/edit', function(data) {
            $('#siswa_id').val(data.id);
            $('#nisn').val(data.nisn);
            $('#nama').val(data.nama);
            $('#kelas').val(data.kelas);
            $('#editModal').removeClass('hidden');
        });
    });

    // Update
    $('#editForm').submit(function(e) {
        e.preventDefault();
        let id = $('#siswa_id').val();
        $.ajax({
            url: '{{ url("admin/siswa") }}/' + id,
            type: 'PUT',
            data: $(this).serialize(),
            success: function(res) {
                $('#editModal').addClass('hidden');
                $('#siswa-table').DataTable().ajax.reload();
                alert(res.success);
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Gagal update'));
            }
        });
    });

    // Delete
    $('body').on('click', '.delete', function() {
        if (confirm('Hapus siswa ini?')) {
            let id = $(this).data('id');
            $.ajax({
                url: '{{ url("admin/siswa") }}/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    $('#siswa-table').DataTable().ajax.reload();
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
