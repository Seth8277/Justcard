<?php

namespace App\Http\Resources;

use App\Models\Order as OrderModel;
use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\Card as CardResource;

class Order extends Resource
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
            'id' => $this->id,
            'price' => $this->price,
            'product' => new ProductResource($this->product),
            'status'  => $this->status,
            'number'  => $this->number,
            'cards' => $this->when($this->status !== OrderModel::ORDER_PAID ,
                                             CardResource::collection($this->cards))
        ];
    }
}
