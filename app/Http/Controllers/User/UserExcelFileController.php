<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExcelFile;
use Illuminate\Support\Facades\Auth;

class UserExcelFileController extends Controller
{
    
    public function store(Request $request, $user_id) {

        $request->validate($rules = [
            
            'page_type' => 'required',         
            'files' => 'required|distinct:strict|array|min:1|max:8',
            'files.*' => 'max:10000000|mimes:xlsx,xls',
        ]);

        
        
        if($request->hasFile('files')) {

            foreach($request->File('files') as $file) {

                $excelFile = new ExcelFile();
                $excelFile->page_type =$request->page_type;
            
                $user = Auth::user();
                $excelFile->added_by =$user->id;
                $excelFile->addedBy()->associate($excelFile->added_by);
                $extension = $file->getClientOriginalExtension();
                $excelPath = md5(uniqid()).'.'.$extension;
                $excel_url = $file->storeAs('public/excel', $excelPath);
                $excel_url= 'storage'. substr($excel_url,strlen('public'));
        
                $excelFile->file_url = asset($excel_url);
                $excelFile->save();
            }
        }

        return response()->json(["message" => "Excel Dosyası Başarıyla Yüklendi"], 201);


    }

    
    public function destroy($user_id, $excelFile_id){

        $excelFile = ExcelFile::find($excelFile_id);
        if(Auth::user()->id == $excelFile->added_by) {
            $excelFile->delete();
            return response()->json(["message" => "Dosya başarıyla silindi"], 201);
        }else{
            
            return response()->json(["message" => " Dosya silinemedi"], 403);
        }
    }

}
