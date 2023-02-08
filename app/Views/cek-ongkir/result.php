<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $sub_title ?></h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- general form elements -->
        <div class="card card">
            <div class="card-header">
                <h3 class="card-title"><a class="btn btn-primary btn-sm" href="<?= site_url('resi') ?>">Kembali</a></h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="<?= site_url('resi') ?>">
                <div class="card-body">
                    <?php if (session('error') !== null) : ?>
                        <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
                    <?php endif ?>

                    <?php if (session('message') !== null) : ?>
                        <div class="alert alert-success" role="alert"><?= session('message') ?></div>
                    <?php endif ?>

                    <div class="my-5 shadow card">
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td>Dari</td>
                                    <td>:</td>
                                    <td>{{ $origin['city_name'] }}, Kab. {{ $origin['city_name'] }}</td>
                                </tr>
                                <tr>
                                    <td>Tujuan</td>
                                    <td>:</td>
                                    <td>{{ $destination['city_name'] }}, Kab. {{ $destination['city_name'] }}</td>
                                </tr>
                                <tr>
                                    <td>Berat (g)</td>
                                    <td>:</td>
                                    <td>{{ $berat }}</td>
                                </tr>
                                <tr>
                                    <td>Kurir</td>
                                    <td>:</td>
                                    <td>{{ $namaKurir }}</td>
                                </tr>
                            </table>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Nama Layanan</th>
                                        <th scope="col">Biaya</th>
                                        <th scope="col">ETD (Estimates Days)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rows as $cost)
                                    <tr>
                                        <td>{{ $cost['description'] }}</td>
                                        <td>Rp. {{ number_format($cost['biaya'], 0, '.', '.') }}</td>
                                        <td>{{ $cost['etd'] }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <a href="{{ route('home') }}" class="btn btn-success">👈 Kembali</a>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Cek</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>