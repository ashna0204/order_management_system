@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Add New Product</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
            <label>Product Name <i class="text-danger">*</i></label>
            <input type="text" name="name" class="form-control" >
            @error('name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label>Price <i class="text-danger">*</i></label>
            <input type="number" name="price" class="form-control" step="0.01" >
            @error('price')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label>Product Image <i class="text-danger">*</i></label>
            <input type="file" name="image" class="form-control">
            @error('image')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>
@endsection
