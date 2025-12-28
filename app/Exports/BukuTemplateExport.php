<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BukuTemplateExport implements FromArray, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['IPA001', 'Fisika Dasar', 'Prof. Budi', 10],
            ['IPS002', 'Sejarah Indonesia', 'Dr. Siti', 8],
        ];
    }

    public function headings(): array
    {
        return ['kode_buku', 'judul', 'pengarang', 'stok'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
