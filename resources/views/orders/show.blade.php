@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Order - {{ $order->id }}</h2>
    <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
    <p><strong>Date:</strong> {{ $order->date }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th><th>Image</th><th>Quantity</th><th>Price</th><th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
               <td class="text-center">
    @if ($item->product && $item->product->image)
        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" width="100">
    @else
        No image
    @endif
</td>

                <td>{{ $item->quantity }}</td>
                <td>{{ $item->formatted_price }}</td>
                <td>{{ $item->formatted_total }}</td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
