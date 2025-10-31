@extends('admin.layout')

@section('content')
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">Edit Permission</h3>
    </div>
    <div class="card-body">


         flasher_render()

        <form action="{{ route('permission.update', $per->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="role_name" class="form-label">Permission Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="role_name" name="name" placeholder="Enter Permission Name"
                       value="{{ $per->name }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Update And Save</button>
        </form>
    </div>
</div>
@endsection
