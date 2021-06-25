<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;
    
    
    protected $table = "penalties";

    protected $fillable = [
        'vehicle_id',
        'receipt_number',
        'penalty_date',
        'payment_date',
        'status',
        'notification_date',
        'penalty_hour',
        'penalty_article',
        'penalty',
        'paying',
        'cancelation_status',
        'unit',
        'company',
        'request_no',
        'unit_no',
        'imm_no',
        'pdf_url',
        'added_by',
        'name',
        'registration_date',
        'arrival_date',
        'decision_date',
        'payment_amount',
        'image_url',


    ];

    protected $with = [
        'vehicle',
        'addedBy',
    ];
    
    
    public function getTableName(){
        return $this->table;
    }
    
    public function vehicle() {

        return $this->belongsTo(Vehicle::class);
    }
    public function addedBy() {

        return $this->belongsTo(User::class, 'added_by', 'id');
    }
}
