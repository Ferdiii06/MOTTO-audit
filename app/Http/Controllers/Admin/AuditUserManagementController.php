<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditSchedule;
use App\Models\AuditUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuditUserManagementController extends Controller
{
    private function checkAdmin(): void
    {
        $userId = session('audit_user_id');
        $user = $userId ? AuditUser::find($userId) : null;

        if (! $user || ! $user->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Admin QA yang dapat mengelola akun auditor.');
        }
    }

    public function index()
    {
        $this->checkAdmin();

        $users = AuditUser::orderBy('role')->orderBy('nik')->get();

        return view('admin.auditor.index', compact('users'));
    }

    public function create()
    {
        $this->checkAdmin();

        return view('admin.auditor.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'nik' => 'required|string|max:50|unique:audit_users,nik',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,auditor',
            'tipe_auditor' => 'required|in:auditor,instruktur',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK sudah terdaftar di sistem.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'role.required' => 'Role akun wajib dipilih.',
            'role.in' => 'Role harus berupa Admin atau Auditor.',
            'tipe_auditor.required' => 'Tipe auditor wajib dipilih.',
            'tipe_auditor.in' => 'Tipe auditor harus berupa Auditor atau Instruktur.',
        ]);

        AuditUser::create([
            'nik' => $request->input('nik'),
            'name' => $request->input('name'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
            'tipe_auditor' => $request->input('tipe_auditor'),
        ]);

        return redirect()->route('admin.auditor.index')->with('success', 'Akun auditor berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $this->checkAdmin();

        $user = AuditUser::findOrFail($id);

        return view('admin.auditor.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $targetUser = AuditUser::findOrFail($id);

        $request->validate([
            'nik' => [
                'required',
                'string',
                'max:50',
                Rule::unique('audit_users', 'nik')->ignore($targetUser->id),
            ],
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,auditor',
            'tipe_auditor' => 'required|in:auditor,instruktur',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.unique' => 'NIK sudah terdaftar di sistem.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'role.required' => 'Role akun wajib dipilih.',
            'role.in' => 'Role harus berupa Admin atau Auditor.',
            'tipe_auditor.required' => 'Tipe auditor wajib dipilih.',
            'tipe_auditor.in' => 'Tipe auditor harus berupa Auditor atau Instruktur.',
        ]);

        $data = [
            'nik' => $request->input('nik'),
            'name' => $request->input('name'),
            'role' => $request->input('role'),
            'tipe_auditor' => $request->input('tipe_auditor'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $targetUser->update($data);

        return redirect()->route('admin.auditor.index')->with('success', 'Akun auditor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $this->checkAdmin();

        $currentUserId = session('audit_user_id');

        if ((int) $id === (int) $currentUserId) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus akun yang sedang login.');
        }

        $targetUser = AuditUser::findOrFail($id);

        $scheduleCount = AuditSchedule::where('audit_user_id', $targetUser->id)->count();

        if ($scheduleCount > 0) {
            return redirect()->back()->with('error', "Auditor '{$targetUser->name}' memiliki {$scheduleCount} jadwal audit aktif, tidak dapat dihapus.");
        }

        $targetUser->delete();

        return redirect()->route('admin.auditor.index')->with('success', 'Akun auditor berhasil dihapus.');
    }
}
