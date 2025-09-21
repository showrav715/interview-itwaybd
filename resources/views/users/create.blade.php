@extends('layouts.app')
@section('content')
    <div class="container">
        <h1>Create Customer</h1>
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter User Name" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter User Email" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter User Password" required>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror   
            </div>

            <div class="mb-3">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm User Password" required>
            @error('password_confirmation')
                <div class="text-danger">{{ $message }}</div>
            @enderror   
            </div>

            <button class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
