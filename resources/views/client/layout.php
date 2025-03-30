<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Welcome' ?> - <?= config('app.name') ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Client CSS -->
    <link rel="stylesheet" href="<?= asset('css/client.css') ?>">

    <!-- Toastify CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Custom CSS -->
    <?php if (isset($styles)): ?>
        <?= $styles ?>
    <?php endif; ?>
</head>

<body>
    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="<?= url('/') ?>">
                    <?= config('app.name') ?>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link <?= isset($active) && $active === 'home' ? 'active' : '' ?>"
                                href="<?= url('/') ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isset($active) && $active === 'about' ? 'active' : '' ?>"
                                href="<?= url('about') ?>">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isset($active) && $active === 'services' ? 'active' : '' ?>"
                                href="<?= url('services') ?>">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= isset($active) && $active === 'contact' ? 'active' : '' ?>"
                                href="<?= url('contact') ?>">Contact</a>
                        </li>
                    </ul>

                    <div class="d-flex">
                        <?php if (session('user_id')): ?>
                            <div class="dropdown">
                                <a class="btn btn-outline-primary dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-user-circle me-1"></i> <?= session('user_name') ?? 'User' ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="<?= url('dashboard') ?>">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?= url('profile') ?>">Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="<?= url('logout') ?>">Logout</a></li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="<?= url('login') ?>" class="btn btn-outline-primary me-2">Login</a>
                            <a href="<?= url('register') ?>" class="btn btn-primary">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            <?php if (isset($content)): ?>
                <?= $content ?>
            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><?= config('app.name') ?></h5>
                    <p>Your trusted partner for quality services and products.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= url('/') ?>" class="text-white">Home</a></li>
                        <li><a href="<?= url('about') ?>" class="text-white">About Us</a></li>
                        <li><a href="<?= url('services') ?>" class="text-white">Services</a></li>
                        <li><a href="<?= url('contact') ?>" class="text-white">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <address>
                        <p><i class="fas fa-map-marker-alt me-2"></i> 123 Main Street, City, Country</p>
                        <p><i class="fas fa-phone me-2"></i> +1 234 567 890</p>
                        <p><i class="fas fa-envelope me-2"></i> info@<?= strtolower(config('app.name')) ?>.com</p>
                    </address>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; <?= date('Y') ?> <?= config('app.name') ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Toastify JS -->
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Client JS -->
    <script src="<?= asset('js/client.js') ?>"></script>

    <!-- Custom JS -->
    <?php if (isset($scripts)): ?>
        <?= $scripts ?>
    <?php endif; ?>
</body>

</html>