@extends('layouts.app')

@section('content')
    <h1>Order Details</h1>
    <p>Atas Nama: {{ $pemesanan->atas_nama }}</p>
    <p>Nama Design: {{ $pemesanan->nama_design }}</p>
    <p>QTY: {{ $pemesanan->QTY }}</p>
    <p>Tanggal Pemesanan: {{ $pemesanan->tgl_pemesanan }}</p>
    <p>Tanggal Deathline: {{ $pemesanan->tgn_deathline }}</p>
    <p>Status: {{ $pemesanan->status }}</p>
    <a href="{{ route('pemesanans.index') }}">Back</a>
@endsection
