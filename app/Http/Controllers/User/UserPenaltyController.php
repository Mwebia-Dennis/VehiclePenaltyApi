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

        
        $user = $user->myPenalty();
        if(request()->has('sort_by')) {
            $user = $user->orderBy(request()->sort_by, 'DESC');
        }
        
        $perPage = (request()->has('per_page'))?request()->per_page:env('PER_PAGE');
        return response()->json($user->paginate($perPage));
    }

    public function store(Request $request, $user_id)
    {
        $request->validate($rules = [
    
            'vehicle_id' => 'required|integer',
            'receipt_number' => 'required',
            'penalty_date' => 'required',
            'payment_date'  => 'required',
            'notification_date' => 'required',
            'penalty_hour' => 'required',
            'penalty_article' => 'required|max:350',
            'penalty' => 'required|max:350',
            'paying' => 'required|max:350',
            'note' => 'required|max:350',
            'source' => 'required|max:350',
            'unit' => 'required|max:350',
            'return_id' => 'required|max:350',
            'pesintutar' => 'required|max:350',
            'daysisid' => 'required|max:350',
            'daysisonay' => 'required|max:350',
            'status' => 'required',
            'pdf' => 'mimes:pdf|max:50048',

        ]);
        $penalty = new Penalty();
        $penalty->receipt_number = $request->receipt_number;
        $penalty->penalty_date = $request->penalty_date;
        $penalty->payment_date = $request->payment_date;
        $penalty->status = $request->status;
        $penalty->notification_date = $request->notification_date;
        $penalty->penalty_hour = $request->penalty_hour;
        $penalty->penalty_article = $request->penalty_article;
        $penalty->penalty = $request->penalty;
        $penalty->paying = $request->paying;
        $penalty->source = $request->source;
        $penalty->unit = $request->unit;
        $penalty->note = $request->note;
        $penalty->return_id = $request->return_id;
        $penalty->pesintutar = $request->pesintutar;
        $penalty->daysisid = $request->daysisid;
        $penalty->daysisonay = $request->daysisonay;

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
            'receipt_number' => 'required',
            'penalty_date' => 'required',
            'payment_date'  => 'required',
            'notification_date' => 'required',
            'penalty_hour' => 'required',
            'penalty_article' => 'required|max:350',
            'penalty' => 'required|max:350',
            'paying' => 'required|max:350',
            'source' => 'required|max:350',
            'unit' => 'required|max:350',
            'return_id' => 'required|max:350',
            'note' => 'required|max:350',
            'pesintutar' => 'required|max:350',
            'daysisid' => 'required|max:350',
            'daysisonay' => 'required|max:350',
            'status' => 'required',

        ]);
        $penalty->receipt_number = $request->receipt_number;
        $penalty->penalty_date = $request->penalty_date;
        $penalty->payment_date = $request->payment_date;
        $penalty->status = $request->status;
        $penalty->vehicle_id = $request->vehicle_id;
        $penalty->vehicle()->associate($penalty->vehicle_id);
        $penalty->note = $request->note;
        $penalty->notification_date = $request->notification_date;
        $penalty->penalty_hour = $request->penalty_hour;
        $penalty->penalty_article = $request->penalty_article;
        $penalty->penalty = $request->penalty;
        $penalty->paying = $request->paying;
        $penalty->source = $request->source;
        $penalty->unit = $request->unit;
        $penalty->return_id = $request->return_id;
        $penalty->pesintutar = $request->pesintutar;
        $penalty->daysisid = $request->daysisid;
        $penalty->daysisonay = $request->daysisonay;

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
            return response()->json(["message" => " Penalty deleted successfully"], 201);
        }
        return response()->json(["message" => " Sorry cannot delete penalty"], 201);
    }
}
