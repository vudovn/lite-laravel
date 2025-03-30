<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 animate-on-scroll">
                <h1>Welcome to <?= config('app.name') ?></h1>
                <p>Your trusted partner for quality services and products. We provide innovative solutions to meet your
                    needs.</p>
                <div class="d-flex gap-3">
                    <a href="<?= url('services') ?>" class="btn btn-primary">Our Services</a>
                    <a href="<?= url('contact') ?>" class="btn btn-outline-primary">Contact Us</a>
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0 animate-on-scroll">
                <img src="<?= asset('img/hero-image.jpg') ?>" alt="Hero Image" class="img-fluid rounded shadow"
                    onerror="this.src='https://via.placeholder.com/600x400?text=Welcome'">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>Our Features</h2>
            <p class="text-muted">Discover what makes us different</p>
        </div>

        <div class="row">
            <div class="col-md-4 animate-on-scroll">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Secure & Reliable</h4>
                    <p>We prioritize security and reliability in everything we do, ensuring your peace of mind.</p>
                </div>
            </div>

            <div class="col-md-4 animate-on-scroll">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Fast & Efficient</h4>
                    <p>Our streamlined processes ensure quick delivery without compromising on quality.</p>
                </div>
            </div>

            <div class="col-md-4 animate-on-scroll">
                <div class="feature-box">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h4>24/7 Support</h4>
                    <p>Our dedicated team is always available to address your concerns and provide assistance.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products/Services Section -->
<section class="py-5 bg-light-gray">
    <div class="container">
        <div class="section-title">
            <h2>Our Services</h2>
            <p class="text-muted">Explore our range of services designed for your needs</p>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 animate-on-scroll">
                <div class="card">
                    <img src="<?= asset('img/service1.jpg') ?>" class="card-img-top" alt="Service 1"
                        onerror="this.src='https://via.placeholder.com/350x200?text=Service+1'">
                    <div class="card-body">
                        <h5 class="card-title">Web Development</h5>
                        <p class="card-text">Custom web solutions tailored to your specific requirements, built with the
                            latest technologies.</p>
                        <a href="<?= url('services/web-development') ?>" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 animate-on-scroll">
                <div class="card">
                    <img src="<?= asset('img/service2.jpg') ?>" class="card-img-top" alt="Service 2"
                        onerror="this.src='https://via.placeholder.com/350x200?text=Service+2'">
                    <div class="card-body">
                        <h5 class="card-title">Mobile Apps</h5>
                        <p class="card-text">Native and cross-platform mobile applications that deliver exceptional user
                            experiences.</p>
                        <a href="<?= url('services/mobile-apps') ?>" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 animate-on-scroll">
                <div class="card">
                    <img src="<?= asset('img/service3.jpg') ?>" class="card-img-top" alt="Service 3"
                        onerror="this.src='https://via.placeholder.com/350x200?text=Service+3'">
                    <div class="card-body">
                        <h5 class="card-title">Digital Marketing</h5>
                        <p class="card-text">Comprehensive digital marketing strategies to grow your online presence and
                            reach.</p>
                        <a href="<?= url('services/digital-marketing') ?>" class="btn btn-primary">Learn More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="<?= url('services') ?>" class="btn btn-outline-primary">View All Services</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5">
    <div class="container">
        <div class="section-title">
            <h2>What Our Clients Say</h2>
            <p class="text-muted">Trusted by businesses worldwide</p>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 animate-on-scroll">
                <div class="testimonial">
                    <div class="testimonial-content">
                        "Working with this team was a game-changer for our business. They delivered beyond our
                        expectations and were responsive throughout the project."
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Client">
                        <div>
                            <h5 class="testimonial-author mb-0">John Smith</h5>
                            <p class="text-muted mb-0">CEO, TechCorp</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 animate-on-scroll">
                <div class="testimonial">
                    <div class="testimonial-content">
                        "The attention to detail and professionalism displayed by the team was impressive. Our project
                        was completed on time and within budget."
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Client">
                        <div>
                            <h5 class="testimonial-author mb-0">Sarah Johnson</h5>
                            <p class="text-muted mb-0">Marketing Director, BrandCo</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 animate-on-scroll">
                <div class="testimonial">
                    <div class="testimonial-content">
                        "We've seen a significant increase in our online presence since partnering with this company.
                        Their strategies have driven real business results."
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <img src="https://via.placeholder.com/50" class="rounded-circle me-3" alt="Client">
                        <div>
                            <h5 class="testimonial-author mb-0">Robert Davis</h5>
                            <p class="text-muted mb-0">Founder, StartupX</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container text-center animate-on-scroll">
        <h2 class="mb-4">Ready to Get Started?</h2>
        <p class="lead mb-4">Contact us today for a free consultation and quote.</p>
        <a href="<?= url('contact') ?>" class="btn btn-light btn-lg">Contact Us Now</a>
    </div>
</section>

<!-- Back to Top Button -->
<a href="#" class="back-to-top">
    <i class="fas fa-arrow-up"></i>
</a>

<?php
// Set active menu item
$active = 'home';

// Return the layout data
return [
    'title' => 'Welcome',
    'content' => ob_get_clean(),
    'active' => $active,
    'styles' => '<style>
        .back-to-top {
            position: fixed;
            bottom: 25px;
            right: 25px;
            display: none;
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 40px;
            background: #007bff;
            color: #fff;
            border-radius: 50%;
            z-index: 9999;
        }
        
        .back-to-top.show {
            display: block;
        }
        
        .back-to-top:hover {
            background: #0056b3;
            color: #fff;
        }
    </style>'
];
?>