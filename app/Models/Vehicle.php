<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    
    
    protected $table = "vehicles";

    protected $fillable = [
        'plate_number',
        'owner_name',
        'owner_surname',
        'duty_location',
        'unit',
        'added_by',

    ];

    protected $with = [
        'addedBy',
    ];

    public function getTableName(){
        return $this->table;
    }
    
    public function addedBy() {

        return $this->belongsTo(User::class, 'added_by', 'id');
    }
    public function penalty() {

        return $this->hasMany(Penalty::class);
    }
}
