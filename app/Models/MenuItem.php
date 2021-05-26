<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'menu_id',
        'added_by',

    ];

    protected $with = [
        'menu',
    ];
    
    public function menu() {

        return $this->belongsTo(Menu::class);
    }
    public function addedBy() {

        return $this->belongsTo(User::class, 'id', 'added_by');
    }
}
