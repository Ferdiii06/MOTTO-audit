<?php

namespace App\Http\Controllers;

use App\Models\AuditUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        $request->session()->put('audit_user_role', $user->role ?? 'auditor');
        $request->session()->put('audit_user_nik', $user->nik);

        return redirect('/audit/dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('audit_user_id');
        $request->session()->forget('audit_user_name');
        $request->session()->forget('audit_user_role');
        $request->session()->forget('audit_user_nik');

        return redirect('/audit/login');
    }
}