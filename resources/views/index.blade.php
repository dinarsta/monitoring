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
            </div>
            <!-- End Page Title -->

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
                                    <select name="month" class="form-select">
                                        <option value="">-- Pilih Bulan --</option>
                                        @foreach (range(1, 12) as $month)
                                            <option value="{{ $month }}"
                                                {{ request('month') == $month ? 'selected' : '' }}>
                                                {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="year" class="form-select">
                                        <option value="">-- Pilih Tahun --</option>
                                        @foreach (range(date('Y'), 2000) as $year)
                                            <option value="{{ $year }}"
                                                {{ request('year') == $year ? 'selected' : '' }}>
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

                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteConfirmationModal" tabindex="-1"
                                aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah anda yakin ingin menghapus desain <strong id="designName"></strong>
                                                atas nama <strong id="customerName"></strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-danger"
                                                id="confirmDeleteButton">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table Structure -->
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
                                        @php
                                            $lastRowTglPemesanan = null; // Variabel untuk menyimpan tanggal pemesanan baris terakhir
                                        @endphp
                                        @foreach ($pemesanans as $pemesanan)
                                            @php
                                                // Dapatkan tanggal sekarang
                                                $now = \Carbon\Carbon::now();
                                                $tglPemesanan = \Carbon\Carbon::parse($pemesanan->tgl_pemesanan);
                                                $tglDeadline = \Carbon\Carbon::parse($pemesanan->tgl_deadline);

                                                // Perhitungan selisih hari
                                                $isPesanClose = $now->diffInDays($tglPemesanan, false) <= 1;
                                                $isDeadlineClose = $now->diffInDays($tglDeadline, false) <= 1;

                                                // Status apakah "selesai"
                                                $isDone = $pemesanan->status === 'selesai';

                                                // Logika untuk warna merah hanya berlaku jika tgl_pemesanan dan tgl_deadline dekat dengan tanggal sekarang
                                                $highlightPesanan = !$isDone && $isPesanClose && $isDeadlineClose;
                                            @endphp
                                            <tr>
                                                <td class="{{ $highlightPesanan ? 'bg-danger text-white' : '' }}">
                                                    {{ $loop->iteration }}</td>
                                                <td class="{{ $highlightPesanan ? 'bg-danger text-white' : '' }}">
                                                    {{ $pemesanan->atas_nama }}</td>
                                                <td class="{{ $highlightPesanan ? 'bg-danger text-white' : '' }}">
                                                    {{ $pemesanan->nama_design }}</td>
                                                <td class="{{ $highlightPesanan ? 'bg-danger text-white' : '' }}">
                                                    {{ $pemesanan->jenis_barang }}</td>
                                                <td class="{{ $highlightPesanan ? 'bg-danger text-white' : '' }}">
                                                    {{ number_format($pemesanan->QTY, 0, ',', '.') }}</td>
                                                <td class="{{ $highlightPesanan ? 'bg-danger text-white' : '' }}">
                                                    {{ $tglPemesanan->format('d-m-Y') }}
                                                </td>
                                                <td
                                                    class="{{ !$isDone && $isDeadlineClose ? 'bg-danger text-white' : '' }}">
                                                    {{ $tglDeadline->format('d-m-Y') }}
                                                </td>
                                                <td class="{{ $highlightPesanan ? 'bg-danger text-white' : '' }}">
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
                                                <td class="{{ $highlightPesanan ? 'bg-danger text-white' : '' }}">
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        onclick="moveRow(this, -1)" aria-label="Move Up">
                                                        <i class="fas fa-arrow-up"></i>
                                                    </button>
                                                </td>
                                                <td class="{{ $highlightPesanan ? 'bg-danger text-white' : '' }}">
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        onclick="moveRow(this, 1)" aria-label="Move Down">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </button>
                                                </td>
                                                <td class="{{ $highlightPesanan ? 'bg-danger text-white' : '' }}">
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        onclick="openEditModal({{ json_encode($pemesanan) }})"
                                                        aria-label="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                                <td class="{{ $highlightPesanan ? 'bg-danger text-white' : '' }}">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="showDeleteConfirmation({{ $pemesanan->id }}, '{{ $pemesanan->nama_design }}', '{{ $pemesanan->atas_nama }}')"
                                                        aria-label="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>


                            <script>
                                let deleteFormAction;

                                function showDeleteConfirmation(id, designName, customerName) {
                                    // Set the design and customer names in the modal
                                    document.getElementById('designName').textContent = designName;
                                    document.getElementById('customerName').textContent = customerName;

                                    // Set the form action for deletion
                                    deleteFormAction = "{{ route('pemesanan.destroy', ':id') }}".replace(':id', id);

                                    // Show the modal
                                    var deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
                                    deleteModal.show();
                                }

                                document.getElementById('confirmDeleteButton').addEventListener('click', function() {
                                    // Create and submit a form dynamically to delete the item
                                    const form = document.createElement('form');
                                    form.method = 'POST';
                                    form.action = deleteFormAction;
                                    form.innerHTML = '@csrf @method('DELETE')';
                                    document.body.appendChild(form);
                                    form.submit();
                                });
                            </script>



                            <!-- Edit Modal -->
                            <div class="modal fade" id="editOrderModal" tabindex="-1"
                                aria-labelledby="editOrderModalLabel" aria-hidden="true">
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
                                document.addEventListener('DOMContentLoaded', function() {
                                    loadRowOrder();
                                });

                                function moveRow(button, direction) {
                                    const row = button.closest('tr');
                                    const orderTableBody = document.getElementById('orderTableBody');

                                    if (direction === -1 && row.previousElementSibling) {
                                        orderTableBody.insertBefore(row, row.previousElementSibling);
                                    } else if (direction === 1 && row.nextElementSibling) {
                                        orderTableBody.insertBefore(row.nextElementSibling, row);
                                    }

                                    updateIndexes(); // Update row numbers after movement
                                    saveRowOrder(); // Save the new order
                                }

                                function openEditModal(pemesanan) {
                                    document.getElementById('atas_nama').value = pemesanan.atas_nama || '';
                                    document.getElementById('nama_design').value = pemesanan.nama_design || '';
                                    document.getElementById('jenis_barang').value = pemesanan.jenis_barang || '';
                                    document.getElementById('QTY').value = pemesanan.QTY || '';
                                    document.getElementById('tgl_pemesanan').value = pemesanan.tgl_pemesanan || '';
                                    document.getElementById('tgl_deadline').value = pemesanan.tgl_deadline || '';

                                    document.getElementById('editOrderForm').action = '/pemesanan/' + pemesanan.id;

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

                                function loadRowOrder() {
                                    const orderTableBody = document.getElementById('orderTableBody');
                                    let savedOrder = localStorage.getItem('rowOrder');
                                    let allRows = Array.from(orderTableBody.querySelectorAll('tr'));

                                    if (savedOrder) {
                                        let order = JSON.parse(savedOrder);

                                        // Append rows in saved order
                                        order.forEach(id => {
                                            let row = document.querySelector(`tr[data-id="${id}"]`);
                                            if (row) orderTableBody.appendChild(row);
                                        });

                                        // Append any new rows that weren't saved in the previous order
                                        allRows.forEach(row => {
                                            if (!order.includes(row.dataset.id)) {
                                                orderTableBody.appendChild(row);
                                            }
                                        });

                                        updateIndexes(); // Update row numbers after loading the saved order
                                    }
                                }

                                function saveRowOrder() {
                                    const order = Array.from(document.querySelectorAll('#orderTableBody tr')).map(row => row.dataset.id);
                                    localStorage.setItem('rowOrder', JSON.stringify(order));
                                }

                                function addNewRow(data) {
                                    const orderTableBody = document.getElementById('orderTableBody');

                                    // Create a new row and append data
                                    const newRow = document.createElement('tr');
                                    newRow.setAttribute('data-id', data.id); // Set data-id for saving order
                                    newRow.innerHTML = `
                                        <td></td> <!-- For the index, which will be updated -->
                                        <td>${data.atas_nama}</td>
                                        <td>${data.nama_design}</td>
                                        <td>${data.jenis_barang}</td>
                                        <td>${data.QTY}</td>
                                        <td>${data.tgl_pemesanan}</td>
                                        <td>${data.tgl_deadline}</td>
                                        <td>
                                            <!-- Add any actions you want, like edit or delete buttons -->
                                            <button onclick="openEditModal(${JSON.stringify(data)})">Edit</button>
                                            <button onclick="confirmDelete(${data.id}, '${data.nama_design}', '${data.atas_nama}')">Delete</button>
                                        </td>
                                    `;

                                    // Append new row to the end of the table
                                    orderTableBody.appendChild(newRow);

                                    // Update index and save new order
                                    updateIndexes();
                                    saveRowOrder();
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            </class>
        @endsection
