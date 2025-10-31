@extends('admin.layout')

@section('content')
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">Add Permission</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('permission.store') }}" method="POST">
         @csrf
             @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
            <div class="mb-3">
                <label for="permission_name" class="form-label">Permission Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="permission_name" name="name" placeholder="Enter Permission Name"
                       value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-success">Add Permission</button>
        </form>
    </div>
</div>
@endsection
