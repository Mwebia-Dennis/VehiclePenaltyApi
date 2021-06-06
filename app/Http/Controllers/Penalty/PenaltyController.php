<?php

namespace App\Http\Controllers\Penalty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penalty;

class PenaltyController extends Controller
{
    public function index()
    {
        $penalty = new Penalty();
        if(request()->has('sort_by')) {
            $penalty = $penalty->orderBy(request()->sort_by, 'DESC');
        }
        
        $perPage = (request()->has('per_page'))?request()->per_page:env('PER_PAGE');
        return response()->json($penalty->paginate($perPage));
    }
}
