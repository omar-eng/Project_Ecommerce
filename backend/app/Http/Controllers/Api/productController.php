<?php

namespace App\Http\Controllers\Api;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class productController extends Controller
{
    
    public function getProducts(){ //buyer
        $userId = Auth::id();
// dd($userId);
    // Retrieve posts where user_id is not the authenticated user's ID
    $products = Product::where('user_id', '!=', $userId)->get();
    $products->each(function ($product) {
    $product->image_path = asset('storage/' . $product->image);
});
    return response()->json($products);
    }

    public function getMyProducts(){ //seller
        $userId = Auth::id();
// dd($userId);
    // Retrieve posts where user_id is not the authenticated user's ID
    $products = Product::where('user_id', $userId)->get();
    $products->each(function ($product) {
    $product->image_path = asset('storage/' . $product->image);
});
    return response()->json($products);
    }

    public function addProduct(Request $request){ //seller
        $product = new Product();
        $imagePath = $request->file('image')->store('images/posts', 'public');
        $product->description = $request->description;
        $product->image = $imagePath;
        $product->title = $request->title;
        $product->price = $request->price;
        if($request->quantity) $product->quantity = $request->quantity;
        $product->user_id = $request->user()->id;
        $product->save();
        $product->image_path = asset('storage/' . $imagePath);
        return $product;
    }


    public function updateProduct($id, Request $request){ //seller
        $product = Product::find($id);
        // dd($request->description);
        if($request->description) $product->description = $request->description;
        if($request->image) $product->image = $request->image;
        if($request->title) $product->title = $request->title;
        if($request->quantity) $product->quantity = $request->quantity;
        $product->save();
        return $product;
    }


    public function getProduct($id, Request $request){ //both
        $Product = Product::find($id);
        $Product->image_path = asset('storage/' . $Product->image);
        // $post->save();
        return $Product;
    }


    public function deleteProduct($id){ //seller
        $Product = Product::find($id);
        $Product->delete();
        return $Product;
    }

    // In your controller or route
// public function showImage($id)
// {
//     $post = Post::find($id);
// dd($post->image);
//     if (!$post) {
//         abort(404); // Image not found
//     }

//     // Set appropriate headers
//     header('Content-Type: image/jpeg');
//     header('Content-Length: ' . strlen($post->image));
// dd($post->image);
//     // Output the image data
//     echo $post->image;
// }

}