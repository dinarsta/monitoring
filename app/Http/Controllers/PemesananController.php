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

    public function index(Request $request)
{
    $query = Pemesanan::query();

    // Pencarian berdasarkan nama
    if ($request->has('search') && $request->search != '') {
        $query->where('atas_nama', 'like', '%' . $request->search . '%');
    }

    // Filter berdasarkan bulan
    if ($request->has('month') && $request->month != '') {
        $query->whereMonth('tgl_pemesanan', $request->month);
    }

    // Filter berdasarkan tahun
    if ($request->has('year') && $request->year != '') {
        $query->whereYear('tgl_pemesanan', $request->year);
    }

    // Mendapatkan semua data pemesanan
    $pemesanans = $query->get();

    // Mengirimkan data ke view
    return view('index', compact('pemesanans'));
}

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'atas_nama' => 'required|string|max:255',
            'nama_design' => 'required|string|max:255',
            'QTY' => 'required|integer',
            'jenis_barang' => 'required|string',
            'tgl_pemesanan' => 'required|date',
            'tgl_deadline' => 'required|date',
            'jenis_barang_lain' => 'nullable|string|max:255', // Optional for 'lainnya'
        ]);

        // Create a new Pemesanan instance and save it
        $pemesanan = new Pemesanan();
        $pemesanan->atas_nama = $request->atas_nama;
        $pemesanan->nama_design = $request->nama_design;
        $pemesanan->QTY = $request->QTY;
        $pemesanan->tgl_pemesanan = $request->tgl_pemesanan;
        $pemesanan->tgl_deadline = $request->tgl_deadline;

        // Save the `jenis_barang` or the custom `jenis_barang_lain`
        $pemesanan->jenis_barang = $request->jenis_barang === 'lainnya' ? $request->jenis_barang_lain : $request->jenis_barang;

        $pemesanan->save();

        return redirect()->route('index')->with('success', 'Order created successfully!');
    }

    public function edit($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        return view('edit', compact('pemesanan'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'atas_nama' => 'required|string|max:255',
            'nama_design' => 'required|string|max:255',
            'jenis_barang' => 'required|string',
            'QTY' => 'required|integer',
            'tgl_pemesanan' => 'required|date',
            'tgl_deadline' => 'required|date',
        ]);

        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->atas_nama = $request->input('atas_nama');
        $pemesanan->nama_design = $request->input('nama_design');
        $pemesanan->jenis_barang = $request->input('jenis_barang');
        $pemesanan->QTY = $request->input('QTY');
        $pemesanan->tgl_pemesanan = $request->input('tgl_pemesanan');
        $pemesanan->tgl_deadline = $request->input('tgl_deadline');

        // Save the updated model
        $pemesanan->save();

        return redirect()->route('index')->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        // Pindahkan data ke tabel `deleted_orders_history`
        \App\Models\DeletedOrderHistory::create([
            'pemesanan_id' => $pemesanan->id,
            'atas_nama' => $pemesanan->atas_nama,
            'nama_design' => $pemesanan->nama_design,
            'QTY' => $pemesanan->QTY,
            'tgl_pemesanan' => $pemesanan->tgl_pemesanan,
            'tgl_deadline' => $pemesanan->tgl_deadline,
            'jenis_barang' => $pemesanan->jenis_barang,
            'status' => $pemesanan->status,
            'deleted_at' => now(),
        ]);

        // Hapus data dari tabel `pemesanans`
        $pemesanan->delete();

        return redirect()->route('index')->with('success', 'Order deleted successfully and added to history.');
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
        // Check the number of existing users
        $userCount = User::count();

        // If there are already 2 users, deny registration
        if ($userCount >= 2) {
            return redirect()->back()->withErrors(['message' => 'Registration failed: Only two user accounts are allowed.']);
        }

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user
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
            return redirect()->route('index');
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

    //
    public function updateStatus(Request $request, $id)
{
    // Find the order by its ID
    $pemesanan = Pemesanan::findOrFail($id);

    // Validate that the status is one of the allowed values
    $validatedData = $request->validate([
        'status' => 'required|in:on progress,done,selesai'
    ]);

    // Update the status with the new value
    $pemesanan->status = $request->input('status');
    $pemesanan->save();

    // Redirect back to the index page with a success message
    return redirect()->route('index')->with('success', 'Order status updated successfully.');
}


public function history(Request $request)
{
    $query = \App\Models\DeletedOrderHistory::query();

    // Pencarian berdasarkan nama
    if ($request->has('search') && $request->search != '') {
        $query->where('atas_nama', 'like', '%' . $request->search . '%');
    }

    // Filter berdasarkan bulan
    if ($request->has('month') && $request->month != '') {
        $query->whereMonth('tgl_pemesanan', $request->month);
    }

    // Filter berdasarkan tahun
    if ($request->has('year') && $request->year != '') {
        $query->whereYear('tgl_pemesanan', $request->year);
    }

    // Mendapatkan semua data pemesanan yang dihapus
    $deletedOrders = $query->get();

    // Mengirimkan data ke view
    return view('history', compact('deletedOrders'));
}

// live server
public function getLatestOrders()
{
    $pemesanans = Pemesanan::all(); // You can customize this query as needed
    return response()->json($pemesanans);
}

}
