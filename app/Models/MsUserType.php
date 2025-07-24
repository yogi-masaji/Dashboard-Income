<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MsUserType extends Model
{
    use HasFactory;
    protected $table = 'ms_user_type';
    protected $primaryKey = 'user_type_id';
}
