<?php layout('app'); ?>

<?php section('title'); ?>
Reports
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
                                <h4>Reports</h4>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-success btn-sm" onclick="handleExportToExcel()">Export to Excel</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-sm text-sm text-capitalize">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Item</th>
                                <th scope="col">Category</th>
                                <th scope="col">Borrowed By</th>
                                <th scope="col">Borrowed Date</th>
                                <th scope="col">Returned Date</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php foreach ($borrowedItems as $bi): ?>
                                <tr>
                                    <td scope="row"><?php echo ($bi['id']); ?></td>
                                    <td><?php echo ($bi['item_name']); ?></td>
                                    <td><?php echo ($bi['category_name']); ?></td>
                                    <td><?php echo ($bi['user_name']); ?></td>
                                    <td><?php echo ($bi['borrowed_date']); ?></td>
                                    <td><?php echo ($bi['returned_date']); ?></td>
                                    <td><?php echo $bi['status']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
        fetch('/reports/export-to-excel', {
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
            a.download = 'report.xlsx';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        })
        .catch(error => console.error('There was a problem with the fetch operation:', error));
    }
</script>
<?php endsection(); ?>