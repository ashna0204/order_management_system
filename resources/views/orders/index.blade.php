@extends('layouts.app')

@section('content')
<div class="container">
   
        @role('user')
    <a href="{{route('orders.create')}}" class="btn btn-primary mb-3">Add Orders</a>
    @endrole
     <a href="{{ route('orders.exportPdf') }}" class="btn btn-primary mb-3">Export Orders to PDF</a>

     <h1>Order List</h1>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Sl. No</th>
                <th>Order No</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Total Price</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->id }}</td>
                <td>{{ $order->date }}</td>
                <td>{{ $order->customer_name }}</td>
                  <td>{{ $order->formatted_total_price }}</td>
                 <td>
                @foreach($order->items as $item)
                <div>
                    <!-- Display product image -->
                    <img src="{{ $item->image_url }}" alt="{{ $item->product->name }}" style="width: 50px; height: auto;">
                    
                </div>
                @endforeach
            </td>
          
                <td>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info">View</a>
                    @role('user')
                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                     @endrole
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection