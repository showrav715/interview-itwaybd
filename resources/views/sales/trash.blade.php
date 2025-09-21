@extends('layouts.app')
@section('content')
    <h1>Trashed Sales</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Deleted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($trashed as $t)
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->user->name }}</td>
                    <td>{{ $t->deleted_at }}</td>
                    <td>
                        <form method="POST" action="{{ route('sales.restore', $t->id) }}">
                            @csrf
                            <button class="btn btn-success btn-sm">Restore</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr class="text-center">
                    <td colspan="4">No trashed sales found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $trashed->links() }}
@endsection
