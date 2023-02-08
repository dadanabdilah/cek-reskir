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
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"></h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="<?= site_url('produk/' . $Produk->id ) ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT" />
                <div class="card-body">
                    <?php if (session('error') !== null) : ?>
                        <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
                    <?php endif ?>

                    <?php if (session('message') !== null) : ?>
                        <div class="alert alert-success" role="alert"><?= session('message') ?></div>
                    <?php endif ?>

                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" class="form-control" value="<?= $Produk->nama_barang ?>" id="nama_barang" name="nama_barang">
                    </div>
                    <div class="form-group">
                        <label>Kelompok Produk</label>
                        <input type="text" class="form-control" value="<?= $Produk->kelompok_barang ?>" id="kelompok_barang" name="kelompok_barang" autocomplte="off">
                    </div>
                    <div class="form-group">
                        <label>Berat Produk (Gram)</label>
                        <input type="number" class="form-control" value="<?= $Produk->berat ?>" id="berat" name="berat" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label>Harga Produk</label>
                        <input type="number" class="form-control" value="<?= $Produk->harga ?>" id="harga" name="harga">
                    </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>