<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        
        $products = Product::all()->map(function ($product) {
        $product->image_url = asset('storage/' . $product->image);
        return $product;
        });

        $customers = Customer::all();
        return view('orders.create', compact('products', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'address' => 'required',
            'date' => 'required|date',
            'products' => 'required|array',
            'quantities' => 'required|array',
        ]);

        $customer = Customer::find($request->customer_id);

        $order = Order::create([
            'customer_name' => $customer->name,
            'address' => $request->address,
            'date' => $request->date,
        ]);

        foreach ($request->products as $index => $productId) {
            $product = Product::find($productId);
            $quantity = $request->quantities[$index];
            $price = $product->price;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully');
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $products = Product::all();
        $order->load('items');
        return view('orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_name' => 'required',
            'address' => 'required',
            'date' => 'required|date',
            'products' => 'required|array',
            'quantities' => 'required|array',
        ]);

        $order->update([
            'customer_name' => $request->customer_name,
            'address' => $request->address,
            'date' => $request->date,
        ]);

        // Delete existing items and re-insert
        $order->items()->delete();

        foreach ($request->products as $index => $productId) {
            $product = Product::find($productId);
            $quantity = $request->quantities[$index];
            $price = $product->price;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully');
    }

    public function destroy( $id)
    {
        $order= Order::findOrfail($id);
        $order->delete();
        return back()->with('success', 'Order deleted');
    }

    
    public function productForm()
    {
        return view('products.create');
       
    }

        public function productShow(){

            $products= Product::simplepaginate(8);
            return view('products.show',compact('products'));

        }

        public function productEdit($id){
            $product = Product::findOrFail($id); // Assuming Product model
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

        $imagepath = $request->file('image')->store('products', 'public');
        $product->image = $imagepath;
    }

    $product->save();

    return redirect()->back()->with('success', 'Product updated successfully');
}


    public function productDelete($id){
        $products=Product::FindOrFail($id);
        $products->delete();
        return redirect()->back()->with('success','product deleted successfully');
    }
    
    public function productStore(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'price']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.show')->with('success', 'Product added successfully');
    }

public function getImageUrlAttribute()
{
    return asset('storage/' . $this->image);
}

    public function trash(){
        $orders =Order::onlyTrashed()->get();
        return view('orders.trash',compact('orders'));
    }

    public function forceDelete($id){
        $order = Order::onlyTrashed()->findOrfail($id);
        $order->forceDelete();
        return redirect()->route('orders.trash')->with('success','order permanently deleted');
    }

    

    public function restore($id){
        $order = Order::onlyTrashed()->findOrfail($id);
        $order->restore();
        return redirect()->route('orders.index')->with('success','Orders restored successfully');
    }

    public function exportPdf(){
        $orders = Order::with('items.product')->get();

        $pdf = Pdf::loadView('orders.pdf', compact('orders'));
        return $pdf->download('orders.pdf');
    }
}
