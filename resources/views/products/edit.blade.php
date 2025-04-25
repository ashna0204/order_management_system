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
            <label>Product Name <i class="text-danger">*</i></label>
            <input type="text" name="name" class="form-control" value="{{$product->name}}" >
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label>Price <i class="text-danger">*</i></label>
            <input type="number" name="price" class="form-control" step="0.01" value="{{$product->price}}">
            @error('price')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label>Product Image <i class="text-danger">*</i></label>
            <input type="file" name="image"class="form-control">
            <div class="mt-2">
            @if($product->image)
            <img src="{{ asset('storage/' .$product->image)}}" width="150">
            @endif
            </div>
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">update Product</button>
    </form>
</div>
@endsection
