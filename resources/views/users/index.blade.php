@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Customers</h1>
        <a href="{{ route('users.create') }}" class="btn btn-success mb-3">+ New Customer</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{$user->created_at->diffForHumans() ?? '-' }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>

                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Are you sure?')"
                                    class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
@endsection
