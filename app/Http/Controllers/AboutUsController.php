<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{
    public function index()
    {
        $about = AboutUs::first();
        return view('admin.about.index', compact('about'));
    }

    public function create()
    {
        return view('admin.about.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $about = new AboutUs();
        $about->title = $request->title;
        $about->content = $request->input('content'); // FIX

        if ($request->hasFile('image')) {
            $about->image = $request->file('image')->store('about', 'public');
        }

        $about->save();

        return redirect()->route('admin.about.index')->with('success', 'Tentang Kami berhasil ditambahkan');
    }

    public function edit(AboutUs $about)
    {
        return view('admin.about.edit', compact('about'));
    }

    public function update(Request $request, AboutUs $about)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $about->title = $request->title;
        $about->content = $request->input('content'); // FIX

        // jika upload gambar baru
        if ($request->hasFile('image')) {

            // hapus gambar lama kalau ada
            if ($about->image && Storage::disk('public')->exists($about->image)) {
                Storage::disk('public')->delete($about->image);
            }

            // simpan gambar baru
            $about->image = $request->file('image')->store('about', 'public');
        }

        $about->save();

        return redirect()->route('admin.about.index')->with('success', 'Tentang Kami berhasil diperbarui');
    }

    public function destroy(AboutUs $about)
    {
        // hapus gambar kalau ada
        if ($about->image && Storage::disk('public')->exists($about->image)) {
            Storage::disk('public')->delete($about->image);
        }

        $about->delete();

        return redirect()->route('admin.about.index')->with('success', 'Tentang Kami berhasil dihapus');
    }
}
