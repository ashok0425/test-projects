<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = file_get_contents(public_path('data.json'));
        return $products;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        unset($request->token);
        try {
            $current_product = file_get_contents(public_path('data.json'));
            $new_product = ['name' => $request->name, 'qty' => $request->qty, 'price' => $request->price, 'created_at' => now()];
            $array_product = json_decode($current_product, true);
            if ($array_product) {
                $array_product[] = $new_product;
                file_put_contents(public_path('data.json'), json_encode($array_product));
            } else {
                $arr = [];
                $arr[] = $new_product;
                file_put_contents(public_path('data.json'), json_encode($arr));
            }

            return response([
                'success' => true,
                'message' => 'Product Created',
            ]);
        } catch (\Throwable $th) {
            return response([
                'success' => false,
                'message' => 'Failed to  create product',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($index)
    {
        $product = file_get_contents(public_path('data.json'));
        $array_product = json_decode($product, true)[$index];
        if($array_product){
            return response([
                'success' => true,
                'message' =>'Fetched product succesfully',
                'data'=>$array_product
            ]);
        }else{
            return response([
                'success' => false,
                'message' => 'Failed to  fetched product',
            ]);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $index=$request->index;
        try{
        $product = file_get_contents(public_path('data.json'));
        $array_product = json_decode($product, true);
        foreach ($array_product as $key => $value) {
            if ($key==$index) {
                $array_product[$key]['name']=$request->name;
                $array_product[$key]['qty']=$request->qty;
                $array_product[$key]['price']=$request->price;
            }
        }
       
        file_put_contents(public_path('data.json'), json_encode($array_product));
        return response([
            'success' => true,
            'message' => 'Product update',
        ]);
    } catch (\Throwable $th) {
        return response([
            'success' => false,
            'message' => 'Failed to  update product',
        ]);
    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
