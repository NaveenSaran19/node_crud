<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($businessShortName ?? 'Selvi Tex') ?> — Admin Console</title>
    
    <?php
        $loginFavicon = base_url('assets/images/logo.png');
        if (!empty($businessLogo) && file_exists(FCPATH . $businessLogo)) {
            $loginFavicon = base_url($businessLogo);
        }
        $settingsModel = new \App\Models\SettingsModel();
        $favSetting = $settingsModel->where('key', 'favicon')->first();
        if ($favSetting && !empty($favSetting['value']) && file_exists(FCPATH . $favSetting['value'])) {
            $loginFavicon = base_url($favSetting['value']);
        }
    ?>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= $loginFavicon ?>?v=<?= time() ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $loginFavicon ?>?v=<?= time() ?>">
    <link rel="apple-touch-icon" href="<?= $loginFavicon ?>?v=<?= time() ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Admin Login Stylesheet -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin-login.css') ?>?v=<?= time() ?>">
</head>
<body>

    <!-- Ambient Lighting Glows -->
    <div class="ambient-glow ambient-glow-1"></div>
    <div class="ambient-glow ambient-glow-2"></div>
    <div class="bg-overlay-pattern"></div>

    <div class="login-wrapper">
        <div class="login-split-card">
            
            <!-- =========================================================
                 LEFT SIDE: Business Name + Logo + Brand Content
                 ========================================================= -->
            <div class="login-brand-panel">
                <div class="brand-panel-decor"></div>
                <div class="brand-panel-decor-bottom"></div>

                <div class="brand-panel-top">
                    <!-- Business Logo + Name -->
                    <div class="brand-logo-container">
                        <?php if (!empty($businessLogo) && file_exists(FCPATH . $businessLogo)): ?>
                            <img src="<?= base_url($businessLogo) ?>" alt="<?= esc($businessShortName ?? 'Selvi Tex') ?> Logo" class="brand-logo-img">
                        <?php else: ?>
                            <div class="brand-logo-fallback">
                                <i class="fa-solid fa-crown"></i>
                            </div>
                        <?php endif; ?>

                        <div class="brand-name-group">
                            <h1 class="brand-main-title"><?= esc($businessShortName ?? 'Selvi Tex') ?></h1>
                            <span class="brand-tagline-badge">Handloom Silk Traditions</span>
                        </div>
                    </div>

                    <!-- Content / Statement -->
                    <div class="brand-content-body">
                        <div class="brand-statement">
                            Heritage Pure Silk Sarees & <span>Festive Luxury</span>
                        </div>
                        <p class="brand-lead-desc">
                            Welcome to the dedicated administrative console. Manage your store catalog, promotional banners, categories, and customer orders seamlessly.
                        </p>

                        <!-- Highlights List -->
                        <div class="brand-features-list">
                            <div class="brand-feature-item">
                                <div class="brand-feature-icon">
                                    <i class="fa-solid fa-gem"></i>
                                </div>
                                <span class="brand-feature-text">Authentic Kanchipuram & Wedding Silk Collections</span>
                            </div>
                            <div class="brand-feature-item">
                                <div class="brand-feature-icon">
                                    <i class="fa-solid fa-photo-film"></i>
                                </div>
                                <span class="brand-feature-text">Dynamic Hero Slideshows & Festival Banners</span>
                            </div>
                            <div class="brand-feature-item">
                                <div class="brand-feature-icon">
                                    <i class="fa-solid fa-cubes"></i>
                                </div>
                                <span class="brand-feature-text">Real-Time Catalog & Category Navigation Control</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Left Panel Footer -->
                <div class="brand-panel-footer">
                    <span><?= esc($businessName ?? 'Selvi Tex') ?></span>
                    <span class="brand-footer-pill">
                        <i class="fa-solid fa-shield-halved"></i> Storefront Console
                    </span>
                </div>
            </div>

            <!-- =========================================================
                 RIGHT SIDE: Modern Login Form
                 ========================================================= -->
            <div class="login-form-panel">
                
                <div class="form-panel-header">
                    <h2 class="form-welcome-title">Welcome Back</h2>
                    <p class="form-welcome-subtitle">Please enter your administrator credentials to access your console.</p>
                </div>

                <!-- Flash Error Message -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="login-alert alert-error" role="alert">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span><?= esc(session()->getFlashdata('error')) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Flash Success Message (e.g. You have been logged out securely.) -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="login-alert alert-success" role="alert">
                        <i class="fa-solid fa-circle-check"></i>
                        <span><?= esc(session()->getFlashdata('success')) ?></span>
                    </div>
                <?php endif; ?>

                <!-- Authentication Form -->
                <form action="<?= base_url('admin/authenticate') ?>" method="POST" autocomplete="off" id="adminLoginForm">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label class="form-label" for="adminEmail">
                            <span>Admin Email Address</span>
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-envelope input-icon-prefix"></i>
                            <input 
                                type="email" 
                                id="adminEmail" 
                                name="email" 
                                class="form-control" 
                                placeholder="Enter admin email address" 
                                value="<?= esc(old('email', '')) ?>" 
                                required 
                                autofocus 
                                autocomplete="off"
                            >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="adminPassword">
                            <span>Console Password</span>
                        </label>
                        <div class="input-icon-wrapper">
                            <i class="fa-solid fa-lock input-icon-prefix"></i>
                            <input 
                                type="password" 
                                id="adminPassword" 
                                name="password" 
                                class="form-control" 
                                placeholder="••••••••••••" 
                                value=""
                                required 
                                autocomplete="new-password"
                            >
                            <button type="button" class="input-eye-toggle" id="toggleLoginPasswordBtn" title="Toggle password visibility" aria-label="Toggle password visibility">
                                <i class="fa-regular fa-eye" id="loginEyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options-row">
                        <label class="remember-checkbox-label">
                            <input type="checkbox" name="remember" value="1" checked>
                            <span>Stay authenticated</span>
                        </label>
                        <a href="javascript:void(0)" onclick="alert('To update your admin password, please sign in to the console or contact your database administrator.')" class="forgot-hint-btn">Forgot password?</a>
                    </div>

                    <button type="submit" class="login-btn" id="loginSubmitBtn">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i>
                        <span>Enter Console</span>
                    </button>

                    <div class="footer-links-wrap">
                        <a href="<?= base_url('/') ?>" class="return-store-link">
                            <i class="fa-solid fa-store"></i>
                            <span>Return to Storefront</span>
                        </a>
                    </div>
                </form>

            </div>

        </div>
    </div>

    <!-- Toggle Password Visibility Script & Submit State -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleBtn = document.getElementById('toggleLoginPasswordBtn');
            var passwordInput = document.getElementById('adminPassword');
            var eyeIcon = document.getElementById('loginEyeIcon');
            var loginForm = document.getElementById('adminLoginForm');
            var submitBtn = document.getElementById('loginSubmitBtn');

            if (toggleBtn && passwordInput && eyeIcon) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.add('fa-eye');
                    }
                    passwordInput.focus();
                });
            }

            if (loginForm && submitBtn) {
                loginForm.addEventListener('submit', function() {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>Authenticating...</span>';
                });
            }
        });
    </script>
</body>
</html>
