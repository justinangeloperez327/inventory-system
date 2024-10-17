<?php layout('app'); ?>

<?php section('title'); ?>
    Attendance
<?php endsection(); ?>

<?php section('content'); ?>
<div class="container mx-auto">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                
                <div class="card-body">
                <div class="card-title">
                        <div class="row align-items-center">
                            <div class="col text-left">
                                <h4>Attendance</h4>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-success btn-sm" onclick="handleExportToExcel()">Export to Excel</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-sm table-responsive text-sm">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">User</th>
                                <th scope="col">Date</th>
                                <th scope="col">Time In</th>
                                <th scope="col">Time Out</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider text-capitalize">
                            <?php foreach ($attendance['data'] as $a): ?>
                                <tr>
                                    <td scope="row"><?php echo ($a['id']); ?></td>
                                    <td><?php echo ($a['user_name']); ?></td>
                                    <td><?php echo $a['date']; ?></td>
                                    <td><?php echo $a['time_in']; ?></td>
                                    <td><?php echo $a['time_out']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <?php if ($attendance['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <!-- Previous Page Link -->
                                <?php if ($attendance['previous_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $attendance['previous_page_url']; ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <?php for ($page = 1; $page <= $attendance['total_pages']; $page++): ?>
                                    <li class="page-item <?php echo ($page == $attendance['current_page']) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next Page Link -->
                                <?php if ($attendance['next_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $attendance['next_page_url']; ?>" aria-label="Next">
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
    function handleExportToExcel()
    {
        fetch('/attendance/export-to-excel', {
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
            a.download = 'attendance.xlsx';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => console.error('There was a problem with the fetch operation:', error));
    }
</script>
<?php endsection(); ?>