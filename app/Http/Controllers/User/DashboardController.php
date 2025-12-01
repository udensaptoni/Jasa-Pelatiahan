<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Show latest 20 payments for user's registrations
        $payments = Payment::whereHas('registration', function($q) use ($user){
            $q->where('email', $user->email);
        })->latest()->take(20)->get();

        return view('user.dashboard', compact('payments'));
    }
}
