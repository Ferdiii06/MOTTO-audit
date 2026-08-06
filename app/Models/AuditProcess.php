<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditProcess extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_area_id',
        'name',
        'description',
        'checkpoint',
        'kriteria_judgement',
        'status',
        'sort_order',
    ];

    public function area()
    {
        return $this->belongsTo(AuditArea::class, 'audit_area_id');
    }

    public function records()
    {
        return $this->hasMany(AuditRecord::class);
    }

    public function points()
    {
        return $this->hasMany(AuditPoint::class, 'audit_process_id')->orderBy('nomor_urut');
    }
}
