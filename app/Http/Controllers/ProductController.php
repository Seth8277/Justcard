<?php

namespace App\Http\Controllers;

use App\Events\OrderCreated;
use App\Http\Resources\Card as CardResource;
use App\Http\Resources\Product as ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Response;
use Webpatser\Uuid\Uuid;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        if ($product->exists)
            return new ProductResource($product);
        else
            return $this->failed('产品不存在', Response::HTTP_NOT_FOUND);
    }

    public function store(Request $request, Product $product)
    {
        $data = $this->validate($request, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'category_id' => 'required|integer|exists:categories,id'
        ]);

        if ($product->fill($data)->save())
            return $this->created();
        else
            return $this->internalError();
    }

    public function buy(Request $request, Product $product)
    {
        $stock = $product->cards->count();

        $data = $this->validate($request, [
            'email' => 'required|email|max:255',
            'number' => "required|integer|min:1|max:{$stock}",
        ]);

        // 生成订单ID
        $data['id'] = Uuid::generate();

        // 计算价格
        $data['price'] = $product->price * $data['number'];

        if (Auth::check())
            $data['user_id'] = Auth::user()->id;

        if ($order = $product->orders()->create($data)) {
            // 触动事件
            OrderCreated::dispatch($order);

            return $this->success(['order_id' => "{$data['id']}"], Response::HTTP_CREATED);
        } else {
            return $this->internalError();

        }
    }

    public function cards(Product $product)
    {
        return CardResource::collection($product->cards);
    }

    public function add_cards(Product $product, Request $request)
    {
        $data = $this->validate($request, [
            'cards' => 'required|json'
        ]);

        $cards = json_decode($data['cards']);
        foreach ($cards as $number => $key) {
            if (!empty($key))
                $product->cards()->create([
                    'number' => $number,
                    'key' => $key
                ]);
            else
                $product->cards()->create([
                    'key' => $number
                ]);
        }
        return $this->created();
    }
}
