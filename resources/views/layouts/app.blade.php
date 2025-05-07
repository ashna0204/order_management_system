<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Management</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap and FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .nav-link {
            padding: 10px 15px;
            display: block;
        }
        .nav-link:hover {
            background-color: #555;
            text-decoration: none;
        }
        .nav-link.active {
            background-color: #333;
            color: #fff;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-dark text-white p-3" style="width: 250px; height: 100vh; position:fixed;">
        <h5 class="mb-4">Order Management</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white mb-3" href="{{route('orders.index')}}"> <i class="fas fa-tachometer-alt"></i> Home</a>
            </li>
              @role('user')
            <li class="nav-item">
                <a class="nav-link text-white mb-3" href="{{route('orders.create')}}"><i class="fas fa-plus-circle"></i> Add Orders</a>
            </li>
            @endrole
          
          

              
            <li class="nav-item">
                <a class="nav-link text-white mb-3" href="{{route('products.show')}}"><i class="fas fa-box"></i> Products</a>
            </li>
            @role('user')
            <li class="nav-item">
                <a class="nav-link text-white mb-3" href="{{route('orders.trash')}}"><i class="fas fa-trash-alt"></i> Trash</a>
            </li>
            @endrole
            <li class="nav-item">
                <a class="nav-link text-white mb-3" href="#"><i class="fas fa-phone-alt"></i> Contact</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="d-flex flex-column min-vh-100 container-fluid p-0" style="margin-left:250px;">
          <div class="p-4">
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
