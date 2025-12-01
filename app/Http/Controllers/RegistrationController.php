<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Product;

class RegistrationController extends Controller
{
    // Simpan data pendaftaran
    public function store(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'telepon' => 'required|string|max:20',
        ]);

        $product = Product::findOrFail($id);

        $registration = new Registration();
        $registration->product_id = $product->id;
        $registration->nama = $request->nama;
        $registration->email = $request->email;
        $registration->telepon = $request->telepon;
        $registration->catatan = $request->catatan ?? null;
        $registration->save();

    // Redirect to payment page for this registration
    return redirect()->route('payments.create', $registration->id)
             ->with('success', 'Pendaftaran berhasil! Silakan lanjutkan ke pembayaran.');
    }

    // Tampilkan detail registrasi
    public function show($id)
    {
        // ambil registrasi + relasi produk
        $registration = Registration::with('product')->findOrFail($id);

        return view('admin.registrations.show', compact('registration'));
    }

    // Daftar semua registrasi (untuk index)
    public function index()
    {
        $registrations = Registration::with('product')->latest()->paginate(10);

        return view('admin.registrations.index', compact('registrations'));
    }
}
