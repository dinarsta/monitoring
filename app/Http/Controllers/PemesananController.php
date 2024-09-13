<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index()
    {
        $pemesanans = Pemesanan::all();
        return view('index', compact('pemesanans'));
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

}
