<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'weight', 'photo'];

    public function products(){
        $this->hasMany(Product::class);
    }
}
