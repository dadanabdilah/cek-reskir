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
                <h3 class="card-title">
                    <a class="btn btn-primary btn-sm" href="<?= site_url('resi') ?>">Kembali</a>
                </h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="<?= site_url('resi/' . $Resi->resi_id ) ?>">
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
                        <label for="exampleInputEmail1">Nama Customer</label>
                        <input type="text" class="form-control" value="<?= $Resi->nama_customer ?>" id="nama_customer" name="nama_customer">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">No Telpon</label>
                        <input type="text" class="form-control" value="<?= $Resi->no_telp ?>" id="no_telp" name="no_telp">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">No Resi</label>
                        <input type="text" class="form-control" value="<?= $Resi->no_resi ?>" id="no_resi" name="no_resi">
                    </div>
                    <div class="form-group">
                        <label>Nama Barang</label>
                        <select name="kode_barang" class="form-control">
                        <option selected disabled>Pilih...</option>
                        <?php foreach ($Produk as $key => $value) { ?>
                          <option value="<?= $value->kode_barang ?>" <?= $Resi->kode_barang == $value->kode_barang ? "selected" : "" ; ?> ><?= $value->nama_barang ?></option>
                        <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Expedisi</label>
                        <select name="ekspedisi" class="form-control">
                            <option selected disabled>Pilih...</option>
                            <?php foreach (expedisi() as $key => $value) { ?>
                                <option value="<?= $key ?>" <?= $Resi->ekspedisi == $key ? "selected" : "" ; ?> ><?= $value ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Harga</label>
                        <input type="number" class="form-control" value="<?= $Resi->harga ?>" id="harga" name="harga">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Tanggal Pencatatan</label>
                        <input type="date" value="<?= date("Y-m-d") ?>" class="form-control" value="<?= $Resi->tanggal_pencatatan ?>" id="tanggal_pencatatan" name="tanggal_pencatatan">
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