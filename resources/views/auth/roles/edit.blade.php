@extends('admin.layout')

@section('content')
<div class="card mt-4">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">Add Role</h3>
    </div>

    <div class="card-body">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('role.update',$role->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="role_name" class="form-label">Role Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="role_name" name="name" placeholder="Enter Role Name"
                       value="{{  $role->name}}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-success">Update And Save</button>
        </form>
    </div>
</div>
@endsection
