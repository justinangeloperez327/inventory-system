<?php layout('app'); ?>

<?php section('title'); ?>
    Returned Items
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
                            <h4>Returned Items</h4>
                        </div>
                    </div>
                </div>
                    <table class="table table-sm text-sm text-capitalize">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Item</th>
                                <th scope="col">Category</th>
                                <th scope="col">Status</th>
                                <th scope="col">Returned By</th>
                                <th scope="col">Returned Date</th>
                                <?php if(admin()): ?>
                                    <th scope="col" width="10%">Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php foreach ($returnedItems['data'] as $ri): ?>
                                <tr>
                                    <td scope="row"><?php echo ($ri['id']); ?></td>
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
                                        <?php if ($ri['returned_date']): ?>
                                            <?php echo ($ri['returned_date']); ?>
                                        <?php endif ?>

                                    </td>
                                    <?php if(admin()): ?>
                                        <td>
                                            <div>
                                                <button 
                                                    type="button"
                                                    class="btn btn-success btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editReturnedItemModal"
                                                    data-id="<?php echo $ri['id']; ?>"
                                                    data-item-id="<?php echo $ri['item_id']; ?>"
                                                    data-borrowed-item-id="<?php echo ($ri['borrowed_item_id']); ?>"
                                                    data-item-name="<?php echo ($ri['item_name']); ?>"
                                                    data-status="<?php echo ($ri['status']); ?>"
                                                    data-returned-date="<?php echo ($ri['returned_date']); ?>"
                                                >
                                                    Edit
                                                </button>
                                            </div>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Pagination Links -->
                    <?php if ($returnedItems['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <!-- Previous Page Link -->
                                <?php if ($returnedItems['previous_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $returnedItems['previous_page_url']; ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <?php for ($page = 1; $page <= $returnedItems['total_pages']; $page++): ?>
                                    <li class="page-item <?php echo ($page == $returnedItems['current_page']) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next Page Link -->
                                <?php if ($returnedItems['next_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $returnedItems['next_page_url']; ?>" aria-label="Next">
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
<div class="modal fade" id="editReturnedItemModal" tabindex="-1" aria-labelledby="editReturnedItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReturnedItemModalLabel">Edit Returned Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editReturnedItemForm">
                    <input type="hidden" id="editReturnedItemId" name="id">
                    <input type="hidden" id="editReturnedItemItemId" name="borrowed_item_id">
                    <div class="mb-3">
                        <label for="editReturnedItemItemName" class="form-label">Item</label>
                        <input type="text" class="form-control" id="editReturnedItemItemName" name="item_name" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="editReturnedItemStatus" class="form-label">Status</label>
                        <select class="form-control" id="editReturnedItemStatus" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editReturnedItemReturnedDate" class="form-label">Returned Date</label>
                        <input type="date" class="form-control" id="editReturnedItemReturnedDate" name="returned_date" required>
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
    document.addEventListener('DOMContentLoaded', function () {
        var editReturnedItemModal = document.getElementById('editReturnedItemModal');

        editReturnedItemModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var status = button.getAttribute('data-status');
            var returnedDate = button.getAttribute('data-returned-date');
            var itemId = button.getAttribute('data-item-id');
            var itemName = button.getAttribute('data-item-name');
            var modal = this;
            modal.querySelector('#editReturnedItemId').value = id;
            modal.querySelector('#editReturnedItemItemId').value = itemId;
            modal.querySelector('#editReturnedItemStatus').value = status;
            modal.querySelector('#editReturnedItemReturnedDate').value = returnedDate;
            modal.querySelector('#editReturnedItemItemName').value = itemName;
        });

        var editReturnedItemForm = document.getElementById('editReturnedItemForm');
        
        editReturnedItemForm.addEventListener('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(editReturnedItemForm);
            console.log(formData);
            var id = document.getElementById('editReturnedItemId').value;
            fetch('/returned-items/' + id + '/update', {
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


    });
</script>
<?php endsection(); ?>