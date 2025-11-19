<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //get all users with pagination
        $users = User::when($request->input('name'), function ($query, $name) {
        // Validasi input
        $searchTerm = '%' . $name . '%';
        
        $query->where(function($q) use ($searchTerm) {
            $q->where('name', 'like', $searchTerm)
              ->orWhere('email', 'like', $searchTerm);
                });
            })
            ->orderBy('name') // Penting untuk konsistensi pagination
            ->paginate(10);

        return view('pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validate the request...
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            // 'roles' => 'required',
        ]);

        // store the request...
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        // $user->syncRoles($request->roles);
        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('pages.users.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $roles = Role::pluck('name', 'name')->all();
        $user = User::findOrFail($id);
        // $userRole = $user->roles->pluck('name', 'name')->all();
        // return view('pages.users.edit', compact('user', 'roles', 'userRole'));
        return view('pages.users.edit', compact('user'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // validate the request...
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            // 'roles' => 'required',
        ]);

        // update the request...
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        // $user->syncRoles($request->roles);
        $user->save();

        //if password is not empty
        if ($request->password) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
