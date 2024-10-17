<?php layout('app'); ?>

<?php section('title'); ?>
    Borrowed Items
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
                            <h4>Borrowed Items</h4>
                        </div>
                    </div>
                </div>
                    <table class="table table-sm table-responsive text-sm">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Item</th>
                                <th scope="col">Category</th>
                                <th scope="col">Status</th>
                                <th scope="col">Borrowed By</th>
                                <th scope="col">Borrowed Date</th>
                                <th scope="col">Borrowed Deadline</th>
                                <th scope="col" width="15%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider text-capitalize">
                            <?php foreach ($borrowedItems['data'] as $bi): ?>
                                <tr>
                                    <td scope="row"><?php echo ($bi['id']); ?></td>
                                    <td><?php echo ($bi['item_name']); ?></td>
                                    <td><?php echo ($bi['category_name']); ?></td>
                                    <td>
                                        <?php
                                            $statusClass = '';
                                            if ($bi['status'] === 'pending') {
                                                $statusClass = 'text-bg-warning';
                                            } elseif ($bi['status'] === 'approved') {
                                                $statusClass = 'text-bg-success';
                                            } elseif ($bi['status'] === 'rejected') {
                                                $statusClass = 'text-bg-danger';
                                            }
                                        ?>
                                        <span class="badge rounded-pill <?php echo $statusClass; ?>"><?php echo ($bi['status']); ?></span>
                                    </td>
                                    <td class="">
                                        <?php if ($bi['user_name']): ?>
                                            <?php echo ($bi['user_name']); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($bi['borrowed_date']): ?>
                                            <?php echo ($bi['borrowed_date']); ?>
                                        <?php endif ?>

                                    </td>
                                    <td>
                                        <?php if ($bi['borrowed_deadline']): ?>
                                            <?php echo ($bi['borrowed_deadline']); ?>
                                        <?php endif ?>
                                    </td>
                                    <td>
                                    
                                        <?php if(admin()): ?>
                                            <button 
                                                type="button"
                                                class="btn btn-success btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editBorrowedItemModal"
                                                data-id="<?php echo $bi['id']; ?>"
                                                data-item-id="<?php echo ($bi['item_id']); ?>"
                                                data-item-name="<?php echo ($bi['item_name']); ?>"
                                                data-status="<?php echo ($bi['status']); ?>"
                                                data-borrowed-date="<?php echo ($bi['borrowed_date']); ?>"
                                                data-borrowed-deadline="<?php echo ($bi['borrowed_deadline']); ?>"
                                            >
                                                Edit
                                            </button>
                                        <?php else:?>
                                            <?php if ($bi['status'] == 'pending'): ?>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" 
                                                    data-bs-target="#cancelBorrowedItemModal" data-id="<?php echo $bi['id']; ?>"
                                                >
                                                    Cancel
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if(!admin()): ?>
                                            <?php if ($bi['status'] == 'approved'): ?>
                                                <div class="d-grid gap-2 d-md-block justify-content-end">
                                                    <?php if (!$bi['returned_id']): ?>
                                                        <button type="button" class="btn btn-primary" onclick="handleReturn(<?php echo $bi['id']; ?>)">
                                                            Return
                                                        </button>
                                                    <?php endif; ?>
                                                    <?php if (!$bi['returned_id']): ?>
                                                        <button type="button" class="btn btn-warning" onclick="handleRenew(<?php echo $bi['id']; ?>)">
                                                            Renew
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <?php if ($borrowedItems['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <!-- Previous Page Link -->
                                <?php if ($borrowedItems['previous_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $borrowedItems['previous_page_url']; ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <?php for ($page = 1; $page <= $borrowedItems['total_pages']; $page++): ?>
                                    <li class="page-item <?php echo ($page == $borrowedItems['current_page']) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next Page Link -->
                                <?php if ($borrowedItems['next_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $borrowedItems['next_page_url']; ?>" aria-label="Next">
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
<div class="modal fade" id="editBorrowedItemModal" tabindex="-1" aria-labelledby="editBorrowedItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBorrowedItemModalLabel">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editBorrowedItemForm">
                    <input type="hidden" id="editBorrowedItemId" name="id">
                    <input type="hidden" id="editBorrowedItemItemId" name="item_id">
                    <div class="mb-3">
                        <label for="editBorrowedItemItemName" class="form-label">Item</label>
                        <input class="form-control" type="text" id="editBorrowedItemItemName" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="editBorrowedItemStatus" class="form-label">Status</label>
                        <select class="form-control" id="editBorrowedItemStatus" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    <div class="mb-3" id="deadlineContainer">
                        <label for="editBorrowedItemBorrowedDeadline" class="form-label">Borrowed Deadline</label>
                        <input type="date" class="form-control" id="editBorrowedItemBorrowedDeadline" name="borrowed_deadline" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Item Modal -->
<div class="modal fade" id="cancelBorrowedItemModal" tabindex="-1" aria-labelledby="cancelBorrowedItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelBorrowedItemModalLabel">Cancel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this item?</p>
                <form id="deleteItemForm">
                    <input type="hidden" id="deleteItemId" name="id">
                    <button type="submit" class="btn btn-danger">Yes</button>
                    <button type="button" class="btn btn-secondary" ata-bs-dismiss="modal">No</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endsection(); ?>

<?php section('scripts'); ?>
<script>
    function handleReturn(id) {
        fetch('/returned-items/' + id, {
            method: 'GET'
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                location.href = '/returned-items';
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function handleRenew(id) {
        fetch('/renewed-items/' + id, {
            method: 'GET'
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                location.href = '/renewed-items';
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    document.addEventListener('DOMContentLoaded', function () {
        var statusSelect = document.getElementById('editBorrowedItemStatus');
        var deadlineContainer = document.getElementById('deadlineContainer');

        if (statusSelect.value === 'approved') {
            deadlineContainer.style.display = 'block';
        } else {
            deadlineContainer.style.display = 'none';
        }

        function toggleDeadlineContainer() {
            if (statusSelect.value === 'approved') {
                deadlineContainer.style.display = 'block';
            } else {
                deadlineContainer.style.display = 'none';
            }
        }

        statusSelect.addEventListener('change', toggleDeadlineContainer);
        toggleDeadlineContainer();

        var editBorrowedItemModal = document.getElementById('editBorrowedItemModal');
        editBorrowedItemModal.addEventListener('show.bs.modal', function (event) {

            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var itemId = button.getAttribute('data-item-id');
            var itemName = button.getAttribute('data-item-name');
            var status = button.getAttribute('data-status');
            var borrowedDeadline = button.getAttribute('data-borrowed-deadline');

            var modal = this;
            modal.querySelector('#editBorrowedItemId').value = id;
            modal.querySelector('#editBorrowedItemItemId').value = itemId;
            modal.querySelector('#editBorrowedItemItemName').value = itemName;
            modal.querySelector('#editBorrowedItemStatus').value = status;
            modal.querySelector('#editBorrowedItemBorrowedDeadline').value = borrowedDeadline;
            toggleDeadlineContainer();
        });

        var cancelBorrowedItemModal = document.getElementById('cancelBorrowedItemModal');
        cancelBorrowedItemModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');

            var modal = this;
            modal.querySelector('#deleteItemId').value = id;
        });


        var editBorrowedItemForm = document.getElementById('editBorrowedItemForm');
        editBorrowedItemForm.addEventListener('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(editBorrowedItemForm);
            var id = document.getElementById('editBorrowedItemId').value;
            fetch('/borrowed-items/' + id + '/update', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload the page to see the updated item
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });

        var deleteItemForm = document.getElementById('deleteItemForm');
        deleteItemForm.addEventListener('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(deleteItemForm);
            var id = document.getElementById('deleteItemId').value;
            fetch('/borrowed-items/' + id + '/delete', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload the page to see the updated list
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
</script>
<?php endsection(); ?>