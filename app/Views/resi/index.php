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
                <h3 class="card-title"><a class="btn btn-primary btn-sm" href="<?= base_url('admin/resi/new') ?>">Tambah</a></h3>
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
                        <th>No Resi</th>
                        <th>Expedisi</th>
                        <th>Nama Customer</th>
                        <th>No Telpon</th>
                        <th>Nama Barang</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                   <?php $no =1; foreach ($Resi as $key => $value) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $value->no_resi ?></td>
                            <td><?= expedisi($value->ekspedisi) ?></td>
                            <td><?= $value->nama_customer ?></td>
                            <td><?= $value->no_telp ?></td>
                            <td><?= $value->nama_barang ?></td>
                            <td>Rp. <?= number_format($value->harga, 0,'.',',') ?></td>
                            <td>
                               <div class="d-flex">
                                    <a class="btn btn-info btn-sm mr-2" href="<?= site_url('admin/resi/' . $value->resi_id) ?>">Detail</a>
                                    <a class="btn btn-warning btn-sm mr-2" href="<?= site_url('admin/resi/edit/' . $value->resi_id) ?>/edit">Edit</a>
                                    <a class="btn btn-danger btn-sm mr-2" href="<?= site_url('admin/resi/delete/' . $value->resi_id) ?>">Hapus</a>
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