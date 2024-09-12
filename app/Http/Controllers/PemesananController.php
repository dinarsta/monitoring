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

    public function show($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        return view('show', compact('pemesanan'));
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
        $pemesanan->status = 'selesai';  // Automatically set the status to "selesai"
        $pemesanan->save();

        return redirect()->route('index')->with('success', 'Order created successfully and status set to selesai.');
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
        'QTY' => 'required|integer',
        'tgl_pemesanan' => 'required|date',
        'tgn_deathline' => 'required|date',
    ]);

    $pemesanan = Pemesanan::findOrFail($id);
    $pemesanan->update($request->all());
    $pemesanan->status = 'selesai';  // Automatically set the status to "selesai"
    $pemesanan->save();

    return redirect()->route('index')->with('success', 'Order updated successfully and status set to selesai.');
}


    public function destroy($id)
    {
        $pemesanan = Pemesanan::findOrFail($id);
        $pemesanan->delete();

        return redirect()->route('index')->with('success', 'Order deleted successfully.');
    }
}
