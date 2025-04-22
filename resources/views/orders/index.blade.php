@extends('layouts.app')

@section('content')
<div class="container">
   
        
    <a href="{{route('orders.create')}}" class="btn btn-primary mb-3">Add Orders</a>
     <a href="{{ route('orders.exportPdf') }}" class="btn btn-primary mb-3">Export Orders to PDF</a>

     <h1>Order List</h1>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th>Sl. No</th>
                <th>Order No</th>
                <th>Date</th>
                <th>Customer</th>
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
                <td>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info">View</a>
                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection