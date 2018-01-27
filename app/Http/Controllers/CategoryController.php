<?php

namespace App\Http\Controllers;

use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    public function show(){
        return CategoryResource::collection(Category::all());
    }

    public function store(Request $request,Category $category){
        $data = $this->validate($request,[
            'id' => 'nullable|exists:category',
            'name' => 'required|string',
            'weight' => 'required|integer|max:100|min:0',
            'photo' => 'nullable|url'
        ]);

        if (empty($data['photo']))
            $data['photo'] = $this->randomPhoto();
        if ($category->fill($data)->save())
            return $this->created();
        else
            return $this->internalError();
    }

    public function products(Category $category){
        return new ProductResource($category->products());
    }

    public function randomPhoto(){
        $photos = [
            "https://i.loli.net/2018/01/24/5a681357af13d.png",
            "https://i.loli.net/2018/01/24/5a681357bdcd7.png",
            "https://i.loli.net/2018/01/24/5a681357bdc9e.png",
            "https://i.loli.net/2018/01/24/5a681357c4a03.png",
            "https://i.loli.net/2018/01/24/5a681357c5557.png",
            "https://i.loli.net/2018/01/24/5a681357c70c1.png",
            "https://i.loli.net/2018/01/24/5a681357cbb70.png",
            "https://i.loli.net/2018/01/24/5a681357cc394.png",
            "https://i.loli.net/2018/01/24/5a681357d5554.png",
            "https://i.loli.net/2018/01/24/5a681357d5c51.png",
            "https://i.loli.net/2018/01/24/5a681486a9059.png",
            "https://i.loli.net/2018/01/24/5a681486cfeab.png",
            "https://i.loli.net/2018/01/24/5a681486db07b.png",
            "https://i.loli.net/2018/01/24/5a681486dbbe9.png",
        ];
        $random = mt_rand(0, count($photos) -1);
        return $photos[$random];

    }
}
