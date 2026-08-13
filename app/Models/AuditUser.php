<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditUser extends Model
{
    protected $fillable = ['nik', 'name', 'password', 'role', 'tipe_auditor'];
    protected $hidden = ['password'];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isInstruktur(): bool
    {
        return $this->tipe_auditor === 'instruktur';
    }

    public function schedules()
    {
        return $this->hasMany(AuditSchedule::class, 'audit_user_id');
    }

    public function getAllowedProcessIds(): ?array
    {
        if ($this->isAdmin()) {
            return null;
        }

        $mapping = config('auditors.mapping', []);

        return $mapping[$this->nik] ?? [];
    }
}