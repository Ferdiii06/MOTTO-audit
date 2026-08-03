<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditUser extends Model
{
    protected $fillable = ['nik', 'name', 'password'];
    protected $hidden = ['password'];
}