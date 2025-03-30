<div class="row">
    <div class="col-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <p class="text-muted">Welcome back, <?= session('user_name') ?? 'Admin' ?>!</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card primary">
            <div class="row">
                <div class="col-4">
                    <i class="fas fa-users"></i>
                </div>
                <div class="col-8 text-end">
                    <h5>Users</h5>
                    <h3 class="counter">1,250</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card success">
            <div class="row">
                <div class="col-4">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="col-8 text-end">
                    <h5>Orders</h5>
                    <h3 class="counter">152</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card info">
            <div class="row">
                <div class="col-4">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="col-8 text-end">
                    <h5>Revenue</h5>
                    <h3>$<span class="counter">8,457</span></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stats-card warning">
            <div class="row">
                <div class="col-4">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="col-8 text-end">
                    <h5>Tasks</h5>
                    <h3 class="counter">24</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-xl-8 mb-4">
        <div class="card">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Sales Overview</h6>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        This Year
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">Last Quarter</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="salesChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 mb-4">
        <div class="card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Recent Activities</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <div>
                            <i class="fas fa-user-plus text-primary me-2"></i>
                            New user registered
                            <div class="text-muted small">John Doe</div>
                        </div>
                        <small class="text-muted">Just now</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <div>
                            <i class="fas fa-shopping-cart text-success me-2"></i>
                            New order placed
                            <div class="text-muted small">Order #12345</div>
                        </div>
                        <small class="text-muted">2 hours ago</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <div>
                            <i class="fas fa-comment text-info me-2"></i>
                            New comment
                            <div class="text-muted small">On Product #42</div>
                        </div>
                        <small class="text-muted">3 hours ago</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <div>
                            <i class="fas fa-server text-warning me-2"></i>
                            Server rebooted
                            <div class="text-muted small">Server #2 restarted</div>
                        </div>
                        <small class="text-muted">5 hours ago</small>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                        <div>
                            <i class="fas fa-credit-card text-danger me-2"></i>
                            Payment failed
                            <div class="text-muted small">Invoice #987</div>
                        </div>
                        <small class="text-muted">Yesterday</small>
                    </li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="#" class="btn btn-sm btn-primary">View All Activities</a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links Row -->
<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-3">
                        <a href="<?= url('admin/users/create') ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-user-plus me-2"></i> Add User
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="<?= url('admin/products/create') ?>" class="btn btn-success btn-block">
                            <i class="fas fa-plus me-2"></i> Add Product
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="<?= url('admin/orders') ?>" class="btn btn-info btn-block">
                            <i class="fas fa-list me-2"></i> View Orders
                        </a>
                    </div>
                    <div class="col-6 mb-3">
                        <a href="<?= url('admin/settings') ?>" class="btn btn-secondary btn-block">
                            <i class="fas fa-cog me-2"></i> Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">System Status</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label d-flex justify-content-between">
                        <span>Server Load</span>
                        <span>25%</span>
                    </label>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label d-flex justify-content-between">
                        <span>Database Usage</span>
                        <span>67%</span>
                    </label>
                    <div class="progress">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 67%" aria-valuenow="67"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label d-flex justify-content-between">
                        <span>Storage Space</span>
                        <span>82%</span>
                    </label>
                    <div class="progress">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 82%" aria-valuenow="82"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label d-flex justify-content-between">
                        <span>Memory Usage</span>
                        <span>45%</span>
                    </label>
                    <div class="progress">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 45%" aria-valuenow="45"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <a href="#" class="btn btn-sm btn-primary">View Details</a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold">Recent Messages</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="User">
                            <div>
                                <h6 class="mb-0">John Doe</h6>
                                <small class="text-muted">john@example.com</small>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">Hello, I need some help with my recent order...</p>
                        <small class="text-muted">30 min ago</small>
                    </li>
                    <li class="list-group-item p-3">
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://via.placeholder.com/40" class="rounded-circle me-2" alt="User">
                            <div>
                                <h6 class="mb-0">Jane Smith</h6>
                                <small class="text-muted">jane@example.com</small>
                            </div>
                        </div>
                        <p class="mb-0 text-muted">When will my order be shipped? I've been waiting...</p>
                        <small class="text-muted">2 hours ago</small>
                    </li>
                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="#" class="btn btn-sm btn-primary">View All Messages</a>
            </div>
        </div>
    </div>
</div>

<!-- External JavaScript for Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
// Set active menu item
$active = 'dashboard';

// Return the layout data
return [
    'title' => 'Dashboard',
    'content' => ob_get_clean(),
    'active' => $active,
    'scripts' => ''
];
?>