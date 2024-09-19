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
                                <button type="submit" class="btn btn-primary btn-sm"> <i class="bi bi-search"></i></button>
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
                                <tbody id="orderTableBody">
                                    @forelse ($pemesanans as $pemesanan)
                                        <tr data-id="{{ $pemesanan->id }}" data-index="{{ $loop->index }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $pemesanan->atas_nama }}</td>
                                            <td>{{ $pemesanan->nama_design }}</td>
                                            <td>{{ $pemesanan->QTY }}</td>
                                            <td>{{ $pemesanan->tgl_pemesanan }}</td>
                                            <td>{{ $pemesanan->tgn_deadline }}</td>
                                            <td>
                                                @if ($pemesanan->status === 'selesai')
                                                    <span class="badge bg-success">Completed</span>
                                                @else
                                                    <form action="{{ route('mark-as-completed', $pemesanan->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="badge bg-primary border-0">Mark as Completed</button>
                                                    </form>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <button type="button" class="btn btn-info btn-sm me-1" onclick="moveRow(this, -1)">
                                                        <i class="bi bi-arrow-up"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-warning btn-sm me-1" onclick="moveRow(this, 1)">
                                                        <i class="bi bi-arrow-down"></i>
                                                    </button>

                                                    {{-- Delete Action --}}
                                                    <form action="{{ route('delete', $pemesanan->id) }}" method="POST" onsubmit="return confirmDelete('{{ $pemesanan->id }}', '{{ $pemesanan->nama_design }}', '{{ $pemesanan->atas_nama }}');" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </div>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const orderTableBody = document.getElementById('orderTableBody');

        // Load the order from localStorage
        const storedOrder = JSON.parse(localStorage.getItem('rowOrder')) || [];
        reorderRows(storedOrder);

        function moveRow(button, direction) {
            const row = button.closest('tr');
            const index = parseInt(row.getAttribute('data-index'));
            const newIndex = index + direction;

            if (newIndex >= 0 && newIndex < orderTableBody.rows.length) {
                const targetRow = orderTableBody.rows[newIndex];
                if (direction < 0) {
                    row.parentNode.insertBefore(row, targetRow);
                } else {
                    row.parentNode.insertBefore(targetRow, row);
                }

                updateIndexes();
                saveOrder();
            }
        }

        function updateIndexes() {
            Array.from(orderTableBody.rows).forEach((row, index) => {
                row.setAttribute('data-index', index);
            });
        }

        function saveOrder() {
            const order = Array.from(orderTableBody.rows).map(row => ({
                id: row.getAttribute('data-id'),
                index: parseInt(row.getAttribute('data-index'))
            }));
            localStorage.setItem('rowOrder', JSON.stringify(order));
        }

        function reorderRows(order) {
            order.forEach(({ id, index }) => {
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (row) {
                    row.setAttribute('data-index', index);
                    orderTableBody.appendChild(row);
                }
            });
        }

        function confirmDelete(id, namaDesign, atasNama) {
            return confirm('Apakah Anda yakin ingin menghapus pesanan atas nama: ' + atasNama + '?');
        }

        window.moveRow = moveRow;
        window.confirmDelete = confirmDelete;
    });
</script>

@endsection
