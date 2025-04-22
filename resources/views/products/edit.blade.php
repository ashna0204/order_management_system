@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit  Product</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('products.update',$product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="{{$product->name}}" required>
        </div>

        <div class="form-group mb-3">
            <label>Price</label>
            <input type="number" name="price" class="form-control" step="0.01" value="{{$product->price}}"required>
        </div>

        <div class="form-group mb-3">
            <label>Product Image</label>
            <input type="file" name="image"class="form-control">
            <div class="mt-2">
            @if($product->image)
            <img src="{{ asset('storage/' .$product->image)}}" width="150">
            @endif
            </div>
        </div>

        <button type="submit" class="btn btn-primary">update Product</button>
    </form>
</div>
@endsection
