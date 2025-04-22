@extends('layouts.app')
@section('content')
<a href="{{route('products.create')}}" class="btn btn-primary mb-3">Add Products</a>
<div class="container">
    <div class="row">
   
    @foreach($products as $product)
    <div class="col-md-3 mb-3 d-flex">
        <div class="card w-100">
    @if($product->image)
  <img class="card-img-top" src="{{asset('storage/' .$product->image)}}" alt="Card image cap">
  @else
  <img class="card-img-top" src="{{asset('images/default')}}" alt="card image">
  @endif
  <div class="card-body">
    <h5 class="card-title"> {{$product->name}}</h5>
    <p class="card-text">${{$product->price}}</p>
    <a href="{{route('products.delete',$product->id)}}" class="btn btn-primary">Delete</a>
    <a href="{{route('products.edit',$product->id)}}" class="btn btn-primary">Edit</a>
  </div>
  
  </div>
  
</div>

  @endforeach

    
</div>
<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>

</div>
@endsection