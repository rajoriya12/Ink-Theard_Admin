@extends('admin.layouts.admin')

@section('content')

<div class="row g-4">

    <div class="col-md-4">
        <div class="card card-custom p-4">
            <h5>Total Products</h5>
            <h2>{{ $totalProducts }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom p-4">
            <h5>Total Orders</h5>
            <h2>{{ $totalOrders }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom p-4">
            <h5>Total Customers</h5>
            <h2>{{ $totalCustomers }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom p-4">
            <h5>Pending Orders</h5>
            <h2>{{ $pendingOrders }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom p-4">
            <h5>Delivered Orders</h5>
            <h2>{{ $deliveredOrders }}</h2>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom p-4">
            <h5>Total Revenue</h5>
            <h2>₹{{ $totalRevenue }}</h2>
        </div>
    </div>

</div>

@endsection