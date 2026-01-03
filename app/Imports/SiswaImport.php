<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class SiswaImport extends DefaultValueBinder implements ToModel, WithHeadingRow, WithValidation, WithCustomValueBinder
{
    public function model(array $row)
    {
        // Skip baris kosong
        if (!isset($row['nisn']) || empty($row['nisn'])) {
            return null;
        }
    
        // === PERBAIKAN TANGGAL ===
        $tanggal_lahir_raw = $row['tanggal_lahir'];
    
        // Cek apakah Excel serial number (numeric)
        if (is_numeric($tanggal_lahir_raw)) {
            $tanggal_lahir = Date::excelToDateTimeObject($tanggal_lahir_raw)->format('Y-m-d');
        }
        // Jika string: 26/03/2001 → parse dengan format DD/MM/YYYY
        else {
            try {
                $tanggal_lahir = Carbon::createFromFormat('d/m/Y', $tanggal_lahir_raw)->format('Y-m-d');
            } catch (\Exception $e) {
                \Log::error("Gagal parse tanggal: {$tanggal_lahir_raw}");
                return null; // Skip baris ini
            }
        }
    
        // === LOGIKA PASSWORD ===
        // Mengubah tanggal lahir menjadi format YYYYMMDD (contoh: 20070515)
        $passwordDefault = Carbon::parse($tanggal_lahir)->format('Ymd');
    
        // Cek user sudah ada atau buat baru
        $user = User::firstOrCreate(
            ['username' => $row['nisn']],
            [
                'password' => Hash::make($passwordDefault), // Password otomatis dari tanggal lahir
                'role' => 'siswa',
            ]
        );
    
        // Cek siswa sudah ada agar tidak duplikat
        if (Siswa::where('user_id', $user->id)->exists()) {
            return null;
        }
    
        return new Siswa([
            'user_id' => $user->id,
            'nama' => $row['nama'],
            'tanggal_lahir' => $tanggal_lahir, // Simpan format asli Y-m-d ke database
            'angkatan' => $row['angkatan'],
            'nisn' => $row['nisn'],
            'kelas' => $row['kelas'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required',
            'angkatan' => 'required|integer|min:2000|max:2099',
            'nisn' => 'required|unique:siswa,nisn',
            'kelas' => 'required|string|max:20',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() === 'B' && is_numeric($value)) { // Kolom tanggal_lahir
            $cell->setValueExplicit($value, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $cell->getStyle()->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
