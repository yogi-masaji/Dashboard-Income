<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'ms_menu';

    protected $primaryKey = 'id_Menu';

    public $timestamps = false;

    protected $fillable = [
        'nama_Menu',
        'nama_File',
        'addname_file'
    ];

    public const TRANSACTION_MENU_IDS = [1, 2, 3, 4];
    public const INCOME_MENU_IDS      = [5, 6, 7, 10, 142, 144, 147];
    public const EPAYMENT_MENU_IDS   = [8, 9, 11];
    public const TRAFFIC_MENU_IDS    = [22, 23, 24, 25];
    public const SEARCH_MENU_IDS     = [
        12,
        13,
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        26,
        30,
        136,
        137,
        138,
        139,
        140,
        141,
        143,
        145,
        148,
        149,
        150,
        151,
        152,
        153
    ];

    public static function getMenusByGroup(array $ids)
    {
        return self::whereIn('id_Menu', $ids)->get();
    }
}
