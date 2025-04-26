@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
       <div class="form-group">
    <label for="customer-select">Select Customer <i class="text-danger">*</i></label>
    <select name="customer_id" id="customer-select" class="form-control" >
        <option value="">-- Select Customer --</option>
        @foreach($customers as $customer)
            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
        @endforeach
    </select>
    @error('customer_id')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="customer-address">Customer Address <i class="text-danger">*</i></label>
    <textarea class="form-control" name="address" id="customer-address" rows="3"  readonly></textarea>
    @error('address')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
</div>

        <div class="form-group">
            <label for="date">Date <i class="text-danger">*</i></label>
            <input type="date" class="form-control" id="date"name="date" >
            @error('date')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>
        <h4>Order Items</h4>
        <table class="table table-dark table-striped" id="orderTable">
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
            </tbody>
        </table>
        <button type="button" id="addItem" class="btn btn-secondary">Add Item</button>
        <h5>Total Price: <span id="totalPrice">0</span></h5>
        <button type="submit" class="btn btn-success">Submit Order</button>
    </form>
</div>

<script>
let counter = 1;
const products = @json($products);

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
        <td>
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

document.addEventListener('change', updatePrices);
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('delete-row')) {
        e.target.closest('tr').remove();
        updatePrices();
    }
});
function getSelectedProductIds() {
    const selected = [];
    document.querySelectorAll('.product-select').forEach(select => {
        if (select.value) selected.push(select.value);
    });
    return selected;
}
function updatePrices() {
    let totalPrice = 0;
    document.querySelectorAll('#orderTable tbody tr').forEach(row => {
        const select = row.querySelector('.product-select');
        const quantity = row.querySelector('.quantity').value;

        const selectedOption = select.options[select.selectedIndex]; 
        const price = parseFloat(selectedOption.dataset.price || 0);
        const total = price * quantity;

        // Update image
        const image = selectedOption.dataset.image;
        const imgTag = row.querySelector('.product-preview');
        if (image) {
            imgTag.src = image;

            imgTag.style.display = 'block';
        } else {
            imgTag.style.display = 'none';
        }

        row.querySelector('.price').innerText = selectedOption.dataset.formatted || price.toFixed(2);
        row.querySelector('.total').innerText = total.toFixed(2);
        totalPrice += total;
    });

    document.getElementById('totalPrice').innerText = totalPrice.toFixed(2);

       // Disable selected products in other dropdowns
    const selectedIds = getSelectedProductIds();
    document.querySelectorAll('.product-select').forEach(select => {
        const currentVal = select.value;
        Array.from(select.options).forEach(option => {
            if (option.value && option.value !== currentVal && selectedIds.includes(option.value)) {
                option.disabled = true;
            } else {
                option.disabled = false;
            }
        });
    });
}

</script>


<script>
$(document).ready(function () {
    $('#customer-select').on('change', function () {
        const customerId = $(this).val();

        if (customerId) {
            $.ajax({
                url: `/customers/${customerId}`,
                method: 'GET',
                success: function (response) {
                    $('#customer-address').val(response.address);
                },
                error: function () {
                    $('#customer-address').val('');
                    alert('Could not fetch customer address');
                }
            });
        } else {
            $('#customer-address').val('');
        }
    });
});
</script>

@endsection