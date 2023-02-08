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
                <h3 class="card-title"><a class="btn btn-primary btn-sm" href="<?= base_url('resi') ?>">Kembali</a></h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <?php if (session('error') !== null) : ?>
                    <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
                <?php endif ?>

                <?php if (session('message') !== null) : ?>
                    <div class="alert alert-success" role="alert"><?= session('message') ?></div>
                <?php endif ?>

                <div class="row">
                  <div class="col-md-6">
                  <ul class="list-group list-group-flush text-bold mb-3">
                    <li class="list-group-item pb-0 mt-0">Nomor Resi : <?= $Resi->no_resi ?></li>
                    <li class="list-group-item pb-0 mt-0">Expedisi : <?= expedisi($Resi->ekspedisi) ?></li>
                    <li class="list-group-item pb-0 mt-0">Nama Customer : <?= $Resi->nama_customer ?></li>
                    <li class="list-group-item pb-0 mt-0">Nomor Telpon : <?= $Resi->no_telp ?></li>
                    <li class="list-group-item pb-0 mt-0">Tanggal Pencatatan : <?= str_replace('-', '/', $Resi->tanggal_pencatatan) ?></li>
                    <li class="list-group-item"></li>
                  </ul>
                  </div>
                </div>

                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Lokasi</th>
                    </tr>
                  </thead>
                  <tbody>
                   <?php $no =1; foreach ($Resis as $key => $value) { ?>
                        <tr>
                            <td><?= $value->date ?></td>
                            <td><?= $value->description ?></td>
                            <td><?= $value->location ?></td>
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