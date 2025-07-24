<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'ms_group';
    protected $primaryKey = 'id_Group';
    public $timestamps = false;
    protected $fillable = [
        'nama_Group',
        'datetime_Registered',
        'id_Registrant',
    ];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'group_menu', 'id_Group', 'id_Menu');
    }
    public function locations()
    {
        return $this->belongsToMany(Lokasi::class, 'group_lokasi', 'id_Group', 'id_Lokasi');
    }
}
