@extends('layout.master')
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Create Order</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Form</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
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
                            <label for="tgl_pemesanan" class="form-label">Tanggal Pemesanan:</label>
                            <input type="date" class="form-control" name="tgl_pemesanan" id="tgl_pemesanan" required>
                        </div>

                        <div class="mb-3">
                            <label for="tgn_deathline" class="form-label">Tanggal Deathline:</label>
                            <input type="date" class="form-control" name="tgn_deathline" id="tgn_deathline" required>
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
