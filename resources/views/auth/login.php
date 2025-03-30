<?php
/**
 * Login page view
 */
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="card-title mb-0">Login to Your Account</h4>
                </div>
                <div class="card-body p-4">
                    <form action="<?= url('login') ?>" method="POST">
                        <?= csrf() ?>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control <?= isset($_SESSION['_errors']['email']) ? 'is-invalid' : '' ?>" 
                                   id="email" name="email" value="<?= old('email', '') ?>">
                            <?php if (isset($_SESSION['_errors']['email'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($_SESSION['_errors']['email']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control <?= isset($_SESSION['_errors']['password']) ? 'is-invalid' : '' ?>" 
                                   id="password" name="password">
                            <?php if (isset($_SESSION['_errors']['password'])): ?>
                                <div class="invalid-feedback"><?= htmlspecialchars($_SESSION['_errors']['password']) ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                        
                        <div class="text-center">
                            <p class="mb-0">Don't have an account? <a href="<?= url('register') ?>" class="text-decoration-none">Register</a></p>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <a href="/" class="text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

<?php
// Clear session errors and old input after displaying
unset($_SESSION['_errors']);
unset($_SESSION['_old_input']);
?>
