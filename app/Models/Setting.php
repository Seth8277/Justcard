<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = "key";

    protected $fillable = ['key', 'value'];

    protected $keyType = "string";

    public $incrementing = false;
}
