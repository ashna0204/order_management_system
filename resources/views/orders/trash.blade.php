@extends('layouts.app')
@section('content')


<div class="container">
     <a href="{{route('orders.index')}}" class="btn btn-primary mb-3">Back to home</a>

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
                    <button  class="btn btn-success">Restore</button>
                </form>
                <form method="POST" action="{{route('orders.forceDelete',$order->id)}}" style="display: inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onClick="return confirm('permanently delete this order?">Delete permanently</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
   
    

</table>

</div>
@endsection