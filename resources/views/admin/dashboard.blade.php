@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Number of Customers -->
        <div class="col-md-4">
            <div class="card text-white mb-3" style="background-color: #7F55B1;">
                <div class="card-body">
                    <h5 class="card-title">No. of Customers</h5>
                    <p class="card-text">{{$customerCount}}</p>
                </div>
            </div>
        </div>
        
        <!-- Number of Orders -->
        <div class="col-md-4">
            <div class="card text-white mb-3" style="background-color: #9B7EBD;">
                <div class="card-body">
                    <h5 class="card-title">No. of Orders</h5>
                    <p class="card-text">{{$orderCount}}</p>
                </div>
            </div>
        </div>
        
        <!-- Total Revenue -->
        <div class="col-md-4">
            <div class="card text-white mb-3" style="background-color: #F49BAB;">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="card-text">{{$fomattedRevenue}}</p>
                </div>
            </div>
        </div>
    </div>
        
   

{{-- Sales Chart --}}
<!-- <div class="container mx-auto mt-5 py-4">
    <div class="bg-white p-6 rounded-xl shadow">
        <h2 class="text-lg font-semibold mb-4">Sales Chart</h2>
        <canvas id="salesChart" height="100"></canvas>
    </div>
</div> -->


<div class="container my-4">
    <div class="row">
        {{-- Orders Chart --}}
        <div class="col-md-6 mb-4">
             <div class="bg-white p-4 rounded-xl shadow h-100" data-bs-toggle="modal" data-bs-target="#ordersChartModal" style="cursor:pointer;">
      
                <h2 class="text-lg font-semibold mb-4">Orders Per Day</h2>
                <canvas id="ordersChart" height="100"></canvas>
            </div>
        </div>

        {{-- Customers Chart --}}
        <div class="col-md-6 mb-4">
            <div class="bg-white p-4 rounded-xl shadow h-100" data-bs-toggle="modal" data-bs-target="#customersChartModal" style="cursor:pointer;">
       
                <h2 class="text-lg font-semibold mb-4">New Customers Per Day</h2>
                <canvas id="customersChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Orders Chart Modal -->
<div class="modal fade" id="ordersChartModal" tabindex="-1" aria-labelledby="ordersChartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ordersChartModalLabel">Orders Per Day (Enlarged)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <canvas id="ordersChartModalCanvas" height="150"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Customers Chart Modal -->
<div class="modal fade" id="customersChartModal" tabindex="-1" aria-labelledby="customersChartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="customersChartModalLabel">New Customers Per Day (Enlarged)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <canvas id="customersChartModalCanvas" height="150"></canvas>
      </div>
    </div>
  </div>
</div>



<div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Top Selling Products</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Units Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topSellingProducts as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->total_units_sold }}</td>
                                <td>{{ $product->formatted_revenue }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


{{-- Sales Chart --}}
<!-- <script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($salesChartData->pluck('date')),
            datasets: [{
                label: 'Total Sales',
                data: @json($salesChartData->pluck('total')),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value;
                        }
                    }
                }
            }
        }
    });
</script> -->

{{-- Orders Chart --}}
<script>
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    new Chart(ordersCtx, {
        type: 'bar',
        data: {
            labels: @json($ordersChartData->pluck('date')),
            datasets: [{
                label: 'Orders',
                data: @json($ordersChartData->pluck('total')),
                backgroundColor: @json($ordersChartData->pluck('color')), // Assuming you pass colors from the backend
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 1,
                barThickness: 10 // Makes the bars thinner
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>

{{-- Customers Chart --}}
<script>
    const customersCtx = document.getElementById('customersChart').getContext('2d');
    new Chart(customersCtx, {
        type: 'line',
        data: {
            labels: @json($customersChartData->pluck('date')),
            datasets: [{
                label: 'New Customers',
                data: @json($customersChartData->pluck('total')),
                backgroundColor: 'rgba(153, 102, 255, 0.3)',
                borderColor: 'rgb(153, 102, 255)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>


<!-- ordersChartModal -->
<script>
    // Clone data for modal canvas
    const ordersChartModalCtx = document.getElementById('ordersChartModalCanvas').getContext('2d');
    new Chart(ordersChartModalCtx, {
        type: 'bar',
        data: {
            labels: @json($ordersChartData->pluck('date')),
            datasets: [{
                label: 'Orders',
                data: @json($ordersChartData->pluck('total')),
                backgroundColor: @json($ordersChartData->pluck('color')),
                borderColor: 'rgb(54, 162, 235)',
                borderWidth: 3,
                barThickness: 50
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, precision: 0 }
            }
        }
    });

    const customersChartModalCtx = document.getElementById('customersChartModalCanvas').getContext('2d');
    new Chart(customersChartModalCtx, {
        type: 'line',
        data: {
            labels: @json($customersChartData->pluck('date')),
            datasets: [{
                label: 'New Customers',
                data: @json($customersChartData->pluck('total')),
                backgroundColor: 'rgba(153, 102, 255, 0.3)',
                borderColor: 'rgb(153, 102, 255)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, precision: 0 }
            }
        }
    });
</script>


@endsection