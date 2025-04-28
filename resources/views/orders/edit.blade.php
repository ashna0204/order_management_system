@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Order #{{ $order->id }}</h2>

    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Customer Name<i class="text-danger">*</i> </label>
            <input type="text" name="customer_name" class="form-control" value="{{ $order->customer_name }}" >
            @error('customer_name')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Address <i class="text-danger">*</i></label>
            <textarea name="address" class="form-control" >{{ $order->address }}</textarea>
            @error('address')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Date <i class="text-danger">*</i></label>
            <input type="date" name="date" class="form-control" value="{{ $order->date }}" >
            @error('date')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <h4>Order Items</h4>
        <table class="table" id="orderTable">
            <thead>
                <tr>
                    <th>Sl. No</th>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <select name="products[]" class="form-control product-select">
                            @foreach($products as $product)
                               <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-image="{{ $product->image_url }}">
    {{ $product->name }}
</option>

                            @endforeach
                            @error('products')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </select>
                    </td>
                    <td class="image-cell">
    <img src="{{ $item->product->image_url }}" alt="Product Image" width="80">
</td>

                    <td><input type="number" name="quantities[]" class="form-control quantity" value="{{ $item->quantity }}"></td>
                   <td class="price">{{ $item->formatted_price }}</td>
<td class="total">{{ $item->formatted_total }}</td>

                    @error('quantities')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    @error('price')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <td><button type="button" class="btn btn-danger delete-row">Delete</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="button" id="addItem" class="btn btn-secondary">Add Item</button>
        <h5>Total Price: <span id="totalPrice">0</span></h5>
        <button type="submit" class="btn btn-primary">Update Order</button>
    </form>
</div>

<script>
let counter = {{ count($order->items) + 1 }};
const products = @json($products);

// Update Prices and Check Quantities
function updatePrices() {
    let total = 0;
    document.querySelectorAll('#orderTable tbody tr').forEach(row => {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity');
        const quantity = parseFloat(quantityInput.value);
        const price = parseFloat(select.options[select.selectedIndex].dataset.price);
        const rowTotal = price * quantity;
        
        // Update the price and total for this row
        row.querySelector('.price').innerText = price.toFixed(2);
        row.querySelector('.total').innerText = rowTotal.toFixed(2);
        
        total += rowTotal;

        // Ensure quantity is positive
        if (quantity <= 0 || isNaN(quantity)) {
            quantityInput.value = 1; // Set to 1 if invalid or negative
        }
    });

    document.getElementById('totalPrice').innerText = total.toFixed(2);
}

document.getElementById('addItem').addEventListener('click', function() {
    const table = document.querySelector('#orderTable tbody');
    const row = document.createElement('tr');

let productOptions = products.map(p =>
    `<option 
        value="${p.id}" 
        data-price="${p.price}" 
        data-image="${p.image_url}" 
        data-formatted="${p.formatted_price}">
        ${p.name} 
    </option>`
).join('');


row.innerHTML = `
    <td>${counter}</td>
    <td>
        <select name="products[]" class="form-control product-select">
            <option value="">-- Select Product --</option>
            ${productOptions}
        </select>
        </td>
        <td class="image-cell">
        <img src="" class="product-preview mt-2" style="display:none; max-width: 60px;">
  
    </td>
    <td><input type="number" name="quantities[]" class="form-control quantity" min="1" value="1"></td>
    <td class="price">0</td>
    <td class="total">0</td>
    <td><button type="button" class="btn btn-danger delete-row">Delete</button></td>
    @error('products')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    @error('quantities')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
`;


    table.appendChild(row);
    counter++;
    updatePrices();
});
// Ensure prices are updated on quantity or product change
document.addEventListener('change', function (e) {
  if (e.target.classList.contains('product-select')) {
    const select = e.target;
    const row = select.closest('tr');
    const imageUrl = select.options[select.selectedIndex].dataset.image;

    const image = row.querySelector('td.image-cell img');
    if (imageUrl && image) {
        image.src = imageUrl;
        image.style.display = 'block'; // Make sure image becomes visible
    } else if (image) {
        image.src = '';
        image.style.display = 'none';
    }
}

    updatePrices();
});

// Handle delete row action
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('delete-row')) {
        e.target.closest('tr').remove();
        updatePrices();
    }
});

// Initialize prices
updatePrices();

</script>
@endsection
