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
                            <h4 class="text-capitalize"><?php echo $user['username'] ?> Logs</h4>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-success btn-sm" onclick="handleExportToExcel(<?php echo $user['id']; ?>)">Export to Excel</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-sm text-capitalize">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendances['data'] as $attendance): ?>
                                <tr>
                                    <td><?php echo $attendance['id']; ?></td>
                                    <td><?php echo $attendance['date']; ?></td>
                                    <td><?php echo $attendance['time_in']; ?></td>
                                    <td><?php echo $attendance['time_out']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <?php if ($attendances['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <!-- Previous Page Link -->
                                <?php if ($attendances['previous_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $attendances['previous_page_url']; ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <?php for ($page = 1; $page <= $attendances['total_pages']; $page++): ?>
                                    <li class="page-item <?php echo ($page == $attendances['current_page']) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next Page Link -->
                                <?php if ($attendances['next_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $attendances['next_page_url']; ?>" aria-label="Next">
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
    function handleExportToExcel(id)
    {
        fetch(`/users/${id}/export-to-excel`, {
            method: 'GET'
        }).then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Network response was not ok.');
        })
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'users_'+id+'.xlsx';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => console.error('There was a problem with the fetch operation:', error));
    }
</script>
<?php endsection(); ?>