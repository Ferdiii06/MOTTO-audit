<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditUser extends Model
{
    protected $fillable = ['nik', 'name', 'password', 'role'];
    protected $hidden = ['password'];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
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