<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MsLogin extends Authenticatable
{
    use Notifiable;

    protected $table = 'ms_login';
    protected $primaryKey = 'id_Staff'; // Tentukan primary key jika bukan 'id'
    public $timestamps = false; // Nonaktifkan timestamps jika tidak digunakan
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_Staff',
        'email_Staff',
        'password_Staff',
        'user_type_code',
        'lokasi',
        'site_name',
        'ip',
        'default_system_loc',
        'status_login',
        'user_active',
        'removed',
        // tambahkan kolom lain yang relevan
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password_Staff',
    ];

    // Definisikan nama kolom password kustom
    public function getAuthPassword()
    {
        return $this->password_Staff;
    }

    // Relasi ke model MsStaff
    public function staff()
    {
        return $this->belongsTo(MsStaff::class, 'id_Staff', 'id_Staff');
    }

    // Relasi ke model MsUserType
    public function userType()
    {
        return $this->belongsTo(MsUserType::class, 'user_type_code', 'user_type_code');
    }
    public function group()
    {
        return $this->belongsTo(MsGroup::class, 'lokasi', 'id_Group');
    }
}
