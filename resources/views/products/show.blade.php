@extends('layouts.app')
@section('content')
@role('admin')
<a href="{{route('products.create')}}" class="btn btn-primary mb-3">Add Products</a>
@endrole
<div class="container">
    <div class="row">
   
    @foreach($products as $product)
    <div class="col-md-3 mb-3 d-flex">
        <div class="card w-100">
   @if($product->image)
            <picture>
    <source 
        srcset="{{ asset('storage/products/small_' . $product->image) }}" 
        media="(max-width: 600px)">
    <source 
        srcset="{{ asset('storage/products/medium_' . $product->image) }}" 
        media="(max-width: 1024px)">
    <img 
        src="{{ asset('storage/products/large_' . $product->image) }}" 
        alt="{{ $product->name }}" 
        class="img-fluid rounded-top" 
        loading="lazy"
        style="object-fit: cover; aspect-ratio: 1/1;">
</picture>

        @else
            <img 
                src="{{ asset('images/default.jpg') }}" 
                alt="Default image" 
                class="img-fluid rounded-top" 
                loading="lazy"
                style="object-fit: cover; aspect-ratio: 1/1;">
        @endif
  <div class="card-body">
    <h5 class="card-title"> {{$product->name}}</h5>
    <p class="card-text">{{$product->formatted_price}}</p>
    @role("admin")
    <a href="{{route('products.delete',$product->id)}}" class="btn btn-primary">Delete</a>
    <a href="{{route('products.edit',$product->id)}}" class="btn btn-primary">Edit</a>
    @endrole
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