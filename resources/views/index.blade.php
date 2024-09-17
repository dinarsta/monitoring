@extends('layout.master')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Daftar Pesanan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Daftar Pesanan</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Daftar Pesanan</h5>

                            <!-- Search Form -->
                            <form action="{{ route('index') }}" method="GET" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari pesanan...">
                                    <button type="submit" class="btn btn-primary"> <i class="bi bi-search"></i></button>
                                </div>
                            </form>

                            <!-- Responsive Table -->
                            <div class="table-responsive">
                                <table id="orderTable" class="table table-striped table-hover table-bordered">
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
                                        @forelse ($pemesanans as $pemesanan)
                                            <tr>
                                                <td>{{ $loop->iteration + $pemesanans->firstItem() - 1 }}</td>
                                                <td>{{ $pemesanan->atas_nama }}</td>
                                                <td>{{ $pemesanan->nama_design }}</td>
                                                <td>{{ $pemesanan->QTY }}</td>
                                                <td>{{ $pemesanan->tgl_pemesanan }}</td>
                                                <td>{{ $pemesanan->tgn_deathline }}</td>
                                                <td>
                                                    @if ($pemesanan->status === 'selesai')
                                                        <span class="badge bg-success">Completed</span>
                                                    @else
                                                        <form action="{{ route('mark-as-completed', $pemesanan->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="badge bg-primary border-0">Mark as
                                                                Completed</button>
                                                        </form>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info"
                                                        onclick="moveUp(this)">
                                                        <i class="bi bi-arrow-up"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-warning"
                                                        onclick="moveDown(this)">
                                                        <i class="bi bi-arrow-down"></i>
                                                    </button>

                                                    {{--  --}}
                                                    <form action="{{ route('delete', $pemesanan->id) }}" method="POST"
                                                        onsubmit="return confirmDelete('{{ $pemesanan->id }}', '{{ $pemesanan->nama_design }}', '{{ $pemesanan->atas_nama }}');"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada data ditemukan</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- End Responsive Table -->

                            {{-- <!-- Pagination -->
                            <div class="d-flex justify-content-end mt-3">
                                {{ $pemesanans->appends(request()->query())->links() }}
                            </div> --}}

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <script>
        $(document).ready(function() {
            $('#orderTable').DataTable({
                "order": [], // Disable initial sorting
                "paging": true, // Enable DataTables pagination
                "pageLength": 10, // Number of rows per page
                "searching": false // Disable DataTables search to use Laravel search
            });
        });

        // Move row up
        function moveUp(btn) {
            var row = btn.closest('tr');
            if (row.previousElementSibling) {
                row.parentNode.insertBefore(row, row.previousElementSibling);
            }
        }

        // Move row down
        function moveDown(btn) {
            var row = btn.closest('tr');
            if (row.nextElementSibling) {
                row.parentNode.insertBefore(row.nextElementSibling, row);
            }
        }

        function confirmDelete(id, namaDesign, atasNama) {
            return confirm('Apakah Anda yakin ingin menghapus pesanan atas nama: ' + atasNama + '?');
        }
    </script>
@endsection
