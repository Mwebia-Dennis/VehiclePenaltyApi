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
        //get weekly stats
        $penalties1 = new Penalty();
        $__vehicle = new Vehicle();
        $vehicleWeeklydata = [];
        $penaltyWeeklydata = [];

        for ($i=0; $i < 7; $i++) { 
            $carbon = Carbon::today()->subDays( $i+1 );
            $penaltyWeeklydata[] = [$carbon->format('l') => $penalties1->whereDate('created_at' , '=', $carbon)];
            $vehicleWeeklydata[] = [$carbon->format('l') => $__vehicle->whereDate('created_at' , '=', $carbon)];
        }
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
            "vehicleWeeklydata" => $vehicleWeeklydata,
            "penaltyWeeklydata" => $penaltyWeeklydata,
            "vehicleMonthlyIncrease" => $this->getPercentage($lastMonthVehicle,$currentMonthVehicle),
            "penaltiesMonthlyIncrease" => $this->getPercentage($lastMonthPenalties,$currentMonthPenalties),
            "usersMonthlyIncrease" => $this->getPercentage($lastMonthUsers,$currentMonthUsers),
        ];
        return response()->json($data, 201);
    }

    private function getPercentage($oldValue, $newValue) {

        if($oldValue != 0) {
            //avoid divisibility error by 0
            return (($newValue - $oldValue)/$oldValue) * 100;
        }
        return 0;
    }
}
