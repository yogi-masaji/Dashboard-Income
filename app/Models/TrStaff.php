<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrStaff extends Model
{
    use HasFactory;

    protected $table = 'tr_staff';
    protected $primaryKey = 'id_trStaff';
    public $timestamps = true; // Set true if you have datetime_Registered

    const CREATED_AT = 'datetime_Registered';
    const UPDATED_AT = null; // No updated_at column

    protected $fillable = [
        'id_Staff',
        'id_Group',
        'id_Lokasi',
        'id_Registrant',
        'id_removed'
    ];
}
