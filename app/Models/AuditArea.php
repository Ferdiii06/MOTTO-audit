<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'audit_type_id',
        'slug',
        'name',
        'description',
        'icon_svg',
        'sort_order',
    ];

    public function type()
    {
        return $this->belongsTo(AuditType::class, 'audit_type_id');
    }

    public function processes()
    {
        return $this->hasMany(AuditProcess::class)->orderBy('sort_order');
    }

    public function records()
    {
        return $this->hasMany(AuditRecord::class);
    }
}
