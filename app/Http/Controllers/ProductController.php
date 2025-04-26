<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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

        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $tempPath = storage_path('app/public/temp/' . $imageName);
        $image->move(dirname($tempPath), $imageName);

        $imageManager = new ImageManager(new Driver());

        $basePath = public_path('storage/products/');
        $sizes = [
            'small' => [480, 320],
            'medium' => [768, 512],
            'large' => [1024, 768],
        ];

        foreach ($sizes as $key => [$width, $height]) {
            $resized = $imageManager->read($tempPath)->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $resized->save($basePath . "{$key}_$imageName");
        }

        unlink($tempPath);

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imageName, // only base name
        ]);

        return redirect()->route('products.show')->with('success', 'Product added successfully');
    }

    public function productShow()
    {
        $products = Product::simplePaginate(8);

        foreach ($products as $product) {
            $product->formatted_price = number_formatter($product->price);
        }

        return view('products.show', compact('products'));
    }

    public function productEdit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function productUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        ]);

        $product->name = $request->name;
        $product->price = $request->price;

        if ($request->hasFile('image')) {
            if ($product->image) {
                $basePath = 'public/products/';
                $sizes = ['small', 'medium', 'large'];
                foreach ($sizes as $size) {
                    Storage::delete($basePath . "{$size}_" . $product->image);
                }
            }

            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $tempPath = storage_path('app/public/temp/' . $imageName);
            $image->move(dirname($tempPath), $imageName);

            $imageManager = new ImageManager(new Driver());
            $basePath = public_path('storage/products/');
            $sizes = [
                'small' => [480, 320],
                'medium' => [768, 512],
                'large' => [1024, 768],
            ];

            foreach ($sizes as $key => [$width, $height]) {
                $resized = $imageManager->read($tempPath)->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $resized->save($basePath . "{$key}_$imageName");
            }

            unlink($tempPath);
            $product->image = $imageName;
        }

        $product->save();

        return redirect()->route('products.show')->with('success', 'Product updated successfully');
    }

    public function productDelete($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image) {
            $basePath = 'public/products/';
            $sizes = ['small', 'medium', 'large'];
            foreach ($sizes as $size) {
                Storage::delete($basePath . "{$size}_" . $product->image);
            }
        }

        $product->delete();
        return redirect()->back()->with('success', 'Product deleted successfully');
    }
    
}
