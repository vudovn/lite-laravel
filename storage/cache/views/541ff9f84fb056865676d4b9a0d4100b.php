<?php $__sections = $__sections ?? []; ?><?php $__layout = 'layouts.app'; ?>

<?php $__sections['title'] = 'Welcome to Lite Laravel'; ?>

<?php $__currentSection = 'styles'; ob_start(); ?>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
            --secondary-gradient: linear-gradient(135deg, #10B981 0%, #059669 100%);
            --accent-gradient: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            --dark-gradient: linear-gradient(135deg, #1F2937 0%, #111827 100%);
            --radius: 10px;
            --transition: all 0.3s ease;
        }

        .hero-section {
            position: relative;
            padding: 7rem 0;
            overflow: hidden;
            background-image: var(--dark-gradient);
            border-radius: var(--radius);
            color: white;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -5%;
            right: -5%;
            width: 30rem;
            height: 30rem;
            border-radius: 50%;
            background-image: var(--primary-gradient);
            filter: blur(80px);
            opacity: 0.4;
            z-index: 0;
            animation: pulsate 10s ease-in-out infinite alternate;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -10%;
            left: -10%;
            width: 25rem;
            height: 25rem;
            border-radius: 50%;
            background-image: var(--accent-gradient);
            filter: blur(80px);
            opacity: 0.3;
            z-index: 0;
            animation: pulsate 15s ease-in-out infinite alternate-reverse;
        }

        @keyframes pulsate {
            0% {
                transform: scale(1);
                opacity: 0.3;
            }

            100% {
                transform: scale(1.05);
                opacity: 0.4;
            }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            margin-bottom: 1.5rem;
            background-image: linear-gradient(135deg, #fff 0%, #f3f4f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .hero-btn {
            background-image: var(--primary-gradient);
            border: none;
            padding: 1.2rem 2.5rem;
            font-weight: 600;
            font-size: 1.125rem;
            border-radius: var(--radius);
            transition: var(--transition);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }

        .hero-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.5);
        }

        .feature-card {
            height: 100%;
            border: none;
            border-radius: var(--radius);
            overflow: hidden;
            transition: var(--transition);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 30px -12px rgba(0, 0, 0, 0.2);
        }

        .feature-card-header {
            display: flex;
            align-items: center;
            padding: 1.8rem;
            background-image: var(--dark-gradient);
            color: white;
        }

        .feature-card-icon {
            font-size: 1.5rem;
            margin-right: 1rem;
            width: 3.5rem;
            height: 3.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }

        .feature-card:nth-child(1) .feature-card-icon {
            background-image: var(--primary-gradient);
        }

        .feature-card:nth-child(2) .feature-card-icon {
            background-image: var(--secondary-gradient);
        }

        .feature-card:nth-child(3) .feature-card-icon {
            background-image: var(--accent-gradient);
        }

        .feature-card-body {
            padding: 1.8rem;
            background-color: white;
        }

        .feature-card-title {
            font-size: 1.35rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .tech-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 3.5rem;
            justify-content: center;
        }

        .tech-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.6rem 1.2rem;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.95rem;
            background-color: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            color: white;
            transition: var(--transition);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .tech-badge i {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        .tech-badge:hover {
            background-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }

        .footer-section {
            padding: 4rem 0 2rem;
            margin-top: 6rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .footer-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background-image: linear-gradient(to right, transparent, rgba(79, 70, 229, 0.6), transparent);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.9s ease forwards;
            opacity: 0;
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.25s;
        }

        .delay-3 {
            animation-delay: 0.4s;
        }

        .delay-4 {
            animation-delay: 0.55s;
        }

        .delay-5 {
            animation-delay: 0.7s;
        }

        .metrics-row {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            margin-top: 5rem;
            justify-content: center;
        }

        .metric-card {
            flex: 1;
            min-width: 220px;
            padding: 2rem;
            text-align: center;
            background-color: white;
            border-radius: var(--radius);
            transition: var(--transition);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .metric-card::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 4px;
            bottom: 0;
            left: 0;
            background-image: var(--primary-gradient);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .metric-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 30px -5px rgba(0, 0, 0, 0.15);
        }

        .metric-card:hover::after {
            transform: scaleX(1);
        }

        .metric-value {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.8rem;
            color: #4F46E5;
        }

        .metric-title {
            font-size: 1rem;
            font-weight: 600;
            color: #4B5563;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .cta-section {
            position: relative;
            padding: 5rem 0;
            margin-top: 6rem;
            margin-bottom: 3rem;
            border-radius: var(--radius);
            background-color: #f9fafb;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(135deg, rgba(79, 70, 229, 0.05) 0%, rgba(124, 58, 237, 0.05) 100%);
            z-index: 0;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #1F2937;
        }

        .btn-docs {
            background-image: var(--primary-gradient);
            border: none;
            font-weight: 600;
            padding: 1rem 2rem;
            border-radius: var(--radius);
            transition: var(--transition);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.2);
        }

        .btn-docs:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 20px -3px rgba(79, 70, 229, 0.4);
        }

        .btn-demo {
            background-color: white;
            border: 2px solid #4F46E5;
            color: #4F46E5;
            font-weight: 600;
            padding: 1rem 2rem;
            border-radius: var(--radius);
            transition: var(--transition);
        }

        .btn-demo:hover {
            background-color: #4F46E5;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.2);
        }

        pre.code-example {
            border-radius: 8px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.9rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
    </style>
<?php $__sections[$__currentSection] = ob_get_clean(); ?>

<?php $__currentSection = 'content'; ob_start(); ?>
    <div class="container">
        <!-- Hero Section -->
        <section class="hero-section mb-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-9 mx-auto text-center hero-content">
                        <h1 class="hero-title fade-in">Welcome to Lite Laravel</h1>
                        <p class="hero-subtitle fade-in delay-1">A lightweight, modern PHP framework with elegant
                            Laravel-inspired syntax</p>
                        <a href="https://github.com/vudovn/lite-laravel" target="_blank" class="btn hero-btn fade-in delay-2">
                            <i class="fa-brands fa-github me-2"></i> Get Started
                        </a>

                        <div class="tech-badges">
                            <span class="tech-badge fade-in delay-3"><i class="fa-brands fa-php"></i> PHP 8+</span>
                            <span class="tech-badge fade-in delay-3"><i class="fa-solid fa-code"></i> MVC Pattern</span>
                            <span class="tech-badge fade-in delay-4"><i class="fa-solid fa-bolt"></i> Fast</span>
                            <span class="tech-badge fade-in delay-4"><i class="fa-solid fa-feather"></i> Lightweight</span>
                            <span class="tech-badge fade-in delay-5"><i class="fa-solid fa-shield-halved"></i> Secure</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Metrics Row -->
        <div class="metrics-row">
            <div class="metric-card fade-in delay-2">
                <div class="metric-value">50+</div>
                <div class="metric-title">Helper Functions</div>
            </div>
            <div class="metric-card fade-in delay-3">
                <div class="metric-value">100%</div>
                <div class="metric-title">Open Source</div>
            </div>
            <div class="metric-card fade-in delay-4">
                <div class="metric-value">10x</div>
                <div class="metric-title">Faster Development</div>
            </div>
            <div class="metric-card fade-in delay-5">
                <div class="metric-value">&lt; 1MB</div>
                <div class="metric-title">Framework Size</div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="row mt-5 g-4">
            <div class="col-md-4">
                <div class="feature-card fade-in delay-2">
                    <div class="feature-card-header">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-route"></i>
                        </div>
                        <h3 class="feature-card-title mb-0">Elegant Routing</h3>
                    </div>
                    <div class="feature-card-body">
                        <p class="feature-card-text">Define routes with a clean, expressive syntax similar to Laravel.
                            Support for RESTful resources, parameters, and middleware.</p>
                        <pre class="code-example bg-light p-3 rounded"><code>Route::get('/users/{id}', 'UserController@show');</code></pre>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card fade-in delay-3">
                    <div class="feature-card-header">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-code"></i>
                        </div>
                        <h3 class="feature-card-title mb-0">Blade Templates</h3>
                    </div>
                    <div class="feature-card-body">
                        <p class="feature-card-text">Powerful templating engine with layouts, sections, components, and
                            directives. Clean syntax that's easy to learn and use.</p>
                        <pre class="code-example bg-light p-3 rounded"><code>&#64;foreach ($users as $user)
    &#123;&#123; $user->name &#125;&#125;
&#64;endforeach
</code></pre>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="feature-card fade-in delay-4">
                    <div class="feature-card-header">
                        <div class="feature-card-icon">
                            <i class="fa-solid fa-database"></i>
                        </div>
                        <h3 class="feature-card-title mb-0">Simple ORM</h3>
                    </div>
                    <div class="feature-card-body">
                        <p class="feature-card-text">Intuitive database access with an elegant object-relational mapper.
                            Query building, models, and relationships made simple.</p>
                        <pre class="code-example bg-light p-3 rounded"><code>$users = User::where('active', true)
    ->orderBy('name')
    ->get();</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="cta-section text-center">
            <h2 class="cta-title fade-in">Ready to Build Something Amazing?</h2>
            <a href="/docs" class="btn btn-docs btn-lg me-3 fade-in delay-1">Read Documentation</a>
            <a href="/test-toast" class="btn btn-demo btn-lg fade-in delay-2">View Toasts Demo</a>
        </div>

        <!-- Footer -->
        <div class="footer-section">
            <p class="mb-2">Made with <i class="fa-solid fa-heart text-danger"></i> by the Lite Laravel Team</p>
            <p class="text-muted small">© <?php echo htmlspecialchars(date('Y')); ?> Lite Laravel. All rights reserved.</p>
        </div>
    </div>
<?php $__sections[$__currentSection] = ob_get_clean(); ?>

<?php $__currentSection = 'scripts'; ob_start(); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate elements when they come into view
            const fadeElements = document.querySelectorAll('.fade-in');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });

            fadeElements.forEach(element => {
                element.style.animationPlayState = 'paused';
                observer.observe(element);
            });
        });
    </script>
<?php $__sections[$__currentSection] = ob_get_clean(); ?>
