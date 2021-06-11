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
        'vehicle_group',
        'brand_model',
        'chassis_number',
        'motor_number',
        'model_year',
        'color',
        'file_number',
        'note',
        'tag',
        'reception_type',
        'delivery_date',
        'asset_number',
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
    public function menuData() {

        return $this->hasMany(MenuData::class);
    }
}
