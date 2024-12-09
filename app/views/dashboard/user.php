<?php layout('app'); ?>

<?php section('title'); ?>
User Dashboard
<?php endsection(); ?>

<?php section('content'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-body">
                    You're logged in!
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-2">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Instructions</h5>
                    <p>Welcome to the User Dashboard. Here you can perform the following actions:</p>
                    <ul>
                        <li>View all items</li>
                        <li>Borrow items</li>
                        <li>Return items</li>
                        <li>Renew items</li>
                    </ul>
                    <p>Use the navigation menu to access different sections.</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php endsection(); ?>