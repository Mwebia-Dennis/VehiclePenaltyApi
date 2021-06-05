<?php

namespace App\Http\Controllers\SystemStatistics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Penalty;

class SystemStatiticsController extends Controller
{
    public function index() {

        //get new vehicles as per today
        $todayTotalVehicles =  Vehicle::whereDate('created_at', Carbon::today())->count();
        //get new penalties as per today
        $todayTotalPenalties =  Penalty::whereDate('created_at', Carbon::today())->count();
        //get new users as per today
        $todayTotalUsers =  User::whereDate('created_at', Carbon::today())->count();
        //get total penalties
        $totalPenalties = Penalty::count();
        //get total vehicles
        $totalVehicles = Vehicle::count();
        //get percentage of new vehicles this month vs previous
        $vehicle = new Vehicle();
        $lastMonthVehicle = $vehicle->whereMonth(
            'created_at', '=', Carbon::now()->subMonth()->month
        )->count();
        $currentMonthVehicle = $vehicle->whereMonth(
            'created_at', '=', Carbon::now()->month
        )->count();
        //get percentage of new penalties this month vs previous
        $penalty = new Penalty();
        $lastMonthPenalties = $penalty->whereMonth(
            'created_at', '=', Carbon::now()->subMonth()->month
        )->count();
        $currentMonthPenalties = $penalty->whereMonth(
            'created_at', '=', Carbon::now()->month
        )->count();
        //get percentage of new users this month vs previous
        $user = new User();
        $lastMonthUsers = $user->whereMonth(
            'created_at', '=', Carbon::now()->subMonth()->month
        )->count();
        $currentMonthUsers = $user->whereMonth(
            'created_at', '=', Carbon::now()->month
        )->count();

        $data = [
            "todayTotalVehicles" => $todayTotalVehicles,
            "todayTotalPenalties" => $todayTotalPenalties,
            "todayTotalUsers" => $todayTotalUsers,
            "totalPenalties" => $totalPenalties,
            "totalVehicles" => $totalVehicles,
            "lastMonthVehicle" => $lastMonthVehicle,
            "currentMonthVehicle" => $currentMonthVehicle,
            "lastMonthPenalties" => $lastMonthPenalties,
            "currentMonthPenalties" => $currentMonthPenalties,
            "lastMonthUsers" => $lastMonthUsers,
            "currentMonthUsers" => $currentMonthUsers,
        ];
        return response()->json($data, 201);
    }
}
