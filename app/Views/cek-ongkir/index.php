<?= $this->extend('layouts/home') ?>
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container">
        <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0"><?= $sub_title ?></h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= site_url('ongkir') ?>">Cek Ongkir</a></li>
            </ol>
        </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container">
        <!-- general form elements -->
        <div class="card card">
            <div class="card-header">
                <h3 class="card-title"></h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form method="POST" action="<?= site_url('admin/ongkir') ?>">
                <div class="card-body">
                    <?php if (session('error') !== null) : ?>
                        <div class="alert alert-danger" role="alert"><?= session('error') ?></div>
                    <?php endif ?>

                    <?php if (session('message') !== null) : ?>
                        <div class="alert alert-success" role="alert"><?= session('message') ?></div>
                    <?php endif ?>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Provinsi Tujuan</label>
                                        <select id="province_destination" name="province_destination" class="form-control select2"  style="width: 100%;">
                                            <option selected disabled>Pilih...</option>
                                            <?php foreach ($Provinsi->rajaongkir->results as $key => $value) { ?>
                                                <option value="<?= $value->province_id ?>"><?= $value->province ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Kota Tujuan</label>
                                        <select id="city_destination" name="city_destination" class="form-control select2"  style="width: 100%;">
                                            <option selected disabled>Pilih...</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Kecamatan Tujuan</label>
                                        <select id="subdis_destination" name="subdis_destination" class="form-control select2"  style="width: 100%;">
                                            <option selected disabled>Pilih...</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Produk</label>
                                        <select id="kode_barang" name="kode_barang" class="form-control select2"  style="width: 100%;">
                                            <option selected disabled>Pilih...</option>
                                            <?php foreach ($Produk as $key => $value) { ?>
                                                <option data-berat="<?= $value->berat ?>" value="<?= $value->kode_barang ?>"><?= $value->nama_barang ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="weight" class="form-label">Quantity</label>
                                        <input type="number" id="quantity" name="quantity" class="form-control" min="1">
                                    </div>
                                    <div class="mb-3">
                                        <label for="weight" class="form-label">Berat (g)</label>
                                        <input type="number" id="weight" name="weight" class="form-control" min="1">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kurir</label>
                                        <select id="courier" name="courier" class="form-control">
                                            <option selected disabled>Pilih...</option>
                                            <?php foreach (cek_expedisi() as $key => $value) { ?>
                                            <option value="<?= $key ?>"><?= $value ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                    <button type="button" id="btn_cek" class="btn btn-primary btn-block mb-3">Cek</button>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-md-4">
                            <div id="temp_copy"></div>
                            <div id="result_text"></div>
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
    $('.select2').select2()

    $('select[name="kode_barang"]').on('change', function () {
        const barang = $('#kode_barang');
        $('#weight').val(barang.find(':selected').data('berat'));
        $("#quantity").val(1)
    });

    $('#quantity').on('change', function () {
        const oldQty = $(this).val()
        const weight = $("#weight").val()
        $("#weight").val(oldQty * weight)
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
        let originId = "2964";
        let desId = $('#subdis_destination').val();
        let weight = $('#weight').val();
        let kode_barang = $('#kode_barang').val();
        let courier = $('#courier').val();
        let quantity = $('#quantity').val();

        if (originId && desId && weight && courier) {
            $.ajax({
                url: '<?= base_url() ?>/ongkir/cek/' + originId + "/" + desId + "/"+ weight + "/" + courier + "/" + quantity + "/" + kode_barang,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    if(data == ""){
                        $('#result_cost').html("Kurir tidak mendukung!")
                    } else {
                        $('#result_cost').html(data.result)
                        $.each(data, function(i, item) {
                            $('#result_text').html(data.copy_text)
                            console.log(data[i]);
                        });
                    }
                }
            });
        } else {
            alert("Semua data harus diisi.")
        }
    });

    function copyToClipboard(element) {
        var $temp = $("#temp_copy");
        $("body").append($temp);
        $temp.val($(element).val()).select();
        document.execCommand("copy");
        $temp.remove();
    }

   
});
</script>
<?= $this->endSection() ?>