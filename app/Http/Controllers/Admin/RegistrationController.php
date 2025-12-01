<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\RegistrationsExport;

class RegistrationController extends Controller
{
    // Tampilkan semua pendaftaran
    public function index()
    {
        $registrations = Registration::with('product')->latest()->paginate(10);
        return view('admin.registrations.index', compact('registrations'));
    }

    // Detail peserta
    public function show($id)
    {
        $registration = Registration::with('product')->findOrFail($id);
        return view('admin.registrations.show', compact('registration'));
    }

    // Hapus data peserta
    public function destroy($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->delete();

    return redirect()->route('admin.registrations.index')
                         ->with('success', 'Data registrasi berhasil dihapus.');
    }

    public function exportExcel()
{
    return Excel::download(new RegistrationsExport, 'registrations.xlsx');
}

public function exportPDF()
{
    $registrations = \App\Models\Registration::with('product')->get();
    $pdf = Pdf::loadView('admin.registrations.pdf', compact('registrations'));
    return $pdf->download('registrations.pdf');
}
}
