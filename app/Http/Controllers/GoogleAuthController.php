<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    //
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {

            $user = User::create([
                'name'  => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'role'  => 'pelanggan',
            ]);
        }

        Auth::login($user, true);

        switch ($user->role) {

            case 'admin_pusat':
                return redirect()->route('admin-pusat.dashboard');

            case 'admin_cabang':
                return redirect()->route('admin-cabang.dashboard');

            case 'pelanggan':
                return redirect()->route('landing');

            default:
                return redirect()->route('landing');
        }
    }
}