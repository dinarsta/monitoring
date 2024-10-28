@extends('layout.master')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Daftar Pesanan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Daftar History</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <div class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Daftar Pesanan</h5>



                            <!-- Responsive Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>NO</th>
                                            <th>Atas Nama</th>
                                            <th>Nama Design</th>
                                            <th>QTY</th>
                                            <th>Tanggal Pemesanan</th>
                                            <th>Tanggal Deadline</th>
                                            <th>Deleted At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deletedOrders as $order)
                                            <tr>
                                                <td>{{ $order->id }}</td>
                                                <td>{{ $order->atas_nama }}</td>
                                                <td>{{ $order->nama_design }}</td>
                                                <td>{{ number_format($order->QTY, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($order->tgl_pemesanan)->format('d-m-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($order->tgl_deadline)->format('d-m-Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($order->deleted_at)->format('d-m-Y H:i') }}</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- End Section Dashboard -->
    </main>
@endsection
