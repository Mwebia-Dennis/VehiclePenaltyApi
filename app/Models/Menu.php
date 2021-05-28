<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;


    protected $table = "menus";

    protected $fillable = [
        'name',
        'added_by',

    ];

    public function getTableName(){
        return $this->table;
    }
    public function menuItem() {

        return $this->hasMany(MenuItem::class);
    }
    public function addedBy() {

        return $this->belongsTo(User::class, 'id', 'added_by');
    }
    public function menuData() {

        return $this->hasMany(MenuData::class);
    }

}
