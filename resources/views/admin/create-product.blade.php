@extends('admin.layouts.admin')

@section('content')

<h2 class="mb-4">Add Product</h2>

<div class="card card-custom p-4">

    <form method="POST" action="/products/store" enctype="multipart/form-data">

        @csrf

        <div class="mb-3">
            <label>Product Title</label>
            <input
                type="text"
                name="title"
                class="form-control"
                required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea
                rows="5"
                name="description"
                class="form-control"
                required></textarea>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input
                type="number"
                name="price"
                class="form-control"
                required>
        </div>

        <div class="mb-3">
            <label>Category</label>

            <select
                name="category"
                class="form-control">
                <option value="Streetwear">Streetwear</option>
                <option value="Oversized">Oversized</option>
                <option value="Vintage">Vintage</option>
                <option value="Luxury">Luxury</option>
            </select>

        </div>

        <div class="mb-3">

            <label>Image URL</label>

            <input
                type="file"
                name="image"
                class="form-control"
                required>
        </div>

        <div class="mb-3">

            <label>Discount (%)</label>

            <input
                type="number"
                name="discount"
                class="form-control"
                value="0">

        </div>

        <div class="mb-3">

            <input
                type="checkbox"
                name="featured">

            Featured Product

        </div>


        

        <button
            type="submit"
            class="btn btn-warning">
            Save Product
        </button>

       

    </form>

</div>

@endsection