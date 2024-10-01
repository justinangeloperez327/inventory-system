<?php layout('app'); ?>

<?php section('title'); ?>
    Create User
<?php endsection(); ?>

<?php section('content'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Create User</h3>
                </div>
                <?php if ($flashMessage = \core\Redirect::getFlash()): ?>
                    <div class="alert alert-warning">
                        <?php echo ($flashMessage); ?>
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <form action="/users/store" method="POST">
                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="role">Role</label>
                            <select name="role" id="" class="form-control">
                                <option value="faculty">Faculty</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endsection(); ?>
