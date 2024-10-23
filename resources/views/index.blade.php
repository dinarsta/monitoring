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
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control" placeholder="Cari pesanan...">
                                    <button type="submit" class="btn btn-primary btn-sm"> <i
                                            class="bi bi-search"></i></button>
                                </div>
                            </form>


                            <!-- Responsive Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Atas Nama</th>
                                            <th>Nama Design</th>
                                            <th>Jenis Barang</th>
                                            <th>QTY</th>
                                            <th>Tanggal Pemesanan</th>
                                            <th>Tanggal Deadline</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderTableBody">
                                        @foreach ($pemesanans as $pemesanan)
                                            <tr data-id="{{ $pemesanan->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pemesanan->atas_nama }}</td>
                                                <td>{{ $pemesanan->nama_design }}</td>
                                                <td>{{ $pemesanan->jenis_barang }}</td>
                                                <td>{{ $pemesanan->QTY }}</td>
                                                <td>{{ $pemesanan->tgl_pemesanan }}</td>
                                                <td>{{ $pemesanan->tgl_deadline }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-small" onclick="moveRow(this, -1)" title="Move Up">
                                                        <i class="fas fa-arrow-up icon-small"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-warning btn-small" onclick="moveRow(this, 1)" title="Move Down">
                                                        <i class="fas fa-arrow-down icon-small"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-info btn-small" onclick="openEditModal({{ json_encode($pemesanan) }})" title="Edit">
                                                        <i class="fas fa-edit icon-small"></i>
                                                    </button>
                                                    <form action="{{ route('pemesanan.destroy', $pemesanan->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-small" onclick="return confirmDelete({{ $pemesanan->id }}, '{{ $pemesanan->nama_design }}', '{{ $pemesanan->atas_nama }}')" title="Delete">
                                                            <i class="fas fa-trash icon-small"></i>
                                                        </button>
                                                    </form>
                                                </td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <form id="editOrderForm" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editOrderModalLabel">Edit Order</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="atas_nama" class="form-label">Atas Nama</label>
                                                    <input type="text" class="form-control" id="atas_nama"
                                                        name="atas_nama" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="nama_design" class="form-label">Nama Design</label>
                                                    <input type="text" class="form-control" id="nama_design"
                                                        name="nama_design" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="jenis_barang" class="form-label">Jenis Barang</label>
                                                    <input type="text" class="form-control" id="jenis_barang"
                                                        name="jenis_barang" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="QTY" class="form-label">QTY</label>
                                                    <input type="number" class="form-control" id="QTY" name="QTY"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tgl_pemesanan" class="form-label">Tanggal Pemesanan</label>
                                                    <input type="date" class="form-control" id="tgl_pemesanan"
                                                        name="tgl_pemesanan" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tgl_deadline" class="form-label">Tanggal Deadline</label>
                                                    <input type="date" class="form-control" id="tgl_deadline"
                                                        name="tgl_deadline" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script>
                            function moveRow(button, direction) {
                                const orderTableBody = document.getElementById('orderTableBody');
                                const row = button.closest('tr');
                                const index = Array.from(orderTableBody.rows).indexOf(row);
                                const newIndex = index + direction;

                                if (newIndex >= 0 && newIndex < orderTableBody.rows.length) {
                                    if (direction < 0) {
                                        orderTableBody.insertBefore(row, orderTableBody.rows[newIndex]);
                                    } else {
                                        orderTableBody.insertBefore(row, orderTableBody.rows[newIndex + 1]);
                                    }
                                    updateIndexes();
                                    saveOrder();
                                }
                            }

                            function updateIndexes() {
                                const orderTableBody = document.getElementById('orderTableBody');
                                Array.from(orderTableBody.rows).forEach((row, index) => {
                                    row.setAttribute('data-index', index);
                                    row.querySelector('td:first-child').innerText = index + 1;
                                });
                            }

                            function saveOrder() {
                                const orderTableBody = document.getElementById('orderTableBody');
                                const order = Array.from(orderTableBody.rows).map(row => ({
                                    id: row.getAttribute('data-id'),
                                    index: parseInt(row.getAttribute('data-index'))
                                }));
                                localStorage.setItem('rowOrder', JSON.stringify(order));
                            }

                            window.openEditModal = function(pemesanan) {
                                document.getElementById('editOrderModalLabel').innerText = 'Edit Pesanan';
                                document.getElementById('atas_nama').value = pemesanan.atas_nama;
                                document.getElementById('nama_design').value = pemesanan.nama_design;
                                document.getElementById('jenis_barang').value = pemesanan.jenis_barang;
                                document.getElementById('QTY').value = pemesanan.QTY;
                                document.getElementById('tgl_pemesanan').value = pemesanan.tgl_pemesanan;
                                document.getElementById('tgl_deadline').value = pemesanan.tgl_deadline;

                                const form = document.getElementById('editOrderForm');
                                form.action = `/pemesanan/${pemesanan.id}`;
                                var modal = new bootstrap.Modal(document.getElementById('editOrderModal'));
                                modal.show();
                            }

                            function confirmDelete(id, designName, atasNama) {
                                return confirm(`Are you sure you want to delete order ${designName} for ${atasNama}?`);
                            }


                        </script>

                    @endsection
