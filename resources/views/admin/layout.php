<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> - <?= config('app.name') ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">

    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Custom CSS -->
    <?php if (isset($styles)): ?>
        <?= $styles ?>
    <?php endif; ?>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark text-white">
            <div class="sidebar-header p-3">
                <h3><?= config('app.name') ?></h3>
            </div>

            <ul class="list-unstyled components px-3">
                <li class="<?= isset($active) && $active === 'dashboard' ? 'active' : '' ?>">
                    <a href="<?= url('admin/dashboard') ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                </li>
                <li class="<?= isset($active) && $active === 'users' ? 'active' : '' ?>">
                    <a href="<?= url('admin/users') ?>"><i class="fas fa-users me-2"></i> Users</a>
                </li>
                <li class="<?= isset($active) && $active === 'settings' ? 'active' : '' ?>">
                    <a href="<?= url('admin/settings') ?>"><i class="fas fa-cog me-2"></i> Settings</a>
                </li>
                <li>
                    <a href="<?= url('logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content" class="w-100">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-dark">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="ms-auto d-flex">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?= session('user_name') ?? 'Admin' ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="<?= url('admin/profile') ?>">Profile</a></li>
                                <li><a class="dropdown-item" href="<?= url('admin/settings') ?>">Settings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="<?= url('logout') ?>">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid py-4">
                <?php if (isset($content)): ?>
                    <?= $content ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastify JS -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Admin JS -->
    <script src="<?= asset('js/admin.js') ?>"></script>

    <!-- Custom JS -->
    <?php if (isset($scripts)): ?>
        <?= $scripts ?>
    <?php endif; ?>

    <script>
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
</body>

</html>

