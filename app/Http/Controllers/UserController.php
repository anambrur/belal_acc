<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $roles = Role::all();
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);


        
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $address = $request->address;
        $password = Hash::make($request->password);
       
        User::create(['name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address, 'password' => $password]);
      
        return redirect()->route('user.index')->with('success', 'User added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required',
        ]);


        
        $name = $request->name;
        $email = $request->email;
        $phone = $request->phone;
        $address = $request->address;
        $password = Hash::make($request->password);
        
        $data = ['name' => $name, 'email' => $email, 'phone' => $phone, 'address' => $address];

        if ($request->has('password')) {
            $data['password'] = $password;
        }
       
        $user = User::find($id);
        $user->update($data);
        return redirect()->route('user.index')->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User deleted successfully');
    }
}