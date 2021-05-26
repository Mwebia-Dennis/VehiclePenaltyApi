<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuData extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'data',
        'menu_id',
        'added_by',

    ];

    protected $casts = [
        'data' => 'array'
    ];

    protected $with = [
        'menu'
    ];

    
    public function menu() {

        return $this->belongsTo(MenuItem::class);
    }
    public function addedBy() {

        return $this->belongsTo(User::class, 'id', 'added_by');
    }
}
