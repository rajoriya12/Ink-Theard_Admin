@extends('admin.layouts.admin')

@section('content')

<h2 class="mb-4">Edit Product</h2>

{{-- एरर मैसेज दिखाने का सबसे सही और सुरक्षित तरीका --}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card card-custom p-4" style="background: #111; border: 1px solid #222; color: #fff;">

    {{-- MongoDB _id के लिए सुरक्षित राउटिंग --}}
    <form method="POST" action="/products/update/{{ $product->_id ?? $product->id }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- 1. Product Title --}}
        <div class="mb-3">
            <label class="form-label">Product Title</label>
            <input
                type="text"
                name="title"
                class="form-control"
                value="{{ old('title', is_array($product->title) ? implode(' ', $product->title) : $product->title) }}"
                style="background: #1a1a1a; color: #fff; border: 1px solid #333;"
                required>
        </div>

        {{-- 2. Description --}}
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea
                rows="5"
                name="description"
                class="form-control"
                style="background: #1a1a1a; color: #fff; border: 1px solid #333;"
                required>{{ old('description', is_array($product->description) ? implode(' ', $product->description) : $product->description) }}</textarea>
        </div>

        {{-- 3. Price --}}
        <div class="mb-3">
            <label class="form-label">Price (₹)</label>
            <input
                type="number"
                name="price"
                class="form-control"
                value="{{ old('price', is_array($product->price) ? ($product->price[0] ?? 0) : $product->price) }}"
                style="background: #1a1a1a; color: #fff; border: 1px solid #333;"
                required>
        </div>

        {{-- 4. Category --}}
        @php
            // कैटेगरी को सुरक्षित स्ट्रिंग में निकालें
            $currentCategory = is_array($product->category) ? ($product->category[0] ?? '') : $product->category;
            $selectedCategory = old('category', $currentCategory);
        @endphp
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select
                name="category"
                class="form-control"
                style="background: #1a1a1a; color: #fff; border: 1px solid #333;">
                <option value="Streetwear" {{ $selectedCategory == 'Streetwear' ? 'selected' : '' }}>Streetwear</option>
                <option value="Oversized" {{ $selectedCategory == 'Oversized' ? 'selected' : '' }}>Oversized</option>
                <option value="Vintage" {{ $selectedCategory == 'Vintage' ? 'selected' : '' }}>Vintage</option>
                <option value="Luxury" {{ $selectedCategory == 'Luxury' ? 'selected' : '' }}>Luxury</option>
            </select>
        </div>

        {{-- 5. Image Handling --}}
        @php
            $currentImage = is_array($product->image) ? ($product->image[0] ?? '') : $product->image;
        @endphp
        <div class="mb-3">
            <label class="form-label">Current Image</label>
            @if(!empty($currentImage))
                <div class="mb-2">
                    <img
                        src="{{ $currentImage }}"
                        alt="Product Image"
                        width="120"
                        style="border-radius:8px; border: 1px solid #333; object-fit: cover; height: 120px;">
                </div>
            @endif
            <input
                type="file"
                name="image"
                class="form-control"
                style="background: #1a1a1a; color: #fff; border: 1px solid #333;">
        </div>

        {{-- 6. Discount --}}
        <div class="mb-3">
            <label class="form-label">Discount (%)</label>
            <input
                type="number"
                name="discount"
                class="form-control"
                value="{{ old('discount', is_array($product->discount) ? ($product->discount[0] ?? 0) : $product->discount) }}"
                style="background: #1a1a1a; color: #fff; border: 1px solid #333;">
        </div>

        {{-- 7. Featured Checked Status --}}
        @php
            // अगर एरे में 'true' आया हो या डायरेक्ट स्ट्रिंग 'true' हो
            $isFeatured = false;
            $featuredVal = is_array($product->featured) ? ($product->featured[0] ?? 'false') : $product->featured;
            if ($featuredVal === true || $featuredVal === 'true' || $featuredVal == 1 || $featuredVal == '1') {
                $isFeatured = true;
            }
        @endphp
        <div class="mb-3 form-check">
            <input
                type="checkbox"
                name="featured"
                value="1"
                class="form-check-input"
                id="featured"
                {{ old('featured', $isFeatured) ? 'checked' : '' }}>
            <label class="form-check-label" for="featured">
                Featured Product
            </label>
        </div>

        <button
            type="submit"
            class="btn btn-warning w-100"
            style="font-weight: bold;">
            Update Product
        </button>

    </form>
</div>

@endsection