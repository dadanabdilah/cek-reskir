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
            <form method="POST" action="<?= site_url('ongkir') ?>">
                <div class="card-body">
                    <?php if (session('error') !== null) : ?>
                        <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
                    <?php endif ?>

                    <?php if (session('message') !== null) : ?>
                        <div class="alert alert-success" role="alert"><?= session('message') ?></div>
                    <?php endif ?>

                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label>Provinsi Asal</label>
                                <select id="province_origin" name="province_origin" class="form-control">
                                    <option selected disabled>Pilih...</option>
                                    <?php foreach ($Provinsi->rajaongkir->results as $key => $value) { ?>
                                        <option value="<?= $value->province_id ?>"><?= $value->province ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kota Asal</label>
                                <select id="city_origin" name="city_origin" class="form-control">
                                    <option selected disabled>Pilih...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kecamatan Asal</label>
                                <select id="subdis_origin" name="subdis_origin" class="form-control">
                                    <option selected disabled>Pilih...</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label>Provinsi Tujuan</label>
                                <select id="province_destination" name="province_destination" class="form-control">
                                    <option selected disabled>Pilih...</option>
                                    <?php foreach ($Provinsi->rajaongkir->results as $key => $value) { ?>
                                        <option value="<?= $value->province_id ?>"><?= $value->province ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kota Tujuan</label>
                                <select id="city_destination" name="city_destination" class="form-control">
                                    <option selected disabled>Pilih...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kecamatan Tujuan</label>
                                <select id="subdis_destination" name="subdis_destination" class="form-control">
                                    <option selected disabled>Pilih...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Produk</label>
                                <select id="kode_barang" name="kode_barang" class="form-control">
                                    <option selected disabled>Pilih...</option>
                                    <?php foreach ($Produk as $key => $value) { ?>
                                        <option berat="<?= $value->berat ?>" value="<?= $value->berat ?>"><?= $value->nama_barang ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="weight" class="form-label">Berat (g)</label>
                                <input type="number" id="weight" name="weight" class="form-control" min="1">
                            </div>
                            <div class="form-group">
                                <label>Kurir</label>
                                <select id="courier" name="courier" class="form-control">
                                    <option selected disabled>Pilih...</option>
                                    <?php foreach (cek_expedisi() as $key => $value) { ?>
                                    <option value="<?= $key ?>"><?= $value ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="result" class="mt-3">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Layanan</th>
                                    <th>Estimasi</th>
                                    <th>Biaya</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody id="result_cost">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" id="btn_cek" class="btn btn-primary">Cek</button>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
$(document).ready(function () {

    $('select[name="kode_barang"]').on('change', function () {
        const berat = $("#kode_barang").val()
        $("#weight").val(berat)
    });

    $('select[name="province_origin"]').on('change', function () {
        let provinceId = $(this).val();

        if (provinceId) {
            $.ajax({
                url: '<?= base_url() ?>/ongkir/city/' + provinceId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('select[name="city_origin"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="city_origin"]').append('<option value="'+ value.city_id +'">'+ value.city_name +'</option>');
                    });
                }
            });
        } else {
            $('select[name="city_origin"]').empty();
        }
    });

    $('select[name="province_destination"]').on('change', function () {
        let provinceId = $(this).val();

        if (provinceId) {
            $.ajax({
                url: '<?= base_url() ?>/ongkir/city/' + provinceId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('select[name="city_destination"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="city_destination"]').append('<option value="'+ value.city_id +'"> '+ value.city_name +'</option>');
                    });
                }
            });
        } else {
            $('select[name="city_destination"]').empty();
        }
    });


    $('select[name="city_origin"]').on('change', function () {
        let cityId = $(this).val();

        if (cityId) {
            $.ajax({
                url: '<?= base_url() ?>/ongkir/subdis/' + cityId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('select[name="subdis_origin"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="subdis_origin"]').append('<option value="'+ value.subdistrict_id +'">'+ value.subdistrict_name +'</option>');
                    });
                }
            });
        } else {
            $('select[name=subdis_origin"]').empty();
        }
    });

    $('select[name="city_destination"]').on('change', function () {
        let cityId = $(this).val();

        if (cityId) {
            $.ajax({
                url: '<?= base_url() ?>/ongkir/subdis/' + cityId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('select[name="subdis_destination"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="subdis_destination"]').append('<option value="'+ value.subdistrict_id +'"> '+ value.subdistrict_name +'</option>');
                    });
                }
            });
        } else {
            $('select[name="subdis_destination"]').empty();
        }
    });


    $('#btn_cek').on('click', function () {
        let originId = $('#subdis_origin').val();
        let desId = $('#subdis_destination').val();
        let weight = $('#weight').val();
        let courier = $('#courier').val();

        if (originId && desId && weight && courier) {
            $.ajax({
                url: '<?= base_url() ?>/ongkir/cek/' + originId + "/" + desId + "/"+ weight + "/" + courier ,
                type: 'GET',
                dataType: 'html',
                success: function (data) {
                    $('#result_cost').html(data)
                    console.log(data);
                }
            });
        } else {
            alert("Semua data harus diisi.")
        }
    });
});
</script>
<?= $this->endSection() ?>