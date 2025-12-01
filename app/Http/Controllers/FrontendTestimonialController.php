<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Testimonial;

class FrontendTestimonialController extends Controller
{
    public function index()
    {
        // Only show testimonials that have been approved by admin
        $testimonials = Testimonial::where('status', true)->latest()->paginate(9);
        return view('frontend.testimoni', compact('testimonials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'pesan' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only(['nama', 'email', 'pesan']);
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('testimonials', 'public');
        }

        // Default status is false (pending)
        $data['status'] = false;

        Testimonial::create($data);

        return redirect()->route('frontend.testimoni.index')->with('success', 'Terima kasih atas testimoni Anda!');
    }
}




