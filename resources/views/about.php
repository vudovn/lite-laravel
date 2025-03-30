<?php
/**
 * About page view
 */
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4 fw-bold">About Us</h1>
            <p class="lead">Learn more about our mission and vision</p>
        </div>
    </div>

    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h2>Our Story</h2>
            <p>Vu Toi Choi was founded with a simple mission: to provide a powerful yet lightweight application
                framework that helps developers build amazing web applications quickly and efficiently.</p>
            <p>We believe in clean code, modern practices, and providing a solid foundation that helps you focus on
                building your unique features rather than reinventing the wheel.</p>
        </div>
        <div class="col-md-6">
            <img src="<?= asset('images/about.svg') ?>" class="img-fluid rounded shadow-sm" alt="About us illustration">
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body p-5">
                    <h2 class="card-title">Our Mission</h2>
                    <p class="card-text">To empower developers with tools that make web development more enjoyable,
                        efficient, and accessible. We strive to create a framework that balances power and simplicity,
                        providing enough features to speed up development without adding unnecessary complexity.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 text-center">
            <h2>Meet Our Team</h2>
            <p class="text-muted mb-5">The people behind Vu Toi Choi</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <img src="<?= asset('images/team-1.jpg') ?>" class="rounded-circle mb-3" width="150" height="150"
                        alt="Team member">
                    <h3 class="card-title h5">John Doe</h3>
                    <p class="card-subtitle text-muted mb-3">Founder & Lead Developer</p>
                    <p class="card-text">With over 10 years of experience, John leads our development team with a focus
                        on code quality and innovation.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <img src="<?= asset('images/team-2.jpg') ?>" class="rounded-circle mb-3" width="150" height="150"
                        alt="Team member">
                    <h3 class="card-title h5">Jane Smith</h3>
                    <p class="card-subtitle text-muted mb-3">UX/UI Designer</p>
                    <p class="card-text">Jane ensures our framework delivers exceptional user experiences, with a focus
                        on intuitive interfaces and modern design.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <img src="<?= asset('images/team-3.jpg') ?>" class="rounded-circle mb-3" width="150" height="150"
                        alt="Team member">
                    <h3 class="card-title h5">Michael Johnson</h3>
                    <p class="card-subtitle text-muted mb-3">Backend Architect</p>
                    <p class="card-text">Michael specializes in building robust and scalable backend systems that power
                        our framework's performance.</p>
                </div>
            </div>
        </div>
    </div>
</div>