<?php

namespace App\Imports;

use App\Models\Buku;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Throwable;

class BukuImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsFailures;

    public function model(array $row)
    {
        // Skip jika kode_buku kosong
        if (empty($row['kode_buku'])) {
            return null;
        }

        // Skip jika kode_buku sudah ada
        if (Buku::where('kode_buku', $row['kode_buku'])->exists()) {
            return null;
        }

        return new Buku([
            'kode_buku' => $row['kode_buku'],
            'judul' => $row['judul'],
            'pengarang' => $row['pengarang'],
            'stok' => $row['stok'] ?? 1,
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_buku' => 'required|string|max:20',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
        ];
    }

    public function onError(Throwable $e)
    {
        // Log error jika perlu
    }
}
