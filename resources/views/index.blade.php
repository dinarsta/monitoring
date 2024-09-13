@extends('layout.master')
@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Order List</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Daftar Pesanan</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Management</h5>

                            <!-- Responsive Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Atas Nama</th>
                                            <th>Nama Design</th>
                                            <th>QTY</th>
                                            <th>Tanggal Pemesanan</th>
                                            <th>Tanggal Deadline</th>
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
                                                <td>
                                                    @if ($pemesanan->status === 'selesai')
                                                        <span class="badge bg-success">Completed</span>
                                                    @else
                                                        <form action="{{ route('mark-as-completed', $pemesanan->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="badge bg-primary border-0" style="cursor: pointer;">Mark as Completed</button>
                                                        </form>
                                                    @endif
                                                </td>
                                                <td class="d-flex">
                                                    <form action="{{ route('delete', $pemesanan->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this order?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- End Responsive Table -->

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
@endsection
