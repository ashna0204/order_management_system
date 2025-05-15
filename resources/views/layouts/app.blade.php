<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap and FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f9f9fb;
    }

    .sidebar {
        width: 260px;
        background-color: #ffffff;
        border-right: 1px solid #e0e0e0;
        height: 100vh;
        position: fixed;
        padding: 2rem 1rem;
    }

    .sidebar h5 {
        color: #5a4fcf;
        font-weight: 600;
    }

    .nav-link {
        color: #333;
        font-size: 15px;
        padding: 12px 18px;
        border-radius: 12px;
        transition: all 0.2s ease-in-out;
    }

    .nav-link i {
        margin-right: 10px;
    }

    .nav-link:hover {
        background-color: #f0f0ff;
        color: #5a4fcf;
    }

    .nav-link.active {
        background-color: #5a4fcf;
        color: #fff !important;
    }

    .main-content {
        margin-left: 260px;
        padding: 2rem;
    }

    .card-style {
        border-radius: 18px;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05);
        background: #ffffff;
        padding: 2rem;
    }

    .footer {
        border-top: 1px solid #eee;
        padding: 1rem;
        background: #fafafa;
        text-align: center;
        font-size: 14px;
        color: #999;
    }
</style>

</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
   <div class="sidebar">

        <h5 class="mb-4">Order Management</h5>
        <ul class="nav flex-column">
            @role('admin')
            <li class="nav-item">
                <a class="nav-link text-black mb-3" href="{{route('admin.dashboard')}}"> <i class="fas fa-chart-pie"></i> Dashboard</a>
            </li>
            @else
            @role('user')
            <li class="nav-item">
                <a class="nav-link text-black mb-3" href="{{route('user.dashboard')}}"> <i class="fas fa-chart-pie"></i> Dashboard</a>
            </li>
            @endrole
            @endrole
            <li class="nav-item">
                <a class="nav-link text-black mb-3" href="{{route('orders.index')}}"> <i class="fas fa-shopping-cart"></i> Orders</a>
            </li>
              @role('user')
            <li class="nav-item">
                <a class="nav-link text-black mb-3" href="{{route('orders.create')}}"><i class="fas fa-plus-circle"></i> Add Orders</a>
            </li>
            @endrole
          
          

              
            <li class="nav-item">
                <a class="nav-link text-black mb-3" href="{{route('products.show')}}"><i class="fas fa-box"></i> Products</a>
            </li>
            @role('user')
            <li class="nav-item">
                <a class="nav-link text-black mb-3" href="{{route('orders.trash')}}"><i class="fas fa-trash-alt"></i> Trash</a>
            </li>
            @endrole
            <li class="nav-item">
                <a class="nav-link text-black mb-3" href="#"><i class="fas fa-phone-alt"></i> Contact</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">

          <div class="p-2">
              @include('layouts.header')
          </div>

        <!-- This div will expand to push footer down -->
        <div class="flex-grow-1 px-4">
            @yield('content')
        </div>

        <div class="p-3 bg-light border-top text-center">
            @include('layouts.footer')
        </div>
    </div>
</div>

@yield('scripts')

</body>
</html>
