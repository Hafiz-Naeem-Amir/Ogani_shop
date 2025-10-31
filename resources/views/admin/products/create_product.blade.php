@extends('admin.layout.app')
@section('content')

<div class="d-flex justify-content-end mb-3 pe-3" >
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
        +Product
    </button>
</div>

    <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="product_id" value="{{ old('product_id') }}">

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Keyword</label>
                <input type="text" class="form-control" name="keyword" value="{{ old('keyword') }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Image</label>
                <input type="file" class="form-control" name="images[]" multiple accept="image/*">
            </div>

            <div class="col-md-3">
                <label class="form-label">Category</label>
                <select class="form-control" name="category_id" required>
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Variant</label>
                <select class="form-control" name="variant_id" required>
                    <option value="">-- Select Variant --</option>
                    @foreach($variants as $variant)
                        <option value="{{ $variant->id }}" {{ old('variant_id') == $variant->id ? 'selected' : '' }}>{{ $variant->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-12">
                <label class="form-label">Meta Description</label>
                <textarea class="form-control" name="meta_description" rows="3" required>{{ old('meta_description') }}</textarea>
            </div>

            <div class="col-md-12">
                <label class="form-label">Content Description</label>
                <textarea class="form-control" name="content_description" rows="4" required>{{ old('content_description') }}</textarea>
            </div>

            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary">Submit Product</button>
            </div>
        </div>
    </form>
</div>

@endsection
