<?php layout('app'); ?>

<?php section('title'); ?>
Categories
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
                                <h4>Categories</h4>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">New</button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>

                                    <th scope="col">Name</th>
                                    <th scope="col">Parent</th>
                                    <th scope="col" width="10%">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider text-capitalize">
                                <?php foreach ($categories['data'] as $category): ?>
                                    <tr>

                                        <td><?php echo ($category['name']); ?></td>
                                        <td><?php echo ($category['parent_name']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal" data-id="<?php echo $category['id']; ?>" data-name="<?php echo $category['name']; ?>" data-parent-id="<?php echo $category['parent_id']; ?>">Edit</button>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal" data-id="<?php echo $category['id']; ?>">Delete</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Links -->
                    <?php if ($categories['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <!-- Previous Page Link -->
                                <?php if ($categories['previous_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $categories['previous_page_url']; ?>" aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">&laquo;</span>
                                    </li>
                                <?php endif; ?>

                                <!-- Page Numbers -->
                                <?php for ($page = 1; $page <= $categories['total_pages']; $page++): ?>
                                    <li class="page-item <?php echo ($page == $categories['current_page']) ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                                    </li>
                                <?php endfor; ?>

                                <!-- Next Page Link -->
                                <?php if ($categories['next_page_url']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?php echo $categories['next_page_url']; ?>" aria-label="Next">
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
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addItemForm">
                    <div class="mb-3">
                        <label for="itemName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="itemName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="itemCategory" class="form-label">Parent</label>
                        <select class="form-control" id="itemCategory" name="parent_id">
                            <option value"" selected disabled></option>
                            <?php foreach ($parents as $parent): ?>
                                <option value="<?php echo $parent['id']; ?>"><?php echo ($parent['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Add Item</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editItemForm">
                    <input type="hidden" id="editCategoryId" name="id">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCategoryParentId" class="form-label">Parent</label>
                        <select class="form-control" id="editCategoryParentId" name="parent_id">
                            <?php foreach ($parents as $parent): ?>
                                <option value="<?php echo $parent['id']; ?>"><?php echo ($parent['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Item Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Delete Item</h5>
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
<?php endsection(); ?>

<?php section('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var editCategoryModal = document.getElementById('editCategoryModal');
        editCategoryModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var parentId = button.getAttribute('data-parent-id');

            var modal = this;
            modal.querySelector('#editCategoryId').value = id;
            modal.querySelector('#editCategoryName').value = name;
            modal.querySelector('#editCategoryParentId').value = parentId;
        });

        var deleteCategoryModal = document.getElementById('deleteCategoryModal');
        deleteCategoryModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');

            var modal = this;
            modal.querySelector('#deleteItemId').value = id;
        });

        var addItemForm = document.getElementById('addItemForm');
        addItemForm.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(addItemForm);
            fetch('/categories', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var modal = bootstrap.Modal.getInstance(addCategoryModal);
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
            var id = document.getElementById('editCategoryId').value;
            fetch('/categories/' + id, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var modal = bootstrap.Modal.getInstance(editCategoryModal);
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
            fetch('/categories/' + id + '/delete', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var modal = bootstrap.Modal.getInstance(deleteCategoryModal);
                        modal.hide();
                        setTimeout(() => {
                            alert(data.message);
                            location.reload(); // Reload the page to see the new item
                        }, 200);
                    } else {
                        alert(data.message);
                        var modal = bootstrap.Modal.getInstance(deleteCategoryModal);
                        modal.hide();
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>
<?php endsection(); ?>