<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    // Nama tabel (opsional, kalau nama default "registrations" sudah betul, bisa dihapus)
    protected $table = 'registrations';

    // Kolom yang bisa diisi mass-assignment
    protected $fillable = [
        'product_id',
        'nama',
        'email',
        'telepon',
        'catatan',
    ];

    // Relasi ke Product
    public function product()
    {
        // pastikan foreign key adalah 'product_id'
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
