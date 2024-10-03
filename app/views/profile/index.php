<?php layout('app'); ?>

<?php section('title'); ?>
    Profile
<?php endsection(); ?>

<?php section('content'); ?>
<div class="container mt-5">
    <div class="row">
        <div class="col-8">
            <?php if ($flashMessage = \core\Redirect::getFlash()): ?>
                    <div class="alert alert-warning">
                        <?php echo ($flashMessage); ?>
                    </div>
                <?php endif; ?>
            <div class="card">
                <div class="card-header">
                    <h3>QR CODE</h3>
                </div>

                <div class="card-body text-center">
                    <img src="<?php echo $user['qr_code'] ?>" alt="QR Code">
                </div>
            </div>

            <div class="card mt-5">
                <div class="card-header">
                    <h3>Profile</h3>
                </div>

                <div class="card-body">
                    <form action="/profile/<?php echo $user['id'] ?>/update-name" method="POST">
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name'] ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                    </form>
                </div>
            </div>

            <div class="card my-5">
                <div class="card-header">
                    <h3>Password</h3>
                </div>
                <div class="card-body">
                    <form action="/profile/<?php echo $user['id'] ?>/update-password" method="POST">
                        <div class="form-group mb-3">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="confirm_current_password">Confirm Current Password</label>
                            <input type="password" class="form-control" id="confirm_current_password" name="confirm_current_password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="confirm_new_password">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                        </div>
                        <button type="submit" class="btn btn-success">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endsection(); ?>
