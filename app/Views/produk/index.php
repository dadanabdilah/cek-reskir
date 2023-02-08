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
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><a class="btn btn-primary btn-sm" href="<?= base_url('admin/produk/new') ?>">Tambah</a></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <?php if (session('error') !== null) : ?>
                    <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
                <?php endif ?>

                <?php if (session('message') !== null) : ?>
                    <div class="alert alert-success" role="alert"><?= session('message') ?></div>
                <?php endif ?>

                <table id="datatable" class="table table-bordered table-hover">
                  <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Kelompok Produk</th>
                        <th>Berat Produk</th>
                        <th>Harga Produk</th>
                        <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                   <?php $no =1; foreach ($Produk as $key => $value) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $value->kode_barang ?></td>
                            <td><?= $value->nama_barang ?></td>
                            <td><?= $value->kelompok_barang ?></td>
                            <td><?= $value->berat ?></td>
                            <td>Rp. <?= number_format($value->harga, 0,'.',',') ?></td>
                            <td>
                               <div class="d-flex">
                                    <a class="btn btn-warning btn-sm mr-2" href="<?= site_url('admin/produk/edit/' . $value->id) ?>/edit">Edit</a>
                                    <a class="btn btn-danger btn-sm mr-2" href="<?= site_url('admin/produk/delete/' . $value->id) ?>">Hapus</a>
                               </div>
                            </td>
                        </tr>
                   <?php } ?>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>