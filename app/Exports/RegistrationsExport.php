<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RegistrationsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Registration::with('product')
            ->get()
            ->map(function ($reg) {
                return [
                    'Produk' => $reg->product->title ?? '-',
                    'Nama' => $reg->nama,
                    'Email' => $reg->email,
                    'Telepon' => $reg->telepon,
                    'Catatan' => $reg->catatan,
                    'Tanggal Daftar' => $reg->created_at->format('d-m-Y H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return ['Produk', 'Nama', 'Email', 'Telepon', 'Catatan', 'Tanggal Daftar'];
    }
}

