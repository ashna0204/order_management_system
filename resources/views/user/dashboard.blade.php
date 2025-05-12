@extends('layouts.app')
@section('content')
<div style="display: flex; background-color: #9B7EBD; width: 100%; border-radius: 10px; padding: 20px;">
  

  <div style="display: flex; flex-direction: column; justify-content: center; flex: 1;">
    <h1 style="font-size: 24px; color: white; text-transform: capitalize; margin-bottom: 10px;">
      Welcome, {{ ucfirst($user->name) }}
    </h1>
    <p style="font-size: 16px; color: #EDEDED;">
      We're glad to have you here!
    </p>
  </div>

  <div style="flex: 1; display: flex; justify-content: flex-end; align-items: center;">
    <img src="{{ asset('images/welcome.png') }}" alt="Welcome" style="max-height: 80px; border-radius: 8px;">
  </div>

</div>
<div style="display: flex; justify-content: space-between; margin-top: 20px; gap: 20px;">

    <!-- Total Orders Card -->
    <div style="flex: 1; background-color: #6C63FF; color: white; border-radius: 10px; padding: 20px; text-align: center;">
        <i class="fas fa-shopping-cart" style="font-size: 24px; margin-bottom: 10px;"></i>
        <h3 style="font-size: 18px; margin: 10px 0;">Total Orders</h3>
        <p style="font-size: 16px;"></p>
    </div>

    <!-- Coupons Card -->
    <div style="flex: 1; background-color: #FF6584; color: white; border-radius: 10px; padding: 20px; text-align: center;">
        <i class="fas fa-ticket-alt" style="font-size: 24px; margin-bottom: 10px;"></i>
        <h3 style="font-size: 18px; margin: 10px 0;">Coupons</h3>
        <p style="font-size: 16px;">  </p>
    </div>

    <!-- Wishlist Card -->
    <div style="flex: 1; background-color: #4CAF50; color: white; border-radius: 10px; padding: 20px; text-align: center;">
        <i class="fas fa-heart" style="font-size: 24px; margin-bottom: 10px;"></i>
        <h3 style="font-size: 18px; margin: 10px 0;">Wishlist</h3>
        <p style="font-size: 16px;">  </p>
    </div>

    <!-- Help Center Card -->
    <div style="flex: 1; background-color: #FFB74D; color: white; border-radius: 10px; padding: 20px; text-align: center;">
        <i class="fas fa-life-ring" style="font-size: 24px; margin-bottom: 10px;"></i>
        <h3 style="font-size: 18px; margin: 10px 0;">Help Center</h3>
        <p style="font-size: 16px;">  </p>
    </div>

</div>


<div style="display: flex; margin-top: 20px; gap: 20px;">

    <!-- Profile Update Form -->
    <div style="flex: 2; background-color: #F5F5F5; border-radius: 10px; padding: 20px;">
        <h3 style="font-size: 20px; margin-bottom: 15px; color: #333;">Update Profile</h3>
        <form action="" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label for="name" style="display: block; font-size: 14px; color: #555; margin-bottom: 5px;">Name</label>
                <input type="text" id="name" name="name" value="{{ $user->name }}" style="width: 100%; padding: 10px; border: 1px solid #CCC; border-radius: 5px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label for="email" style="display: block; font-size: 14px; color: #555; margin-bottom: 5px;">Email</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}" style="width: 100%; padding: 10px; border: 1px solid #CCC; border-radius: 5px;">
            </div>
            <div style="margin-bottom: 15px;">
                <label for="password" style="display: block; font-size: 14px; color: #555; margin-bottom: 5px;">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter new password" style="width: 100%; padding: 10px; border: 1px solid #CCC; border-radius: 5px;">
            </div>
            <button type="submit" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Update</button>
        </form>
    </div>

    <!-- Long Card -->
    <div style="flex: 3; background-color: #E3F2FD; border-radius: 10px; padding: 20px;">
        <h3 style="font-size: 20px; margin-bottom: 15px; color: #333;">Your Activity</h3>
        <p style="font-size: 16px; color: #555;">Here you can see a summary of your recent activities and updates.</p>
        <ul style="list-style: none; padding: 0; margin-top: 10px;">
            <li style="margin-bottom: 10px; font-size: 14px; color: #777;">- You placed an order on</li>
            <li style="margin-bottom: 10px; font-size: 14px; color: #777;">- You updated your profile on</li>
            <li style="margin-bottom: 10px; font-size: 14px; color: #777;">- You added 3 items to your wishlist</li>
        </ul>
    </div>

</div>
@endsection
