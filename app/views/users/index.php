<?php layout('app'); ?>

<?php section('title'); ?>
Users
<?php endsection(); ?>

<?php section('content'); ?>
<div class="container mt-5 mx-auto">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <div class="row align-items-center">
                            <div class="col text-left">
                                <h4>Users</h4>
                            </div>
                            <div class="col-auto">
                                <a class="btn btn-primary btn-sm" href="users/create">New</a>
                            </div>
                        </div>
                    </div>
                    <table class="table table-sm text-capitalize">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Qr Code</th>
                                <th>Default Password</th>
                                <th scope="col" width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users['data'] as $user): ?>
                                <tr>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $user['name']; ?></td>
                                    <td><?php echo $user['role']; ?></td>
                                    <td>
                                        <img src="<?php echo $user['qr_code']; ?>" alt="QR Code" style="width: 3rem;" />
                                    </td>
                                    <td><?php echo $user['default_password']; ?></td>
                                    <td>
                                        <button type="button" class="btn btn-danger text-white btn-sm" onclick="generateQrCode(<?php echo $user['id']; ?>)">Generate QR</button>
                                        <button type="button" class="btn btn-success text-white btn-sm" onclick="passwordReset(<?php echo $user['id']; ?>)">Password Reset</button>
                                        <a type="button" href="users/<?php echo $user['id']; ?>/logs" class="btn btn-secondary text-white btn-sm mt-1">View Logs</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <?php if ($users['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <!-- Previous Page Link -->
                                <?php if ($users['previous_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $users['previous_page_url']; ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <?php for ($page = 1; $page <= $users['total_pages']; $page++): ?>
                                    <li class="page-item <?php echo ($page == $users['current_page']) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next Page Link -->
                                <?php if ($users['next_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $users['next_page_url']; ?>" aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">&raquo;</span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endsection(); ?>

<?php section('scripts'); ?>

<script>
    function generateQrCode(id) {
        fetch('/generate-qr-code/' + id, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.href = '/users';
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function passwordReset(id) {
        fetch('/password-reset/' + id, {
                method: 'GET'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            });
    }
</script>

<?php endsection(); ?>