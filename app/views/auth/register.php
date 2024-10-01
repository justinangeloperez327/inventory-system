<?php layout('app'); ?>

<?php section('title'); ?>
    Register
<?php endsection(); ?>

<?php section('content'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if (flash()): ?>
                <div class="alert alert-warning">
                    <?php echo (flash()); ?>
                </div>
            <?php endif; ?>
            <div class="card">

                <div class="card-body">
                <div class="card-title text-center">
                    <h3>Register</h3>
                </div>
                    <form method="POST" action="/register">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password:</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="">
                            <button type="submit" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endsection(); ?>
