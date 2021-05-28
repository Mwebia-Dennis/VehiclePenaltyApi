<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;

class MenuDataController extends Controller
{
    public function index(Menu $menu)
    {
        
        return response()->json($menu->menuData()->get(), 200);


    }
}
