@extends('layout.master')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Create Order</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Order Form</li>
                </ol>
            </nav>
        </div>

        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Order Form</h5>

                            <!-- Form -->
                            <form action="{{ route('store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="atas_nama" class="form-label">Atas Nama:</label>
                                    <input type="text" class="form-control" name="atas_nama" id="atas_nama" required>
                                </div>

                                <div class="mb-3">
                                    <label for="nama_design" class="form-label">Nama Design:</label>
                                    <input type="text" class="form-control" name="nama_design" id="nama_design" required>
                                </div>

                                <div class="mb-3">
                                    <label for="QTY" class="form-label">QTY:</label>
                                    <input type="number" class="form-control" name="QTY" id="QTY" required>
                                </div>

                                <div class="mb-3">
                                    <label for="jenis_barang" class="form-label">Jenis Barang:</label>
                                    <select class="form-select" name="jenis_barang" id="jenis_barang" onchange="toggleJenisBarangLain()">
                                        <option value="gelang">Gelang</option>
                                        <option value="gelang lanyard">Gelang Lanyard</option>
                                        <option value="gelang kertas">Gelang Kertas</option>
                                        <option value="tali lanyard">Tali Lanyard</option>
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <!-- Conditional Input for Other Types of Goods -->
                                <div class="mb-3" id="jenis_barang_lain_container" style="display: none;">
                                    <label for="jenis_barang_lain" class="form-label">Jenis Barang Lainnya:</label>
                                    <input type="text" class="form-control" name="jenis_barang_lain" id="jenis_barang_lain" maxlength="255">
                                </div>

                                <script>
                                    function toggleJenisBarangLain() {
                                        const jenisBarangSelect = document.getElementById('jenis_barang');
                                        const jenisBarangLainContainer = document.getElementById('jenis_barang_lain_container');

                                        if (jenisBarangSelect.value === 'lainnya') {
                                            jenisBarangLainContainer.style.display = 'block';
                                        } else {
                                            jenisBarangLainContainer.style.display = 'none';
                                            document.getElementById('jenis_barang_lain').value = '';
                                        }
                                    }
                                </script>

                                <div class="mb-3">
                                    <label for="kategori_acara" class="form-label">Kategori Acara:</label>
                                    <select class="form-select" name="kategori_acara" id="kategori_acara" required>
                                        <option value="tempat rekreasi">Tempat Rekreasi</option>
                                        <option value="event">Event</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="tgl_pemesanan" class="form-label">Tanggal Pemesanan:</label>
                                    <input type="date" class="form-control" name="tgl_pemesanan" id="tgl_pemesanan" required>
                                </div>

                                <div class="mb-3">
                                    <label for="tgl_deadline" class="form-label">Tanggal Deadline:</label>
                                    <input type="date" class="form-control" name="tgl_deadline" id="tgl_deadline" required>
                                </div>

                                <button type="submit" class="btn btn-primary">Create Order</button>
                            </form>
                            <!-- End Form -->

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
