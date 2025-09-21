<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password'] ?? 'defaultpassword');
        User::create($data);

        return redirect()->route('users.index')->with('success', 'Customer created.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UserStoreRequest $request, User $user)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password'] ?? 'defaultpassword');
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'Customer updated.');
    }

    public function destroy(User $user)
    {
        if ($user->sales()->exists()) {
            return redirect()->route('users.index')
                ->with('error', 'Cannot delete this customer. Sales exist.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Customer deleted.');
    }
}
