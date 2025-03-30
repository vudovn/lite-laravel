<?php
/**
 * Contact page view
 */
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="display-4 fw-bold">Contact Us</h1>
            <p class="lead">We'd love to hear from you</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="h4 card-title">Send us a message</h2>
                    <p class="card-text text-muted mb-4">Fill out the form and our team will get back to you within 24
                        hours.</p>

                    <form action="/contact" method="post">
                        <?= csrf() ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="h4 card-title">Get in touch</h2>
                    <p class="card-text text-muted mb-4">You can also reach us through these channels:</p>

                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-primary fa-2x"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="h6">Our Location</h5>
                            <p class="mb-0">123 Web Dev Street, Tech City, 12345</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <i class="fas fa-envelope text-primary fa-2x"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="h6">Email Us</h5>
                            <p class="mb-0"><a href="mailto:<?= $email ?? 'contact@vutoichoi.com' ?>"
                                    class="text-decoration-none"><?= $email ?? 'contact@vutoichoi.com' ?></a></p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <i class="fas fa-phone text-primary fa-2x"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="h6">Call Us</h5>
                            <p class="mb-0"><a href="tel:+1234567890" class="text-decoration-none">+1 (234) 567-890</a>
                            </p>
                        </div>
                    </div>

                    <h5 class="h6 mt-4 mb-3">Follow Us</h5>
                    <div class="d-flex">
                        <a href="#" class="me-3 text-decoration-none">
                            <i class="fab fa-facebook fa-2x text-primary"></i>
                        </a>
                        <a href="#" class="me-3 text-decoration-none">
                            <i class="fab fa-twitter fa-2x text-primary"></i>
                        </a>
                        <a href="#" class="me-3 text-decoration-none">
                            <i class="fab fa-instagram fa-2x text-primary"></i>
                        </a>
                        <a href="#" class="text-decoration-none">
                            <i class="fab fa-linkedin fa-2x text-primary"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>