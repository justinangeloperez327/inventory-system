<?php layout('app'); ?>

<?php section('title'); ?>
Archive Items
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
                                <h4>Archive Items</h4>
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
                                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#restoreItemModal" data-id="<?php echo $item['id']; ?>">Restore</button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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


<!-- Restore Item Modal -->
<div class="modal fade" id="restoreItemModal" tabindex="-1" aria-labelledby="restoreItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreItemModalLabel">Restore Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to restore this item?</p>
                <form id="restoreItemForm">
                    <input type="hidden" id="restoreItemId" name="id">
                    <button type="submit" class="btn btn-warning">Restore</button>
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
            window.location.href = `/items/archive?search=${search}`;
        });

        var restoreItemModal = document.getElementById('restoreItemModal');

        restoreItemModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');

            var modal = this;
            modal.querySelector('#restoreItemId').value = id;
        });

        var restoreItemForm = document.getElementById('restoreItemForm');

        restoreItemForm.addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(restoreItemForm);
            var id = document.getElementById('restoreItemId').value;
            fetch('/items/' + id + '/restore', {
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