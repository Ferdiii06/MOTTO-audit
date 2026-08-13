<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditType extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
    ];

    public function areas()
    {
        return $this->hasMany(AuditArea::class, 'audit_type_id');
    }
}
