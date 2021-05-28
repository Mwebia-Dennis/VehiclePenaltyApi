<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MenuData;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class UserMenuDataController extends Controller
{
    public function store(Request $request, $user_id)
    {
        
        $request->validate($rules = [
    
            'data' => 'required',
            'menu_id'=> 'required|integer',

        ]);
        
        $areFieldsInDb = $this->checkFields($request->data, $request->menu_id );
        if($areFieldsInDb){

            $menuData = new MenuData();
            $menuData->data = $request->data;
            $menuData->menu_id = $request->menu_id;
    
            $user = Auth::user();
            $menuData->added_by =$user->id;
            $menuData->addedBy()->associate($menuData->added_by);
            $menuData->menu()->associate($menuData->menu_id);
    
            $menuData->save();
            return response()->json(["message" => "Data added successfully"], 201);

        }
        
        return response()->json(["message" => "Sorry could not find fields in db"], 403);

    }


    public function update(Request $request, $user_id, $menuData_id)
    {

        $request->validate($rules = [
    
            'data' => 'required',

        ]);

        $menuData = MenuData::find($menuData_id);
        $menuDetails =  $menuData->menu()->get();
        
        $areFieldsInDb = $this->checkFields($request->data, $menuDetails["0"]["id"]);

        if($areFieldsInDb) {

            $menuData->data = $request->data;
    
            if($menuData->isDirty()) {
                
                $menuData->save();
    
            }
    
    
            return response()->json(["message" => " Data updated successfully"], 201);

        }
        return response()->json(["message" => "Sorry could not find fields in db"], 403);
    }

    public function destroy($user_id, $menuId){

        $menuData = MenuData::find($menuId);
        if(Auth::user()->id == $menuData->added_by) {
            $menuData->delete();
            return response()->json(["message" => " Data deleted successfully"], 201);
        }else{
            
            return response()->json(["message" => " Data could not be deleted"], 403);
        }
    }


    private function checkFields($jsonData, $menuId ) {

        $areAllFieldsInDb = true;
        $data = json_decode($jsonData, true);
        $menu = Menu::find($menuId);
        $menuItems = $menu->menuItem()->get();
        $attr = array_keys($data);

        $menuItemsValue = [];
        foreach($menuItems as $item) {
            $menuItemsValue[] = strtolower(trim($item["name"]));
        }


        foreach($attr as $value) {
            if(!in_array( strtolower(trim($value)) ,$menuItemsValue)) {
                $areAllFieldsInDb = false;
                break;
            }
        }

        return $areAllFieldsInDb;
    }
}
