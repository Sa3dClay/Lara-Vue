<?php

namespace App\Http\Controllers\API;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

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
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $this->validate($request, [
            'name'      =>  'required|string|max:191|unique:products,name,'.$product->id,
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

        $currentPic = $product->product_pic;

        if($request->product_pic != $currentPic) {
            $name = time().'.' . explode('/', explode(':', substr($request->product_pic, 0, strpos($request->product_pic, ';')))[1])[1];
            
            \Image::make($request->product_pic)->save(public_path('img/products/').$name);
            $request->merge(['product_pic' => $name]);

            $proPic = public_path('img/products/').$currentPic;
            if(file_exists($proPic) && $currentPic != "no-image.svg") {
                @unlink($proPic);
            }
        }

        $product->update($request->all());

        return ['message' => 'Product updated successfully'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('isAdmin');

        $product = Product::findOrFail($id);

        $proPic = $product->product_pic;
        $picPath = public_path('img/products/').$proPic;
        
        if(file_exists($picPath) && $proPic != "no-image.svg") {
            @unlink($picPath);
        }

        $product->delete();

        return ['message' => 'Product deleted successfully'];
    }
}
