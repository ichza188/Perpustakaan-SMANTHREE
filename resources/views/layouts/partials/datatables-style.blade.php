{{-- resources/views/layouts/partials/datatables-style.blade.php --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<style>
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .dataTables_wrapper {
        font-family: system-ui, -apple-system, sans-serif;
    }

    table.dataTable thead th {
        background: #f8fafc;
        color: #1e293b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 0.75rem 1rem;
        border-bottom: 2px solid #e2e8f0;
    }

    table.dataTable tbody td {
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        color: #374151;
        border-bottom: 1px solid #e2e8f0;
    }

    table.dataTable tbody tr:hover {
        background-color: #f1f5f9;
    }

    .dataTables_filter input,
    .dataTables_length select {
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }

    .dataTables_filter input:focus,
    .dataTables_length select:focus {
        outline: none;
        border-color: #14b8a6;
        box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.2);
    }

    .dataTables_paginate .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 2px;
        border: 1px solid #cbd5e1;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }

    .dataTables_paginate .paginate_button.current {
        background: #ffffff;
        color: white;
        border-color: #14b8a6;
    }

    .dt-buttons .dt-button {
        background: #ffffff;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        margin-right: 0.5rem;
    }

    .dt-buttons .dt-button:hover {
        background: #ffffff;
    }

    .buttons-excel {
        background: #16a34a !important;
    }

    .buttons-pdf {
        background: #dc2626 !important;
    }

    .buttons-print {
        background: #2563eb !important;
    }
</style>
