<?php
/**
 * Dashboard main view
 * 
 * This is the main dashboard view that shows:
 * - User welcome message
 * - Quick stats
 * - Quick actions
 */

// You can set a layout to use if needed
// extend('layouts.app');
?>

<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h3 text-primary">Welcome, <?= $user->name ?? 'User' ?></h1>
                    <p class="text-muted">
                        Your account is
                        <?= ($user->status ?? 'active') === 'active' ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-warning">Pending</span>' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Profile Completion</h5>
                    <div class="progress mt-3">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 85%;" aria-valuenow="85"
                            aria-valuemin="0" aria-valuemax="100">85%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Recent Activity</h5>
                    <p class="card-text">Last login: <?= date('M d, Y H:i', strtotime('-2 hours')) ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title">Account Status</h5>
                    <p class="card-text">Your account is in good standing</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="/dashboard/profile" class="btn btn-outline-primary d-block">
                                <i class="fas fa-user-edit me-2"></i> Edit Profile
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="/dashboard/settings" class="btn btn-outline-secondary d-block">
                                <i class="fas fa-cog me-2"></i> Account Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>