{{-- resources/views/layouts/partials/datatables-script.blade.php --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    // Global Config
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            processing: "Memproses...",
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            },
            emptyTable: "Tidak ada data tersedia",
            zeroRecords: "Tidak ditemukan data yang cocok"
        },
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        responsive: true,
        autoWidth: false,
        dom: '<"flex justify-between items-center mb-4"lfB><"overflow-x-auto"t><"flex justify-between items-center mt-4"ip>',
        buttons: [
            { extend: 'excel', text: 'Excel', className: 'buttons-excel' },
            { extend: 'pdf', text: 'PDF', className: 'buttons-pdf' },
            { extend: 'print', text: 'Cetak', className: 'buttons-print' }
        ]
    });

    // Inisialisasi hanya jika belum ada
    function initTable(tableId, ajaxUrl, columns) {
        const $table = $('#' + tableId);
        if ($table.length && !$.fn.DataTable.isDataTable($table)) {
            $table.DataTable({
                processing: true,
                serverSide: true,
                ajax: ajaxUrl,
                columns: columns
            });
        }
    }

    // Daftar tabel
    const tables = [
        { id: 'buku-table', route: '{{ route("buku.index") }}', columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'kode_buku' },
            { data: 'judul' },
            { data: 'pengarang' },
            { data: 'stok' },
            { data: 'action', orderable: false, searchable: false }
        ]},
        { id: 'siswa-table', route: '{{ route("admin.siswa.index") }}', columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nisn' },
            { data: 'nama' },
            { data: 'kelas' },
            { data: 'angkatan' },
            { data: 'action', orderable: false, searchable: false }
        ]}
    ];

    tables.forEach(t => initTable(t.id, t.route, t.columns));
});
</script>
