<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_area_id',
        'audit_process_id',
        'audit_user_id',
        'audit_date',
        'area_name',
        'auditor_name',
        'score',
        'status',
    ];

    public function area()
    {
        return $this->belongsTo(AuditArea::class, 'audit_area_id');
    }

    public function process()
    {
        return $this->belongsTo(AuditProcess::class, 'audit_process_id');
    }

    public function user()
    {
        return $this->belongsTo(AuditUser::class, 'audit_user_id');
    }
}
