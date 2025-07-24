<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lokasi extends Model
{
    protected $table = 'ms_lokasi';

    protected $primaryKey = 'id_Lokasi';

    public $timestamps = false;

    protected $fillable = [
        'kode_Lokasi',
        'nama_Lokasi',
        'ip_Lokasi',
        'chisel_Version',
        'id_Group',
        'system_code',
        'endpoint_status',
        'active_status',
        'db_migration',
        'version_batch',
        'profile_name',
        'update_status',
        'cutoff_date',
    ];
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_lokasi', 'id_Lokasi', 'id_Group');
    }
}
