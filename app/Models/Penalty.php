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
        'source',
        'unit',
        'return_id',
        'pesintutar',
        'daysisid',
        'daysisonay',
        'pdf_url',
        'added_by',


    ];

    protected $with = [
        'vehicle',
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
