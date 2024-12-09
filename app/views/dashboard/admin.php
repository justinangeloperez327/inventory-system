<?php layout('app'); ?>

<?php section('title'); ?>
Admin Dashboad
<?php endsection(); ?>

<?php section('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
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
                    <p>Welcome to the Admin Dashboard. Here you can perform the following actions:</p>
                    <ul>
                        <li>View all items</li>
                        <li>Issue borrowed items</li>
                        <li>Issue returned items</li>
                        <li>Issue renewed items</li>
                        <li>Add new users</li>
                        <li>Manage users</li>
                        <li>Check attendance of users</li>
                        <li>Generate QR code for attendance</li>
                        <li>View reports</li>
                    </ul>
                    <p>Use the navigation menu to access different sections.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endsection(); ?>