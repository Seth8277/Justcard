<?php

namespace App\Http\Resources;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\Resource;

class Overview extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'NumberOfUsers' => User::all()->count(),
            'TodaysProfit' => Order::where('status', '=', Order::STATUS_PIAD)
                ->whereDay('updated_at', '=', Carbon::today())
                ->sum('price'),
            'NumberOfTodaysOrders' => Order::where('status', '=', Order::STATUS_PIAD)
                ->whereDay('updated_at', '=', Carbon::today())
                ->count(),
        ];
    }
}
