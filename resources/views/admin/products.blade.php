@extends('admin.layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Products</h2>

    <a href="/products/create" class="btn btn-warning">
        + Add Product
    </a>

</div>

@if(session('success'))

<div class="alert alert-success">
    {{ session('success') }}
</div>

@endif

<div class="card card-custom p-3 mb-4">

    <h5 class="mb-3">Import Products CSV</h5>

    <form action="/products/import"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        <input type="file"
            name="csv"
            accept=".csv"
            class="form-control">

        <button type="submit"
            class="btn btn-success mt-3">
            Import CSV
        </button>

    </form>

</div>

<div class="card card-custom p-3">

    <form action="/products/bulk-delete"
        method="POST">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="btn btn-danger mb-3"
            onclick="return confirm('Delete selected products?')">

            Delete Selected

        </button>

        <table class="table table-dark table-hover align-middle">

            <thead>

                <tr>

                    <th>
                        <input type="checkbox" id="selectAll">
                    </th>

                    <th>Image</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Discount</th>
                    <th>Featured</th>
                    <th>Action</th>

                </tr>

            </thead>

            <tbody>
                @forelse($products as $product)

                <tr>

                  
                    <td>
                        <input
                            type="checkbox"
                            name="selected_products[]"
                            value="{{ $product->_id }}"
                            class="product-checkbox">
                    </td>

                    <td>
                        @php
                        $image = is_array($product->image ?? null)
                        ? ($product->image[0] ?? '')
                        : ($product->image ?? '');
                        @endphp

                        @if($image)
                        <img
                            src="{{ $image }}"
                            width="80"
                            height="80"
                            style="object-fit:cover;border-radius:8px;">
                        @else
                        <span>No Image</span>
                        @endif
                    </td>

                    <td>
                        {{ is_array($product->title ?? null) ? implode(', ', $product->title) : $product->title }}
                    </td>

                    <td>
                        ₹ {{ is_array($product->price ?? null) ? implode(', ', $product->price) : $product->price }}
                    </td>

                    <td>
                        {{ is_array($product->category ?? null) ? implode(', ', $product->category) : $product->category }}
                    </td>

                    <td>
                        {{ is_array($product->discount ?? null) ? implode(', ', $product->discount) : $product->discount }}%
                    </td>

                    <td>
                        @if($product->featured)
                        <span class="badge bg-success">Yes</span>
                        @else
                        <span class="badge bg-danger">No</span>
                        @endif
                    </td>

                    <td>
                        <div class="btn-group gap-3" role="group">

                            <form
                                action="/products/delete/{{ $product->_id }}"
                                method="POST">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete this product?')">

                                    Delete

                                </button>

                            </form>

                            <a href="/products/edit/{{ $product->_id }}"
                                class="btn btn-primary btn-sm px-3">
                                Edit
                            </a>

                        </div>
                    </td>
               

                </tr>

                @empty

                <tr>
                    <td colspan="8" class="text-center">
                        No Products Found
                    </td>
                </tr>

                @endforelse


            </tbody>

        </table>

    </form>

</div>


@endsection