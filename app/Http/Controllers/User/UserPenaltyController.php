<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penalty;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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
            'penalty_article' => 'max:350',
            'penalty' => 'max:350',
            'paying' => 'max:350',
            'note' => 'max:350',
            'source' => 'max:350',
            'unit' => 'max:350',
            'return_id' => 'max:350',
            'pesintutar' => 'max:350',
            'daysisid' => 'max:350',
            'daysisonay' => 'max:350',
            'pdf' => 'mimes:pdf|max:50048',

        ]);
        $penalty = new Penalty();
        $penalty->receipt_number = $request->has('receipt_number')?$request->receipt_number:"";
        $penalty->penalty_date = $request->has('penalty_date')?$request->penalty_date:Carbon::now();
        $penalty->payment_date = $request->has('payment_date')?$request->payment_date:Carbon::now();
        $penalty->status = $request->has('status')?$request->status:"";
        $penalty->notification_date = $request->has('notification_date')?$request->notification_date:Carbon::now();
        $penalty->penalty_hour = $request->has('penalty_hour')?$request->penalty_hour:Carbon::now();
        $penalty->penalty_article = $request->has('penalty_article')?$request->penalty_article:"";
        $penalty->penalty = $request->has('penalty')?$request->penalty:"";
        $penalty->paying = $request->has('paying')?$request->paying:"";
        $penalty->source = $request->has('source')?$request->source:"";
        $penalty->unit = $request->has('unit')?$request->unit:"";
        $penalty->note = $request->has('note')?$request->note:"";
        $penalty->return_id = $request->has('return_id')?$request->return_id:"";
        $penalty->pesintutar = $request->has('pesintutar')?$request->pesintutar:"";
        $penalty->daysisid = $request->has('daysisid')?$request->daysisid:"";
        $penalty->daysisonay = $request->has('daysisonay')?$request->daysisonay:"";

        $penalty->vehicle_id = $request->vehicle_id;
        $penalty->vehicle()->associate($penalty->vehicle_id);

        $user = Auth::user();
        $penalty->added_by = $user->id;
        $penalty->addedBy()->associate($penalty->added_by);

        $penalty->pdf_url = '';
        if($request->hasFile('pdf')) {
            $extension = $request->has('equipment')?$request->File('pdf')->getClientOriginalExtension():"";
            $pdfPath = md5(uniqid()). $request->vehicle_id.'.'.$extension;
            $pdf_url = $request->has('equipment')?$request->File('pdf')->storeAs('public/pdf', $pdfPath):"";
            $pdf_url= 'storage'. substr($pdf_url,strlen('public'));
    
            $penalty->pdf_url = asset($pdf_url); 
        }

        $penalty->save();      

        return response()->json(["message" => " Penalty added successfully"], 201);
    }

    public function update(Request $request,$user_id, Penalty $penalty)
    {
        $request->validate($rules = [
    
            'vehicle_id' => 'required|integer',
            'penalty_article' => 'max:350',
            'penalty' => 'max:350',
            'paying' => 'max:350',
            'note' => 'max:350',
            'source' => 'max:350',
            'unit' => 'max:350',
            'return_id' => 'max:350',
            'pesintutar' => 'max:350',
            'daysisid' => 'max:350',
            'daysisonay' => 'max:350',

        ]);
        $penalty->receipt_number = $request->has('receipt_number')?$request->receipt_number:$penalty->receipt_number;
        $penalty->penalty_date = $request->has('penalty_date')?$request->penalty_date:$penalty->penalty_date;
        $penalty->payment_date = $request->has('payment_date')?$request->payment_date:$penalty->payment_date;
        $penalty->status = $request->has('status')?$request->status:$penalty->status;
        $penalty->notification_date = $request->has('notification_date')?$request->notification_date:$penalty->notification_date;
        $penalty->penalty_hour = $request->has('penalty_hour')?$request->penalty_hour:$penalty->penalty_hour;
        $penalty->penalty_article = $request->has('penalty_article')?$request->penalty_article:$penalty->penalty_article;
        $penalty->penalty = $request->has('penalty')?$request->penalty:$penalty->penalty;
        $penalty->paying = $request->has('paying')?$request->paying:$penalty->paying;
        $penalty->source = $request->has('source')?$request->source:$penalty->source;
        $penalty->unit = $request->has('unit')?$request->unit:$penalty->unit;
        $penalty->note = $request->has('note')?$request->note:$penalty->note;
        $penalty->return_id = $request->has('return_id')?$request->return_id:$penalty->return_id;
        $penalty->pesintutar = $request->has('pesintutar')?$request->pesintutar:$penalty->pesintutar;
        $penalty->daysisid = $request->has('daysisid')?$request->daysisid:$penalty->daysisid;
        $penalty->daysisonay = $request->has('daysisonay')?$request->daysisonay:$penalty->daysisonay;
        
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
