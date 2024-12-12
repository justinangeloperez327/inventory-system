<?php layout('app'); ?>

<?php section('title'); ?>
Renewed Items
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
                                <h4>Renewed Items</h4>
                            </div>
                            <div class="col-auto">
                                <label for="borrowed-date" class="form-label">Borrowed Date</label>
                                <input type="date" class="form-control form-control-sm" id="borrowed-date" placeholder="Date" value="<?php echo $borrowedDate; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm text-sm text-capitalize">
                            <thead>
                                <tr>

                                    <th scope="col">Item</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Borrowed By</th>
                                    <th scope="col">Borrowed Deadline</th>
                                    <?php if (admin()): ?>
                                        <th scope="col" width="10%">Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach ($renewedItems['data'] as $ri): ?>
                                    <tr>

                                        <td><?php echo ($ri['item_name']); ?></td>
                                        <td><?php echo ($ri['category_name']); ?></td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            if ($ri['status'] === 'pending') {
                                                $statusClass = 'text-bg-warning';
                                            } elseif ($ri['status'] === 'approved') {
                                                $statusClass = 'text-bg-success';
                                            }
                                            ?>
                                            <span class="badge rounded-pill <?php echo $statusClass; ?>"><?php echo ($ri['status']); ?></span>
                                        </td>
                                        <td>
                                            <?php if ($ri['user_name']): ?>
                                                <?php echo ($ri['user_name']); ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($ri['borrowed_deadline']): ?>
                                                <?php echo ($ri['borrowed_deadline']); ?>
                                            <?php endif ?>
                                        </td>
                                        <?php if (admin()): ?>
                                            <td>
                                                <div>
                                                    <button
                                                        type="button"
                                                        class="btn btn-success btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editRenewedItemModal"
                                                        data-id="<?php echo $ri['id']; ?>"
                                                        data-item-id="<?php echo $ri['item_id']; ?>"
                                                        data-status="<?php echo ($ri['status']); ?>"
                                                        data-borrowed-deadline="<?php echo ($ri['borrowed_deadline']); ?>">
                                                        Edit
                                                    </button>
                                                </div>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <?php if ($renewedItems['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <!-- Previous Page Link -->
                                <?php if ($renewedItems['previous_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $renewedItems['previous_page_url']; ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <?php for ($page = 1; $page <= $renewedItems['total_pages']; $page++): ?>
                                    <li class="page-item <?php echo ($page == $renewedItems['current_page']) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next Page Link -->
                                <?php if ($renewedItems['next_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $renewedItems['next_page_url']; ?>" aria-label="Next">
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


<!-- Edit Item Modal -->
<div class="modal fade" id="editRenewedItemModal" tabindex="-1" aria-labelledby="editRenewedItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editRenewedItemModalLabel">Edit Renewed Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editReturnedItemForm">
                    <input type="hidden" id="editRenewedItemId" name="id">
                    <div class="mb-3">
                        <label for="editRenewedItemItemId" class="form-label">Item</label>
                        <select class="form-control" id="editRenewedItemItemId" name="item_id" disabled>
                            <?php foreach ($items as $item): ?>
                                <option value="<?php echo $item['id']; ?>"><?php echo ($item['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editRenewedItemStatus" class="form-label">Status</label>
                        <select class="form-control" id="editRenewedItemStatus" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editRenewedItemBorrowedDeadline" class="form-label">New Deadline</label>
                        <input type="date" class="form-control" id="editRenewedItemBorrowedDeadline" name="borrowed_deadline" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php endsection(); ?>

<?php section('scripts'); ?>
<script>
    document.getElementById('borrowed-date').addEventListener('change', function() {
        const borrowedDate = this.value;
        const url = new URL(window.location.href);
        url.searchParams.set('borrowed_date', borrowedDate);
        window.history.pushState({}, '', url);
        location.reload();
    });
    document.addEventListener('DOMContentLoaded', function() {
        var editRenewedItemModal = document.getElementById('editRenewedItemModal');
        editRenewedItemModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var status = button.getAttribute('data-status');
            var borrowedDeadline = button.getAttribute('data-borrowed-deadline');
            var itemId = button.getAttribute('data-item-id');

            var modal = this;
            modal.querySelector('#editRenewedItemId').value = id;
            modal.querySelector('#editRenewedItemItemId').value = itemId;
            modal.querySelector('#editRenewedItemStatus').value = status;
            modal.querySelector('#editRenewedItemBorrowedDeadline').value = borrowedDeadline;
        });

        var editReturnedItemForm = document.getElementById('editReturnedItemForm');
        editReturnedItemForm.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(editReturnedItemForm);
            console.log(formData);
            var id = document.getElementById('editRenewedItemId').value;
            fetch('/renewed-items/' + id + '/update', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var modal = bootstrap.Modal.getInstance(editRenewedItemModal);
                        modal.hide();
                        setTimeout(() => {
                            alert(data.message);
                            location.reload(); // Reload the page to see the new item
                        }, 200);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });


    });
</script>
<?php endsection(); ?>