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

                    <div class="row">
                        <div class="col-lg-5">
                            <div class="mb-3">
                                <label for="province_origin" class="form-label">Provinsi Asal</label>
                                <select class="form-select @error('province_origin') is-invalid @enderror"
                                    name="province_origin" id="province_origin">
                                    <option selected disabled>--Provinsi--</option>
                                    @foreach ($provinces as $province => $value)
                                        <option value="{{ $province }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('province_origin')
                                    <div id="province_origin" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="city_origin" class="form-label">Kota Asal</label>
                                <select class="form-select @error('city_origin') is-invalid @enderror"
                                    name="city_origin" id="city_origin">
                                    <option selected disabled>--Kota--</option>
                                </select>
                                @error('city_origin')
                                    <div id="city_origin" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="mb-3">
                                <label for="province_destination" class="form-label">Provinsi Tujuan</label>
                                <select class="form-select @error('province_destination') is-invalid @enderror"
                                    name="province_destination" id="province_destination">
                                    <option selected disabled>--Provinsi--</option>
                                    @foreach ($provinces as $province => $value)
                                        <option value="{{ $province }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('province_destination')
                                    <div id="province_destination" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="city_destination" class="form-label">Kota Tujuan</label>
                                <select class="form-select @error('city_destination') is-invalid @enderror"
                                    name="city_destination" id="city_destination">
                                    <option selected disabled>--Kota--</option>
                                </select>
                                @error('city_destination')
                                    <div id="city_destination" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="courier" class="form-label">Kurir</label>
                                <select class="form-select @error('courier') is-invalid @enderror"
                                    name="courier" id="courier">
                                    <option selected disabled>--Kurir--</option>
                                    @foreach ($couriers as $courier => $value)
                                        <option value="{{ $courier }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('courier')
                                    <div id="courier" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="weight" class="form-label">Berat (g)</label>
                                <input type="number" name="weight"
                                    class="form-control @error('weight') is-invalid @enderror" id="weight"
                                    min="1">
                                @error('weight')
                                    <div id="weight" class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
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

<?= $this->section('js') ?>
<script>
$(document).ready(function () {
    $('select[name="province_origin"]').on('change', function () {
        let provinceId = $(this).val();

        if (provinceId) {
            $.ajax({
                url: '/province/' + provinceId + '/cities',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('select[name="city_origin"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="city_origin"]').append('<option value="'+ key +'">'+ value +'</option>');
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
                url: '/province/' + provinceId + '/cities',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('select[name="city_destination"]').empty();
                    $.each(data, function (key, value) {
                        $('select[name="city_destination"]').append('<option value="'+ key +'"> '+ value +'</option>');
                    });
                }
            });
        } else {
            $('select[name="city_destination"]').empty();
        }
    });
});
</script>
<?= $this->endSection() ?>