<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{

    public function create()
    {
        return view('auth.login');
    }


    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $request->user()->recordActivity('قام بتسجيل الدخول إلى النظام');

        return redirect()->intended(RouteServiceProvider::HOME . '?t=' . time())->with('success', 'مرحباً بعودتك من جديد');

    }


    public function destroy(Request $request)
    {
        if ($request->user()) {
            $request->user()->recordActivity('قام بتسجيل الخروج من النظام');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login?t=' . time());
    }
}