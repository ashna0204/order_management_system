<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use App\Models\Image;



class ProductController extends Controller
{
     public function productForm()
    {
        return view('products.create');
       
    }

        
public function productStore(Request $request)
{
    $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'image' => 'required|image|mimes:png,jpg,jpeg,svg,gif|max:2048'
    ]);

    //upload functionality
    $image = $request->file('image');
    //generate unique image name
    $imageName = time() . '.' . $image->getClientOriginalExtension();

    // Move original file temporarily
    $tempPath = storage_path('app/public/temp/' . $imageName);
    $image->move(dirname($tempPath), $imageName);

    //create new manager instance
    $imageManager = new ImageManager(new Driver());
    // Reading the image from local dir & Resize and move to final destination
    $resizedImage = $imageManager->read($tempPath)->resize(150, 150);
    $resizedImage->save(public_path('storage/products/' . $imageName));

    // Remove temp file (optional)
    unlink($tempPath);

    Product::create([
        'name' => $request->name,
        'price' => $request->price,
        'image' => 'products/' . $imageName,
    ]);

    return redirect()->route('products.show')->with('success', 'Product added successfully');
}


        public function productShow(){

            $products= Product::simplepaginate(8);

            foreach($products as $product){
                $product->formatted_price = number_formatter($product->price);
                
            }
            return view('products.show',compact('products'));

        }

        public function productEdit($id){
            $product = Product::findOrFail($id); 
    
            return view('products.edit', compact('product'));


        }


 public function productUpdate(Request $request, $id)
    {
    $product = Product::findOrFail($id);

    $product->name = $request->name;
    $product->price = $request->price;

    // Only update image if a new one is uploaded
    if ($request->hasFile('image')) {
        if ($product->image && Storage::exists('public/' . $product->image)) {
            Storage::delete('public/' . $product->image);
        }

         $request->validate([
        'name' => 'required',
        'price' => 'required|numeric',
        'image' => 'required|image|mimes:png,jpg,jpeg,svg,gif|max:2048'
    ]);

    //upload functionality
    $image = $request->file('image');
    //generate unique image name
    $imageName = time() . '.' . $image->getClientOriginalExtension();

    // Move original file temporarily
    $tempPath = storage_path('app/public/temp/' . $imageName);
    $image->move(dirname($tempPath), $imageName);

    //create new manager instance
    $imageManager = new ImageManager(new Driver());
    // Reading the image from local dir & Resize and move to final destination
    $resizedImage = $imageManager->read($tempPath)->resize(150, 150);
    $resizedImage->save(public_path('storage/products/' . $imageName));

    // Remove temp file (optional)
    unlink($tempPath);
    $product->image = 'products/' . $imageName;
    }

    $product->save();

    return redirect()->route('products.show')->with('success', 'Product updated successfully');
}


    public function productDelete($id){
        $products=Product::FindOrFail($id);
        $products->delete();
        return redirect()->back()->with('success','product deleted successfully');
    }
    


public function getImageUrlAttribute()
{
    return asset('storage/' . $this->image);
}
}
