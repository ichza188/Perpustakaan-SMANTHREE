<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaTemplateExport implements FromArray, WithHeadings, WithStyles
{
// app/Exports/SiswaTemplateExport.php
public function array(): array
{
    return [
        ['Budi Santoso', '26/03/2001', 2024, '0071234567', 'X IPA 1'],
    ];
}

    public function headings(): array
    {
        return [
            'nama',
            'tanggal_lahir',
            'angkatan',
            'nisn',
            'kelas',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Baris pertama (header) → tebal + background
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFE0E0E0'],
                ],
            ],
        ];
    }
}
