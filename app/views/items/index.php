<?php layout('app'); ?>

<?php section('title'); ?>
Items
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
                                <h4>Items</h4>
                            </div>
                            <div class="col-auto">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="search" placeholder="Search" value="<?php echo $search; ?>">
                                    <script>
                                        document.getElementById('search').addEventListener('input', function() {
                                            const regex = /[^a-zA-Z0-9 ]/g;
                                            this.value = this.value.replace(regex, '');
                                        });
                                    </script>
                                    <div class="ms-2">
                                        <button type="button" class="btn btn-primary btn-sm" id="search-btn">Search</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addItemModal">New</button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm text-capitalize">
                            <thead>
                                <tr>

                                    <th scope="col">Name</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">
                                        <?php if (admin()): ?>
                                            Quantity
                                        <?php else: ?>
                                            Availability
                                        <?php endif; ?>
                                    </th>
                                    <th scope="col" width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                                <?php foreach ($items['data'] as $item): ?>
                                    <tr>

                                        <td><?php echo ($item['name']); ?></td>
                                        <td><?php echo ($item['category_name']); ?></td>
                                        <td>
                                            <?php if (admin()): ?>
                                                <?php echo ($item['quantity']); ?>
                                            <?php else: ?>
                                                <?php if ($item['quantity'] > 0): ?>
                                                    <span class="badge bg-success">Available</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Not Available</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right">
                                            <?php if (admin()): ?>
                                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editItemModal"
                                                    data-id="<?php echo $item['id']; ?>"
                                                    data-name="<?php echo ($item['name']); ?>"
                                                    data-category-id="<?php echo ($item['category_id']); ?>"
                                                    data-quantity="<?php echo $item['quantity']; ?>">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteItemModal" data-id="<?php echo $item['id']; ?>">Delete</button>

                                            <?php else: ?>
                                                <?php if ($item['quantity'] > 0): ?>
                                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#borrowItemModal" data-id="<?php echo $item['id']; ?>">Borrow</button>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Links -->
                    <?php if ($items['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <!-- Previous Page Link -->
                                <?php if ($items['previous_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $items['previous_page_url']; ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <?php for ($page = 1; $page <= $items['total_pages']; $page++): ?>
                                    <li class="page-item <?php echo ($page == $items['current_page']) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next Page Link -->
                                <?php if ($items['next_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $items['next_page_url']; ?>" aria-label="Next">
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

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">New Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addItemForm">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="itemName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemCategory" class="form-label">Category</label>
                        <select class="form-control" id="editItemCategoryId" name="category_id" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo ($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="itemQuantity">Quantity</label>
                        <input type="number" class="form-control" id="itemQuantity" name="quantity" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Add Item</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editItemForm">
                    <input type="hidden" id="editItemId" name="id">
                    <div class="mb-3">
                        <label for="editItemName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editItemName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editItemCategoryId" class="form-label">Category</label>
                        <select class="form-control" id="editItemCategoryId" name="category_id" required>

                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo ($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editItemQuantity">Quantity</label>
                        <input type="number" class="form-control" id="editItemQuantity" name="quantity" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Item Modal -->
<div class="modal fade" id="deleteItemModal" tabindex="-1" aria-labelledby="deleteItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteItemModalLabel">Delete Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this item?</p>
                <form id="deleteItemForm">
                    <input type="hidden" id="deleteItemId" name="id">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Borrow Item Modal -->
<div class="modal fade" id="borrowItemModal" tabindex="-1" aria-labelledby="borrowItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="borrowItemModalLabel">Borrow Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to borrow this item?</p>
                <form id="borrowItemForm">
                    <input type="hidden" id="borrowItemId" name="id">
                    <button type="submit" class="btn btn-success">Borrow</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php endsection(); ?>

<?php section('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('search-btn').addEventListener('click', function() {
            const search = document.getElementById('search').value;
            window.location.href = `/items?search=${search}`;
        });

        var editItemModal = document.getElementById('editItemModal');

        editItemModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var categoryId = button.getAttribute('data-category-id');
            var quantity = button.getAttribute('data-quantity');
            var modal = this;

            modal.querySelector('#editItemId').value = id;
            modal.querySelector('#editItemName').value = name;
            modal.querySelector('#editItemCategoryId').value = categoryId;
            modal.querySelector('#editItemQuantity').value = quantity;
        });

        var deleteItemModal = document.getElementById('deleteItemModal');

        deleteItemModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');

            var modal = this;
            modal.querySelector('#deleteItemId').value = id;
        });

        var borrowItemModal = document.getElementById('borrowItemModal');

        borrowItemModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');

            var modal = this;
            modal.querySelector('#borrowItemId').value = id;
        });

        var addItemForm = document.getElementById('addItemForm');

        addItemForm.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(addItemForm);
            fetch('/items', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        //  close modal
                        var modal = bootstrap.Modal.getInstance(addItemModal);
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

        var editItemForm = document.getElementById('editItemForm');

        editItemForm.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(editItemForm);
            var id = document.getElementById('editItemId').value;
            fetch('/items/' + id, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var modal = bootstrap.Modal.getInstance(editItemModal);
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

        var deleteItemForm = document.getElementById('deleteItemForm');

        deleteItemForm.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(deleteItemForm);
            var id = document.getElementById('deleteItemId').value;
            fetch('/items/' + id + '/delete', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var modal = bootstrap.Modal.getInstance(deleteItemModal);
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

        var borrowItemForm = document.getElementById('borrowItemForm');

        borrowItemForm.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(borrowItemForm);
            var id = document.getElementById('borrowItemId').value;
            fetch('/borrowed-items', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.href = '/borrowed-items'; // Redirect to borrowed items page
                    } else {
                        alert(data.message);
                        var modal = bootstrap.Modal.getInstance(borrowItemModal);
                        modal.hide();
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>
<?php endsection(); ?>