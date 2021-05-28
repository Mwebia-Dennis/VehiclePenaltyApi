<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuData;
use App\traits\Utils;

class SearchMenuDataController extends Controller
{
    
    use Utils;

    public function index(Request $request, $menu_id) {

        $menus= Menu::find($menu_id);
        $menuItems = $menus->menuItem()->get();
        $menuItemsColumns = [];
        // return $menuItems;

        foreach ($menuItems as $item) {
            
            $menuItemsColumns[] = $item["name"];

        }
        
        $menuData = new MenuData();
        // $data = [$request->column => $request->value];
        // echo json_encode($data);
        if(in_array($request->column, $menuItemsColumns)) {

            return response()->json($menuData->where("data", 'LIKE', '%'.$request->value.'%')->get(), 201);
    
        }
        return response()->json(["message", "could not find data"], 403);

    }
}
