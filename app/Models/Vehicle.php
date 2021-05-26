<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'plate_number',
        'user_id',
        'duty_location',
        'unit',
        'added_by',

    ];

    protected $with = [
        'user', 'addedBy',
    ]

    
    public function user() {

        return $this->belongsTo(User::class);
    }
    public function addedBy() {

        return $this->belongsTo(User::class, 'id', 'added_by');
    }
    public function penalty() {

        return $this->hasMany(Penalty::class);
    }
}
