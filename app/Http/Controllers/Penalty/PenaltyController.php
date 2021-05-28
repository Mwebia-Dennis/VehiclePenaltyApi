<?php

namespace App\Http\Controllers\Penalty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penalty;

class PenaltyController extends Controller
{
    public function index()
    {
        return response()->json(Penalty::all(), 201);
    }
}
