@extends('layouts.app')
@section('content')


<div class="container">
     <a href="{{route('orders.index')}}" class="btn mb-3" style="background-color: #9B7EBD; color:white;">Back to home</a>

<table class="table table-bordered">

<thead>
    <tr>
        <td>Sl No</td>
        <td>Order NO</td>
        <td>Customer</td>
        <td>Date</td>
        <td>Actions</td>
    </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)

        <tr>
            <td>
                {{$loop->iteration}}
            </td>
            <td>{{$order->id}}</td>
            <td>{{$order->customer_name}}</td>
            <td>{{$order->date}}</td>
            <td>
                <form method="POST" action="{{route('orders.restore',$order->id)}}" style="display: inline">
                    @csrf
                    <button  class="btn btn-success" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;" >Restore</button>
                </form>
                <form method="POST" action="{{route('orders.forceDelete',$order->id)}}" style="display: inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style=" color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;" onClick="return confirm('permanently delete this order?">Delete permanently</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
   
    

</table>

</div>
@endsection