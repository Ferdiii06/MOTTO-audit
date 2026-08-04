<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'slug',
        'name',
        'description',
        'icon_svg',
        'sort_order',
    ];

    public function processes()
    {
        return $this->hasMany(AuditProcess::class)->orderBy('sort_order');
    }

    public function records()
    {
        return $this->hasMany(AuditRecord::class);
    }
}
