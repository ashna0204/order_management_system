<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Order List</h2>

<table>
    <thead>
        <tr>
            <th>Order NO</th>
            <th>Customer Name</th>
            <th>Address</th>
            <th>date</th>
            <th>Products</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{$order->id }}</td>
            <td>{{$order->customer_name }}</td>
            <td>{{$order-> address}}</td>
            <td>{{$order-> date}}</td>
            <td>
                <ul>
                    @foreach($order->items as $item)
                    <li>{{$item->product->name}} (x {{ $item->quantity }} -  {{ $item->formatted_total }})</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
