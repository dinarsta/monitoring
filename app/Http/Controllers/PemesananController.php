<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PemesananController extends Controller
{
    public function index()
{
    $pemesanans = Pemesanan::all(); // Fetch all orders
    return view('index', compact('pemesanans')); // Return view with orders
}


    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'atas_nama' => 'required|string|max:255',
            'nama_design' => 'required|string|max:255',
            'QTY' => 'required|integer',
            'tgl_pemesanan' => 'required|date',
            'tgn_deathline' => 'required|date',
        ]);

        $pemesanan = new Pemesanan($request->all());
        // Do not set status here
        $pemesanan->save();

        return redirect()->route('index')->with('success', 'Order created successfully.');
    }



    public function edit($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        dd($pemesanan); // Debugging line
        return view('edit', compact('pemesanan'));
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'atas_nama' => 'required|string|max:255',
            'nama_design' => 'required|string|max:255',
            'QTY' => 'required|integer',
            'tgl_pemesanan' => 'required|date',
            'tgn_deathline' => 'required|date',
        ]);

        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->update($request->all());
        // Do not set status here
        $pemesanan->save();

        return redirect()->route('index')->with('success', 'Order updated successfully.');
    }



public function destroy($id)
{
    $pemesanan = Pemesanan::findOrFail($id);
    $pemesanan->delete();

    return redirect()->route('index')->with('success', 'Order deleted successfully.');
}
public function markAsCompleted($id)
{
    $pemesanan = Pemesanan::findOrFail($id);
    $pemesanan->status = 'selesai';
    $pemesanan->save();

    return redirect()->route('index')->with('success', 'Order status updated to selesai.');
}


public function showRegistrationForm()
{
    return view('auth.register');
}

public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'user',  // Default role
    ]);

    return redirect()->route('login')->with('success', 'Registration successful. Please login.');
}

public function showLoginForm()
{
    return view('auth.login');
}

public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        return redirect()->route('dashboard');
    }

    throw ValidationException::withMessages([
        'email' => ['The provided credentials do not match our records.'],
    ]);
}

public function logout(Request $request)
{
    Auth::logout();
    return redirect('/login');
}

}
