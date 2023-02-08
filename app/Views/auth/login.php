<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>
<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
        <a href="<?= base_url() ?>index2.html" class="h1"><b>Login</b></a>
        </div>
        <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="<?= base_url() ?>" method="post">
            <div class="input-group mb-3">
            <input type="text" class="form-control" name="username" placeholder="Username" required>
            <div class="input-group-append">
                <div class="input-group-text">
                <span class="fas fa-envelope"></span>
                </div>
            </div>
            </div>
            <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <div class="input-group-append">
                <div class="input-group-text">
                <span class="fas fa-lock"></span>
                </div>
            </div>
            </div>
            <div class="social-auth-links text-center mt-2 mb-3">
                <button type="submit" class="btn btn-block btn-primary"> Login
                </button>
            </div>
            <!-- /.social-auth-links -->
        </form>
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->
<?= $this->endSection() ?>