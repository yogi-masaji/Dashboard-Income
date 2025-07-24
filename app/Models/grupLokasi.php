<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class grupLokasi extends Model
{
    protected $table = 'group_lokasi';
    protected $primaryKey = 'id_Glokasi';
    public $timestamps = false;

    protected $fillable = [
        'id_Group',
        'id_Lokasi',
    ];
}
