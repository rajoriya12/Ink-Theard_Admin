@extends('admin.layouts.admin')

@section('content')

<h2 class="mb-4">Orders</h2>

<div class="card p-4">

    <table class="table table-dark">

        <thead>
            <tr>
                <th>Customer</th>
                <th>Phone</th>
                <th>Product</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>

        <tbody>

            @foreach($orders as $order)

            <tr>
                <td>
                    <strong>{{ $order->customerName }}</strong>
                    <br>
                    <small>{{ $order->customerEmail }}</small>
                </td>

                <td>{{ $order->phone }}</td>

                <td>{{ $order->productTitle }}</td>

                <td>₹{{ $order->productPrice }}</td>

                <td>
                    <form action="/orders/status/{{ $order->_id }}" method="POST">

                        @csrf

                        <select
                            name="status"
                            onchange="this.form.submit()"
                            class="form-select form-select-sm">
                            <option value="Pending"
                                {{ $order->status == 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="Processing"
                                {{ $order->status == 'Processing' ? 'selected' : '' }}>
                                Processing
                            </option>

                            <option value="Shipped"
                                {{ $order->status == 'Shipped' ? 'selected' : '' }}>
                                Shipped
                            </option>

                            <option value="Delivered"
                                {{ $order->status == 'Delivered' ? 'selected' : '' }}>
                                Delivered
                            </option>

                            <option value="Cancelled"
                                {{ $order->status == 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>
                        </select>

                    </form>
                </td>

                <td>
                    {{ $order->created_at ?? $order->createdAt }}
                </td>
            </tr>

            @endforeach

        </tbody>

    </table>

</div>

@endsection