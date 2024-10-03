<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
                <?php renderSection('title'); ?>
        </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="/css/app.css">
        <?php renderSection('styles') ?? '';?>
</head>
    <body>
        <nav class="navbar navbar-expand-lg">
            <div class="container mx-auto">
                <a class="navbar-brand" href="#">Inventory System</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                    <?php if (authenticated()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/items">Items</a>
                        </li>
                        <?php if (admin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/categories">Categories</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/borrowed-items">Borrowed Items</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/returned-items">Returned Items</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/renewed-items">Renewed Items</a>
                        </li>
                        <?php if (admin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/users ">Users</a>
                            </li>
                        <?php endif; ?>
                        <?php if (admin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/reports ">Reports</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <?php if (authenticated()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo userName(); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="/profile">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout">Logout</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
        <main class="mt-5">
            <?php renderSection('content'); ?>
        </main>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <?php renderSection('scripts') ?? ''; ?>
</body>
</html>