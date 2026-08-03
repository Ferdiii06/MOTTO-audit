<?php

namespace App\Http\Controllers;

use App\Models\AuditUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuditAuthController extends Controller
{
    public function showLogin()
    {
        return view('audit.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = AuditUser::where('nik', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['username' => 'NIK atau password salah.'])->withInput();
        }

        $request->session()->put('audit_user_id', $user->id);
        $request->session()->put('audit_user_name', $user->name);

        return redirect('/audit/dashboard');
    }

    public function dashboard()
    {
        return view('audit.dashboard');
    }
    public function standard5s()
    {
        return view('audit.5s-standard');
    }

    public function changePointManagement()
    {
        return view('audit.change-point-management');
    }

    public function licenseSystem()
    {
        return view('audit.license-system');
    }

    public function riwayat()
    {
        return view('audit.riwayat');
    }

    public function pedoman()
    {
        return view('audit.pedoman');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('audit_user_id');
        $request->session()->forget('audit_user_name');

        return redirect('/audit/login');
    }
}