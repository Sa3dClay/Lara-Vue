<?php

namespace App\Http\Controllers\API;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::latest()->paginate(6);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();

        $this->validate($request, [
            'name'      =>  'required|string|max:191|unique:products',
            'brand'     =>  'required|string|max:191',
            'price'     =>  'required',
            'quantity'  =>  'required'
        ]);

        if( empty( $request['category'] ) ) {
            $request['category'] = 'default';
        }
        if( empty( $request['descreption'] ) ) {
            $request['descreption'] = 'No descreption';
        }

        if($request->product_pic) {
            $name = time().'.' . explode('/', explode(':', substr($request->product_pic, 0, strpos($request->product_pic, ';')))[1])[1];
            
            \Image::make($request->product_pic)->save(public_path('img/products/').$name);
            $request->merge(['product_pic' => $name]);

        } else {
            $request->merge(['product_pic' => 'no-image.svg']);
        }

        return Product::create([
            'name'          => $request['name'],
            'brand'         => $request['brand'],
            'price'         => $request['price'],
            'quantity'      => $request['quantity'],
            'category'      => $request['category'],
            'descreption'   => $request['descreption'],
            'product_pic'   => $request['product_pic']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
