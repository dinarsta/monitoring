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

                            <!-- Delete Modal -->
                            <!-- Delete Confirmation Modal -->
                            <div class="modal fade" id="deleteConfirmationModal" tabindex="-1"
                                aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-md">
                                    <div class="modal-content shadow border-0 rounded-4">
                                        <div class="modal-header bg-danger text-white border-0 rounded-top-4">
                                            <h5 class="modal-title" id="deleteConfirmationModalLabel">
                                                <i class="fas fa-trash-alt me-2"></i> Konfirmasi Hapus
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <p class="text-dark mb-4">
                                                Apakah Anda yakin ingin menghapus desain <strong id="designName"
                                                    class="text-danger"></strong>
                                                atas nama <strong id="customerName" class="text-danger"></strong>?
                                            </p>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-outline-secondary w-50"
                                                    data-bs-dismiss="modal">
                                                    Batal
                                                </button>
                                                <form id="deleteForm" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger w-100 shadow-sm">
                                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
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
                                            <th>No.Telp</th>
                                            <th>kategori acara</th>
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
                                        @php
                                        $tglPemesanan = \Carbon\Carbon::parse($pemesanan->tgl_pemesanan);
                                        $tglDeadline = \Carbon\Carbon::parse($pemesanan->tgl_deadline);
                                        $selisihHari = $tglPemesanan->diffInDays($tglDeadline);
                                        $isWarning = $selisihHari <= 2 && $pemesanan->status !== 'selesai';
                                            @endphp
                                            <tr data-id="{{ $pemesanan->id }}" @if ($isWarning) class="table-danger"
                                                @endif>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pemesanan->atas_nama }}</td>
                                                <td>{{ $pemesanan->nama_design }}</td>
                                                <td>{{ substr($pemesanan->telp, 0, 3) . '****' . substr($pemesanan->telp, -3) }}
                                                </td>

                                                <td>{{ $pemesanan->kategori_acara }}</td>
                                                <td>{{ $pemesanan->jenis_barang }}</td>
                                                <td>{{ number_format($pemesanan->QTY, 0, ',', '.') }}</td>
                                                <td>{{ $tglPemesanan->format('d-m-Y') }}</td>
                                                <td>{{ $tglDeadline->format('d-m-Y') }}</td>
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
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        onclick="moveRow(this, -1)" aria-label="Move Up">
                                                        <i class="fas fa-arrow-up"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-warning btn-sm"
                                                        onclick="moveRow(this, 1)" aria-label="Move Down">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </button>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm"
                                                        onclick="openEditModal({{ json_encode($pemesanan) }})"
                                                        aria-label="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                                <td>
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
                                function showDeleteConfirmation(id, nama_design, atas_nama) {
                                    // Mengisi data dalam modal
                                    document.getElementById("designName").textContent = nama_design;
                                    document.getElementById("customerName").textContent = atas_nama;
                                    // Update action form agar sesuai dengan ID yang dipilih
                                    document.getElementById("deleteForm").setAttribute("action", `/pemesanan/${id}`);
                                    // Menampilkan modal
                                    var deleteModal = new bootstrap.Modal(document.getElementById(
                                        "deleteConfirmationModal"));
                                    deleteModal.show();
                                }
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
                                                    <label for="nama_design" class="form-label">No.Telp</label>
                                                    <input type="number" class="form-control" id="telp" name="telp"
                                                        required>
                                                </div>
                                                <!-- Kategori Acara -->
                                                <div class="mb-3">
                                                    <label for="kategori_acara" class="form-label">Kategori
                                                        Acara:</label>
                                                    <select class="form-select" name="kategori_acara"
                                                        id="kategori_acara" required>
                                                        <option value="tempat rekreasi">Tempat Rekreasi</option>
                                                        <option value="event">Event</option>
                                                    </select>
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
                                                    <input type="number" class="form-control" id="QTY" name="QTY"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tgl_pemesanan" class="form-label">Tanggal
                                                        Pemesanan</label>
                                                    <input type="date" class="form-control" id="tgl_pemesanan"
                                                        name="tgl_pemesanan" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tgl_deadline" class="form-label">Tanggal
                                                        Deadline</label>
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
                                function openEditModal(pemesanan) {
                                    document.getElementById('atas_nama').value = pemesanan.atas_nama || '';
                                    document.getElementById('nama_design').value = pemesanan.nama_design || '';
                                    document.getElementById('telp').value = pemesanan.telp || '';
                                    document.getElementById('jenis_barang').value = pemesanan.jenis_barang || '';
                                    document.getElementById('QTY').value = pemesanan.QTY || '';
                                    document.getElementById('tgl_pemesanan').value = pemesanan.tgl_pemesanan || '';
                                    document.getElementById('tgl_deadline').value = pemesanan.tgl_deadline || '';
                                    document.getElementById('kategori_acara').value = pemesanan.kategori_acara ||
                                        'tempat rekreasi'; // Default ke tempat rekreasi
                                    document.getElementById('editOrderForm').action = '/pemesanan/' + pemesanan.id;
                                    var modal = new bootstrap.Modal(document.getElementById('editOrderModal'));
                                    modal.show();
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
            </class>
            @endsection
