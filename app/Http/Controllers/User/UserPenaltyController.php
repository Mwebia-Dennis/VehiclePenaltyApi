<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penalty;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserPenaltyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        $user = Auth::user();

        
        return response()->json($user->myPenalty()->get(), 201);
    }

    public function store(Request $request, $user_id)
    {
        $request->validate($rules = [
    
            'vehicle_id' => 'required|integer',
            'penalty_type' => 'required',
            'receipt_number' => 'required',
            'penalty_date' => 'required',
            'payment_date' => 'required',
            'status' => 'required',
            'pdf' => 'mimes:pdf|max:50048',

        ]);
        $penalty = new Penalty();
        $penalty->penalty_type = $request->penalty_type;
        $penalty->receipt_number = $request->receipt_number;
        $penalty->penalty_date = $request->penalty_date;
        $penalty->payment_date = $request->payment_date;
        $penalty->status = $request->status;
        $penalty->vehicle_id = $request->vehicle_id;
        $penalty->vehicle()->associate($penalty->vehicle_id);

        $user = Auth::user();
        $penalty->added_by = $user->id;
        $penalty->addedBy()->associate($penalty->added_by);

        $penalty->pdf_url = '';
        if($request->hasFile('pdf')) {
            $extension = $request->File('pdf')->getClientOriginalExtension();
            $pdfPath = md5(uniqid()). $request->vehicle_id.'.'.$extension;
            $pdf_url = $request->File('pdf')->storeAs('public/pdf', $pdfPath);
            $pdf_url= 'storage'. substr($pdf_url,strlen('public'));
    
            $penalty->pdf_url = asset($pdf_url); 
        }

        $penalty->save();      

        return response()->json(["message" => " Penalty added successfully"], 201);
    }

    public function update(Request $request,$user_id, Penalty $penalty)
    {
        $request->validate($rules = [
    
            'vehicle_id' => 'required',
            'penalty_type' => 'required',
            'receipt_number' => 'required',
            'penalty_date' => 'required',
            'payment_date' => 'required',
            'status' => 'required',

        ]);
        $penalty->penalty_type = $request->penalty_type;
        $penalty->receipt_number = $request->receipt_number;
        $penalty->penalty_date = $request->penalty_date;
        $penalty->payment_date = $request->payment_date;
        $penalty->status = $request->status;
        $penalty->vehicle_id = $request->vehicle_id;

        if($penalty->isDirty()) {
            
            $penalty->vehicle()->associate($penalty->vehicle_id);    
            $penalty->save();

        }    

        return response()->json(["message" => " Penalty updated successfully"], 201);
    }

    public function destroy($user_id,Penalty $penalty)
    {
        if(Auth::user()->id == $penalty->added_by) {

            $penalty->delete();
            return response()->json(["message" => " Penalty added successfully"], 201);
        }
        return response()->json(["message" => " Sorry cannot delete penalty"], 201);
    }
}
