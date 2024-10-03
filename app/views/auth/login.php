<?php layout('app'); ?>

<?php section('title'); ?>
    Login
<?php endsection(); ?>

<?php section('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
                <?php if ($flashMessage = \core\Redirect::getFlash()): ?>
                    <div class="alert alert-warning">
                        <?php echo ($flashMessage); ?>
                    </div>
                <?php endif; ?>
            <div class="card">
                <div class="card-body">
                    <div class="card-title text-center">
                        <h3>Login</h3>
                    </div>
                    <form method="POST" action="/login">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control form-control-sm" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                        </div>
                        <div class="">
                            <button type="submit" class="btn btn-primary btn-sm">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endsection(); ?>

