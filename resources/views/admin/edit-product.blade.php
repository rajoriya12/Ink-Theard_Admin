@extends('admin.layouts.admin')

@section('content')

<h2 class="mb-4">Edit Product</h2>

9<div class="card card-custom p-4">

    <form method="POST" action="/products/update/{{ $product->id }}">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Product Title</label>
            <input
                type="text"
                name="title"
                class="form-control"
                value="{{ $product->title }}"
                required>
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea
                rows="5"
                name="description"
                class="form-control"
                required>{{ $product->description }}</textarea>
        </div>

        <div class="mb-3">
            <label>Price</label>
            <input
                type="number"
                name="price"
                class="form-control"
                value="{{ $product->price }}"
                required>
        </div>

        <div class="mb-3">
            <label>Category</label>

            <select
                name="category"
                class="form-control">

                <option value="Streetwear"
                    {{ $product->category == 'Streetwear' ? 'selected' : '' }}>
                    Streetwear
                </option>

                <option value="Oversized"
                    {{ $product->category == 'Oversized' ? 'selected' : '' }}>
                    Oversized
                </option>

                <option value="Vintage"
                    {{ $product->category == 'Vintage' ? 'selected' : '' }}>
                    Vintage
                </option>

                <option value="Luxury"
                    {{ $product->category == 'Luxury' ? 'selected' : '' }}>
                    Luxury
                </option>

            </select>

        </div>

        <div class="mb-3">

            <label>Image URL</label>

            <input
                type="file"
                name="image"
                class="form-control"
                value="{{ $product->image }}">

        </div>

        <div class="mb-3">

            <label>Discount (%)</label>

            <input
                type="number"
                name="discount"
                class="form-control"
                value="{{ $product->discount ?? 0 }}">

        </div>

        <div class="mb-3">

            <input
                type="checkbox"
                name="featured"
                {{ $product->featured ? 'checked' : '' }}>

            Featured Product

        </div>

        <button
            type="submit"
            class="btn btn-warning">
            Update Product
        </button>

    </form>

</div>

@endsection