<?php

namespace App\Http\Controllers;

use App\Http\Resources\Overview;

class OverviewController extends Controller
{
    public function show(){
        return new Overview([]);
    }
}
