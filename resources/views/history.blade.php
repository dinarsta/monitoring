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

                        <!-- Search Form -->
                        <form action="{{ route('history') }}" method="GET" class="mb-3">
                            <div class="input-group">
                                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                    placeholder="Cari pesanan..." aria-label="Search orders">

                                <select name="month" class="form-select">
                                    <option value="">-- Pilih Bulan --</option>
                                    @foreach (range(1, 12) as $month)
                                    <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                    </option>
                                    @endforeach
                                </select>

                                <select name="year" class="form-select">
                                    <option value="">-- Pilih Tahun --</option>
                                    @foreach (range(date('Y'), 2000) as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                    @endforeach
                                </select>

                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Responsive Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>Atas Nama</th>
                                        <th>Nama Design</th>
                                        <th>No.Telp</th>
                                        <th>Kategori Acara</th> <!-- Kolom Kategori Acara Ditambahkan -->
                                        <th>QTY</th>
                                        <th>Tanggal Pemesanan</th>
                                        <th>Tanggal Deadline</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($deletedOrders as $order)
                                    <tr>
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->atas_nama }}</td>
                                        <td>{{ $order->nama_design }}</td>
                                        <td>{{ substr($order->telp, 0, 3) . '****' . substr($order->telp, -3) }}</td>
                                        <td>{{ $order->kategori_acara ?? 'Tidak Ada Kategori' }}</td> <!-- Menampilkan Kategori Acara -->
                                        <td>{{ number_format($order->QTY, 0, ',', '.') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($order->tgl_pemesanan)->format('d-m-Y') }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($order->tgl_deadline)->format('d-m-Y') }}</td>
                                        <td>
                                            @if ($order->status === 'selesai')
                                            <span class="badge bg-success">DONE</span>
                                            @elseif ($order->status === 'on progress')
                                            <span class="badge bg-primary">On Progress</span>
                                            @else
                                            <span class="badge bg-secondary">Pending</span>
                                            @endif
                                        </td>

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
