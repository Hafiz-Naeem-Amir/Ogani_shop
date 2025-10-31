@extends('admin.layout')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Permission List</h3>
        <a href="{{ route('permission.create') }}" class="btn btn-success float-right">Add Permission</a>
    </div>

    <div class="card-body">


         flasher_render()

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Permission Name</th>
                    <th>Actions</th>
                    <th>Permissions Has Role</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permissions as $per)
                    <tr>
                        <td>{{ $per->id }}</td>
                        <td>{{ $per->name }}</td>
                        <td>
                            <a href="{{ route('permission.edit', $per->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('permission.destroy',$per->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this role?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
