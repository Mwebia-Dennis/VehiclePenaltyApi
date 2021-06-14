<?php

namespace App\Http\Controllers\ExcelFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExcelFile;
use Illuminate\Support\Facades\Storage;

class ExcelFileController extends Controller
{
    public function index(Request $request) {

        $request->validate($rules = [
            
            'page_type' => 'required',
        ]);

        
        $excelFile = new ExcelFile();
        if($request->has('sort_by')) {
            $excelFile = $excelFile->orderBy($request->sort_by, 'DESC');
        }
        
        $perPage = ($request->has('per_page'))?$request->per_page:env('PER_PAGE');
        return response()->json(
            $excelFile->where("page_type", '=', $request->page_type)
            ->paginate($perPage), 
        201);

    }

    
    public function show($excel_id) {

        $file = ExcelFile::find($excel_id);
        return Storage::download("public/".explode(url('/')."/storage",$file->file_url)["0"]);
        
    }

    
}
