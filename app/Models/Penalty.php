<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'vehicle_id',
        'penalty_type',
        'receipt_number',
        'penalty_date',
        'payment_date',
        'status',
        'pdf_url',
        'added_by',

    ];

    protected $with = [
        'vehicle',
    ];
    
    
    public function vehicle() {

        return $this->belongsTo(Vehicle::class);
    }
    public function addedBy() {

        return $this->belongsTo(User::class, 'added_by', 'id');
    }
}
