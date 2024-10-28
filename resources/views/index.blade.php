@extends('layout.master')

@section('content')
<div id="ordersContainer">
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

        <class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Daftar Pesanan</h5>

                            <!-- Search Form -->
                            <form action="{{ route('index') }}" method="GET" class="mb-3">
                                <div class="input-group">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        class="form-control" placeholder="Cari pesanan..." aria-label="Search orders">
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
                                            <th>No</th>
                                            <th>Atas Nama</th>
                                            <th>Nama Design</th>
                                            <th>Jenis Barang</th>
                                            <th>QTY</th>
                                            <th>Tanggal Pemesanan</th>
                                            <th>Tanggal Deadline</th>
                                            <th>Status</th>
                                            <th colspan="3">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderTableBody">
                                        @foreach ($pemesanans as $pemesanan)
                                            <tr data-id="{{ $pemesanan->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pemesanan->atas_nama }}</td>
                                                <td>{{ $pemesanan->nama_design }}</td>
                                                <td>{{ $pemesanan->jenis_barang }}</td>
                                                <td>{{ number_format($pemesanan->QTY, 0, ',', '.') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($pemesanan->tgl_pemesanan)->format('d-m-Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($pemesanan->tgl_deadline)->format('d-m-Y') }}
                                                </td>
                                                <td>
                                                <td>
                                                    @if ($pemesanan->status === 'selesai')
                                                        <span class="badge bg-success">DONE</span>
                                                    @else
                                                        <form action="{{ route('mark-as-completed', $pemesanan->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="badge bg-primary border-0">ON
                                                                PROGRESS</button>
                                                        </form>
                                                    @endif
                                                </td>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-small"
                                                        onclick="moveRow(this, -1)" aria-label="Move Up">
                                                        <i class="fas fa-arrow-up icon-small"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-small"
                                                        onclick="moveRow(this, 1)" aria-label="Move Down">
                                                        <i class="fas fa-arrow-down icon-small"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-small"
                                                        onclick="openEditModal({{ json_encode($pemesanan) }})"
                                                        aria-label="Edit">
                                                        <i class="fas fa-edit icon-small"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <form action="{{ route('pemesanan.destroy', $pemesanan->id) }}"
                                                        method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-small"
                                                            onclick="return confirmDelete({{ $pemesanan->id }}, '{{ $pemesanan->nama_design }}', '{{ $pemesanan->atas_nama }}')"
                                                            aria-label="Delete">
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
                                                <h5 class="modal-title" id="editOrderModalLabel">Edit Pesanan</h5>
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
                                                    <select class="form-select" name="jenis_barang" id="jenis_barang"
                                                        onchange="toggleJenisBarangLain()">
                                                        <option value="gelang">Gelang</option>
                                                        <option value="gelang lanyard">Gelang Lanyard</option>
                                                        <option value="gelang kertas">Gelang Kertas</option>
                                                        <option value="tali lanyard">Tali Lanyard</option>
                                                        <option value="lainnya">Lainnya</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3" id="jenis_barang_lain_div" style="display: none;">
                                                    <label for="jenis_barang_lain" class="form-label">Jenis Barang
                                                        Lain:</label>
                                                    <input type="text" class="form-control" id="jenis_barang_lain"
                                                        name="jenis_barang_lain">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="QTY" class="form-label">QTY</label>
                                                    <input type="number" class="form-control" id="QTY"
                                                        name="QTY" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tgl_pemesanan" class="form-label">Tanggal
                                                        Pemesanan</label>
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


                            <script>
                                $(document).ready(function() {
                                    // Function to fetch the latest orders
                                    function fetchLatestOrders() {
                                        $.ajax({
                                            url: "{{ route('orders.latest') }}",
                                            method: 'GET',
                                            success: function(data) {
                                                // Assuming you have a container with id 'ordersContainer'
                                                var ordersContainer = $('#ordersContainer');
                                                ordersContainer.empty(); // Clear existing orders

                                                // Append latest orders to the container
                                                data.forEach(function(order) {
                                                    ordersContainer.append(
                                                        `<div class="order">
                                                            <p><strong>Atas Nama:</strong> ${order.atas_nama}</p>
                                                            <p><strong>Nama Design:</strong> ${order.nama_design}</p>
                                                            <p><strong>QTY:</strong> ${order.QTY}</p>
                                                            <p><strong>Tgl Pemesanan:</strong> ${order.tgl_pemesanan}</p>
                                                            <p><strong>Tgl Deadline:</strong> ${order.tgl_deadline}</p>
                                                            <p><strong>Status:</strong> ${order.status}</p>
                                                        </div>`
                                                    );
                                                });
                                            },
                                            error: function(xhr) {
                                                console.error('Failed to fetch orders:', xhr);
                                            }
                                        });
                                    }

                                    // Set interval for fetching orders every 5 seconds (5000 ms)
                                    setInterval(fetchLatestOrders, 5000);
                                });
                                </script>


                           <script>
                                function moveRow(button, direction) {
                                    const row = button.closest('tr');
                                    const orderTableBody = document.getElementById('orderTableBody');

                                    if (direction === -1 && row.previousElementSibling) {
                                        orderTableBody.insertBefore(row, row.previousElementSibling);
                                    } else if (direction === 1 && row.nextElementSibling) {
                                        orderTableBody.insertBefore(row.nextElementSibling, row);
                                    }
                                    updateIndexes(); // Update row numbers after movement
                                }

                                function openEditModal(pemesanan) {
                                    document.getElementById('atas_nama').value = pemesanan.atas_nama || '';
                                    document.getElementById('nama_design').value = pemesanan.nama_design || '';
                                    document.getElementById('jenis_barang').value = pemesanan.jenis_barang || '';
                                    document.getElementById('QTY').value = pemesanan.QTY || '';
                                    document.getElementById('tgl_pemesanan').value = pemesanan.tgl_pemesanan || '';
                                    document.getElementById('tgl_deadline').value = pemesanan.tgl_deadline || '';

                                    // Update the form action URL for editing
                                    document.getElementById('editOrderForm').action = '/pemesanan/' + pemesanan.id;

                                    // Show modal
                                    var modal = new bootstrap.Modal(document.getElementById('editOrderModal'));
                                    modal.show();
                                }

                                function confirmDelete(id, nama_design, atas_nama) {
                                    return confirm(`Apakah Anda yakin ingin menghapus pesanan ${nama_design} atas nama ${atas_nama}?`);
                                }

                                function updateIndexes() {
                                    const rows = document.querySelectorAll('#orderTableBody tr');
                                    rows.forEach((row, index) => {
                                        row.cells[0].textContent = index + 1; // Update the first column with new index
                                    });
                                }

                                function toggleJenisBarangLain() {
                                    const jenisBarangSelect = document.getElementById('jenis_barang');
                                    const jenisBarangLainDiv = document.getElementById('jenis_barang_lain_div');
                                    jenisBarangLainDiv.style.display = jenisBarangSelect.value === 'lainnya' ? 'block' : 'none';
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </class>
        @endsection
