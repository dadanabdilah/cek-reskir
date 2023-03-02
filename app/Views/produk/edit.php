<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><?= $sub_title ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <a class="btn btn-primary btn-sm" style="float: right" href="<?= site_url('admin/produk') ?>">Kembali</a>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- general form elements -->
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="dataProduk-tab" data-toggle="pill"
                                href="#dataProduk" role="tab" aria-controls="dataProduk"
                                aria-selected="true">Data Produk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="variasiHarga-tab" data-toggle="pill"
                                href="#variasiHarga" role="tab" aria-controls="variasiHarga"
                                aria-selected="false">Variasi Harga</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="custom-tabs-four-tabContent">
                        <div class="tab-pane fade show active" id="dataProduk" role="tabpanel"
                            aria-labelledby="dataProduk-tab">
                            <form method="POST" action="<?= site_url('admin/produk/' . $Produk->id ) ?>">
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
                                        <input type="text" class="form-control" value="<?= $Produk->nama_barang ?>" id="nama_barang"
                                            name="nama_barang">
                                    </div>
                                    <div class="form-group">
                                        <label>Kelompok Produk</label>
                                        <input type="text" class="form-control" value="<?= $Produk->kelompok_barang ?>" id="kelompok_barang"
                                            name="kelompok_barang" autocomplte="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Berat Produk (Gram)</label>
                                        <input type="number" class="form-control" value="<?= $Produk->berat ?>" id="berat" name="berat"
                                            autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Harga Produk</label>
                                        <input type="number" class="form-control" value="<?= $Produk->harga ?>" id="harga" name="harga">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="variasiHarga" role="tabpanel"
                            aria-labelledby="variasiHarga-tab">
                            
                            <form method="POST" action="<?= site_url('admin/produk/' . $Produk->id ) ?>">
                                <?= csrf_field() ?>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="35%">Variasi</th>
                                            <th width="25%">Harga</th>
                                            <th width="15%">.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $no = 1;
                                            foreach ($variasi as $key) {
                                        ?>
                                        <tr>
                                            <td width="5%"><?=$no++?></td>
                                            <td width="35%">
                                                <input type="hidden" name="produk_variasi_id" id="produk_variasi_id<?=$key->produk_variasi_id?>" class="form-control" value="<?=$key->produk_variasi_id?>">
                                                <input type="text" name="nama_variasi" id="nama_variasi<?=$key->produk_variasi_id?>" class="form-control" value="<?=$key->nama_variasi?>">
                                            </td>
                                            <td width="25%">
                                                <input type="text" name="harga" id="harga<?=$key->produk_variasi_id?>" class="form-control" value="<?=$key->harga?>">
                                            </td>
                                            <td width="15%" class="text-center">
                                                <a name="" id="" class="btn btn-primary btn-sm" onclick="btnSimpan('<?=$key->produk_variasi_id?>')" role="button"><i class="fa fa-edit"> Simpan</i></a>
                                                <a name="" id="" class="btn btn-danger btn-sm" href="<?=base_url()?>/admin/variasi/delete/<?=$key->produk_variasi_id?>" role="button"><i class="fa fa-trash"> Delete</i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <?php
                                            for ($i = 0; $i < 10; $i++){
                                        ?>
                                        <tr>
                                            <td width="5%"><?=$no++?></td>
                                            <td width="35%">
                                                <input type="text" name="nama_variasi" id="nama_variasinew<?=$i?>" class="form-control">
                                                <input type="hidden" name="produk_variasi_id" id="produk_variasi_id<?=$i?>" class="form-control" value="--">
                                            </td>
                                            <td width="25%">
                                                <input type="text" name="harga" id="harganew<?=$i?>" class="form-control">
                                            </td>
                                            <td width="15%" class="text-center">
                                                <a name="" id="" class="btn btn-primary btn-sm"  onclick="btnSimpan('new<?=$i?>')" role="button"><i class="fa fa-edit"> Simpan</i></a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </table>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<script>
    function btnSimpan(variasi_id = ''){
        produk_variasi_id    = $('#produk_variasi_id'+variasi_id).val();
        nama_variasi    = $('#nama_variasi'+variasi_id).val();
        harga           = $('#harga'+variasi_id).val();
        
        $.post('<?=base_url()?>/admin/variasi/create', 'nama_variasi='+nama_variasi+'&harga='+harga+'&kode_barang=<?=$Produk->kode_barang?>&produk_variasi_id='+produk_variasi_id, function(data){
            alert(data);
        });
    }
</script>
<?= $this->endSection() ?>