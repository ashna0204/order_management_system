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


class OrderController extends Controller
{
     public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')){
            // Admin can view all orders
            $orders = Order::with('items.product')->latest()->get();
        } else {
            // Regular users can only view their own orders
            $orders = Order::with('items.product')->where('user_id', $user->id)->latest()->get();
        }

        foreach ($orders as $order) {
            $order->total_price = 0;
            foreach ($order->items as $item) {
                $item->formatted_price = number_formatter($item->price);
                $item->formatted_total = number_formatter($item->price * $item->quantity);
                $order->total_price += $item->price * $item->quantity;  // Add item total to order total
                $item->image_url = asset('storage/products/medium_' . $item->product->image);  // Add image URL for each product
            }
            $order->formatted_total_price = number_formatter($order->total_price);  // Format total price
        }

        return view('orders.index', compact('orders'));
    }

    // public function index()
    // {
        
    //     $orders = Order::with('items.product')->latest()->get();
    //        foreach ($orders as $order) {
    //     $order->total_price = 0;
    //     foreach ($order->items as $item) {
    //         $item->formatted_price = number_formatter($item->price);
    //         $item->formatted_total = number_formatter($item->price * $item->quantity);
    //         $order->total_price += $item->price * $item->quantity;  // Add item total to order total
    //         $item->image_url = asset('storage/products/medium_' . $item->product->image);  // Add image URL for each product
    //     }
    //     $order->formatted_total_price = number_formatter($order->total_price);  // Format total price
    // }
    //     return view('orders.index', compact('orders'));
    // }

    public function create()
    {
        
        $products = Product::all()->map(function ($product) {
        $product->image_url = asset('storage/products/medium_' . $product->image);

        $product->formatted_price =  number_formatter($product->price);
        $product->formatted_totalprice =  number_formatter($product->totalprice);
        return $product;
        });

        $customers = Customer::all();
        return view('orders.create', compact('products', 'customers'));
    }

    public function store(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_address' => 'required|string',
        'date' => 'required|date',
        'products' => 'required|array',
        'quantities' => 'required|array',
    ]);

    $order = Order::create([
        'user_id' => auth()->id(),
        'customer_name' => $request->customer_name,
        'address' => $request->customer_address,
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
        foreach($order->items as $item){
         $item->formatted_price = number_formatter($item->price);  
         $item->formatted_total = number_formatter($item->price*$item->quantity);  
        }
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $products = Product::all()->map(function ($product) {
            $product->image_url = asset('storage/products/medium_' . $product->image);
            $product->formatted_price = number_formatter($product->price); // Add formatted price
            return $product;
        });

        $order->load('items.product');
        foreach ($order->items as $item) {
            $item->image_url = asset('storage/products/medium_' . $item->product->image);
            $item->formatted_price = number_formatter($item->price); // Add formatted price
            $item->formatted_total = number_formatter($item->price * $item->quantity); // Add formatted total
        }

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

    
   



    public function trash(){
        $user = auth()->user();
        $orders = Order::onlyTrashed()->where('user_id', $user->id)->get();
        return view('orders.trash', compact('orders'));
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
    foreach($orders as $order)
       foreach($order->items as $item){
        $item->formatted_price = number_formatter($item->price);
        $item->formatted_total = number_formatter($item->price * $item->quantity);
    }

        $pdf = Pdf::loadView('orders.pdf', compact('orders'));
        return $pdf->download('orders.pdf');
    }

    public function updateStatus(Request $request, Order $order){
        $request->validate([ 'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled']);

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated');
    }
}
