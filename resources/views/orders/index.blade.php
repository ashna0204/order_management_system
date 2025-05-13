@extends('layouts.app')

@section('content')
<div class="container" >
   
        @role('user')
    <a href="{{route('orders.create')}}" class="btn btn-primary mb-3">Add Orders</a>
    @endrole
    
    @role('admin')
    <a href="{{ route('orders.exportPdf') }}" class="btn btn-primary mb-3">Export Orders to PDF</a>
     @endrole

     <h1>Order List</h1>
    <table class="table table-striped" style="color:#F5F5F5;">
        <thead>
            <tr>
                <th>Sl. No</th>
                <th>Order No</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Total Price</th>
                <th>Image</th>
                <th>Action</th>
                <th>Status</th>
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
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info">
                        <i class="fas fa-eye"></i>
                    </a>
                    @role('user')
                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning"><i class="fas fa-pen"></i></a>
                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                     @endrole
                </td>
                @role('user')
                <td>
                    <span class="badge 
                        @if($order->status == 'pending') bg-warning 
                        @elseif($order->status == 'confirmed') bg-primary 
                        @elseif($order->status == 'processing') bg-info 
                        @elseif($order->status == 'shipped') bg-secondary 
                        @elseif($order->status == 'delivered') bg-success 
                        @elseif($order->status == 'cancelled') bg-danger 
                        @endif" style="width: 100px; display: inline-block; text-align: center;">
                        {{$order->status}}
                    </span>
                </td>
                @endrole
                @role('admin')
                <td>
                    <form action="{{route('orders.updateStatus', $order->id)}}" method="post" style="display:inline">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                           <option value="pending" {{$order->status == 'pending' ? 'selected': ''}}>Pending</option>
                           <option value= "confirmed" {{$order->status== 'confirmed' ? 'selected' : '' }}>Confirmed</option>

                           <option value="processing" {{$order->status == 'processing' ? 'selected' : '' }}>Processing</option>

                           <option value="shipped" {{$order->status == 'shipped' ?  'selected' : '' }}>Shipped</option>

                           <option value="delivered" {{$order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>

                           <option value="cancelled" {{$order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>

                    
</form>
                </td>
                @endrole
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection