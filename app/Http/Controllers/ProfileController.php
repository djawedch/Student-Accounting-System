<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = User::with('university', 'department', 'student')->find(Auth::id());

        return view('profile.show', compact('user'));
    }
}