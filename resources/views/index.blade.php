@extends('layout.master')
@section('content')
    <h1>Orders</h1>
    <a href="{{ route('create') }}">Create New Order</a>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Atas Nama</th>
                <th>Nama Design</th>
                <th>QTY</th>
                <th>Tanggal Pemesanan</th>
                <th>Tanggal Deathline</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pemesanans as $pemesanan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pemesanan->atas_nama }}</td>
                    <td>{{ $pemesanan->nama_design }}</td>
                    <td>{{ $pemesanan->QTY }}</td>
                    <td>{{ $pemesanan->tgl_pemesanan }}</td>
                    <td>{{ $pemesanan->tgn_deathline }}</td>
                    <td>{{ $pemesanan->status }}</td>
                    <td>
                        <a href="{{ route('pemesanans.show', $pemesanan->id) }}">View</a>
                        <a href="{{ route('pemesanans.edit', $pemesanan->id) }}">Edit</a>
                        <form action="{{ route('pemesanans.destroy', $pemesanan->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
