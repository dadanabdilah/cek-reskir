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
            <h3 class="card-title">
              <a class="btn btn-primary btn-sm" href="<?= base_url('admin/resi/new') ?>">Tambah</a>
              <a class="btn btn-warning btn-sm" onclick="cekExpired()">Eliminasi Resi Clear</a>
              <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-import">
                Import data
              </button>
            </h3>
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
                      <a class="btn btn-info btn-sm mr-2"
                        href="<?= site_url('admin/resi/' . $value->resi_id) ?>">Detail</a>
                      <a class="btn btn-warning btn-sm mr-2"
                        href="<?= site_url('admin/resi/' . $value->resi_id) ?>/edit">Edit</a>
                      <a class="btn btn-danger btn-sm mr-2"
                        href="<?= site_url('admin/resi/delete/' . $value->resi_id) ?>">Hapus</a>
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
<div class="modal fade" id="modal-import">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="<?=site_url()?>admin/resi/import" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="POST" />
        <div class="modal-header">
          <h4 class="modal-title">Import Data</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Untuk template unduh <a href="<?=base_url()?>/assets/template_import_resi.xlsx" target="_BLANK">disini</a>.</p>
          <p><input type="file" name="berkas" id="berkas" class="form-control"></p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>

  </div>
</div>
<script>
  function cekExpired() {
    // alert(4);
    $.get('<?=base_url()?>/sistem/cekExpired', '', function (data) {
      alert(data);
    });
  }
</script>

<?= $this->endSection() ?>