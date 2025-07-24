<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsStaff extends Model
{
    use HasFactory;

    protected $table = 'ms_staff';
    protected $primaryKey = 'id';
    public $timestamps = true; // Set true if you have created_at and updated_at

    const CREATED_AT = 'datetime_Registered';
    const UPDATED_AT = null; // No updated_at column

    protected $fillable = [
        'id_Staff',
        'nama_Staff',
        'foto_profile',
        'id_Registrant',
        'id_removed'
    ];
}
