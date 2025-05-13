<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Existing data
        $customerCount = \App\Models\User::count();
        $orderCount = \App\Models\Order::count();
        $totalRevenue = \App\Models\OrderItem::whereHas('order', function($query) {
            $query->whereNull('deleted_at');
        })->sum('price');
        
        $fomattedRevenue = number_formatter($totalRevenue);
        
        $topSellingProducts = \App\Models\OrderItem::select('product_id', \DB::raw('SUM(quantity) as total_sold'), \DB::raw('SUM(price) as total_revenue'))
            ->whereHas('order', function($query) {
                $query->whereNull('deleted_at');
            })
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->take(3)
            ->get();
        
        foreach ($topSellingProducts as $product) {
            $productDetails = Product::find($product->product_id);
            $product->name = $productDetails->name ?? 'Unknown Product';
            $product->formatted_price = number_formatter($productDetails->price ?? 0);
            $product->image_url = asset('storage/products/medium_' . ($productDetails->image ?? 'default.png'));
            $product->total_units_sold = $product->total_sold;
            $product->formatted_revenue = number_formatter($product->total_revenue);
        }
        
         $totalSales = \App\Models\OrderItem::sum('price');
        $totalOrders = \App\Models\Order::count();
        $newCustomers = User::where('created_at', '>=', now()->subDays(30))->count();

        $salesChartData = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.price) as total')
            ->whereNull('orders.deleted_at')
            ->groupBy(DB::raw('DATE(orders.created_at)'))
            ->orderBy('date')
            ->get();

             DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

             $ordersChartData = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
        ->whereNull('deleted_at')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    $customersChartData = User::selectRaw('DATE(created_at) as date, COUNT(*) as total')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
       
        
        return view('admin.dashboard', compact(
            'customerCount',
            'orderCount', 
            'fomattedRevenue',
            'topSellingProducts',
            'totalSales',
            'totalOrders',
            'newCustomers',
            'salesChartData',
             'ordersChartData',
        'customersChartData'
            
        ));
    }
    
  
}