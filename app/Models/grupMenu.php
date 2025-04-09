<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class grupMenu extends Model
{
    protected $table = 'group_menu';
    protected $primaryKey = 'id_GroupMenu';

    protected $fillable = [
        'id_Group',
        'id_Menu',
        'datetime_Registered',
        'id_Registrant'
    ];
}
