<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\lokasi;

class NavbarController extends Controller
{
    public function index()
    {
        // Fetch all records from the ms_lokasi table
        $lokasi = lokasi::all()->sortBy('name');


        // Return the view with the data
        return view('layout.nav', compact('lokasi'));
    }
}
