<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="admin-profile-page-wrapper">
    <!-- Breadcrumb & Header -->
    <div class="admin-profile-header">
        <div class="header-left">
            <h1 class="page-main-title">
                <i class="fa-solid fa-user-shield title-icon"></i>
                Admin Profile &amp; Credentials
            </h1>
            <p class="page-subtitle">Manage administrator credentials, contact email, and account password security.</p>
        </div>
        <div class="header-right">
            <a href="<?= base_url('admin/dashboard') ?>" class="btn-back-dashboard">
                <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Flash Alert Notifications -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="profile-page-alert alert-success" role="alert">
            <i class="fa-solid fa-circle-check alert-icon"></i>
            <div>
                <strong>Success!</strong> <?= session()->getFlashdata('success') ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="profile-page-alert alert-error" role="alert">
            <i class="fa-solid fa-circle-exclamation alert-icon"></i>
            <div>
                <strong>Notice:</strong> <?= session()->getFlashdata('error') ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Dynamic AJAX Alert Container -->
    <div id="pageAjaxAlert" class="profile-page-alert" style="display: none;" role="alert">
        <i class="fa-solid fa-circle-info alert-icon"></i>
        <div class="alert-content"></div>
    </div>

    <!-- Main Grid Content -->
    <div class="profile-page-grid">
        <!-- Left Sidebar: Profile Summary Card -->
        <div class="profile-sidebar-col">
            <div class="profile-summary-card">
                <div class="card-avatar-wrap">
                    <div class="profile-avatar-large" id="profilePageAvatar">
                        <?= esc(strtoupper(substr($adminDisplayName, 0, 2))) ?>
                    </div>
                    <span class="status-indicator-dot" title="Active Session"></span>
                </div>
                
                <h2 class="profile-summary-name" id="profilePageName"><?= esc($adminDisplayName) ?></h2>
                <div class="profile-role-tag">
                    <i class="fa-solid fa-shield-halved"></i> Super Administrator
                </div>
                <div class="profile-console-tag">
                    <?= esc($businessShortName) ?> Console
                </div>

                <div class="profile-summary-meta">
                    <div class="meta-item">
                        <i class="fa-solid fa-envelope"></i>
                        <span id="profilePageEmail"><?= esc($adminDisplayEmail) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fa-solid fa-lock"></i>
                        <span>Bcrypt Password Encryption</span>
                    </div>
                    <div class="meta-item">
                        <i class="fa-solid fa-clock"></i>
                        <span>Active Admin Session</span>
                    </div>
                </div>

                <div class="security-checklist-box">
                    <div class="checklist-title">
                        <i class="fa-solid fa-shield-check"></i> Security Status
                    </div>
                    <ul class="checklist-items">
                        <li><i class="fa-solid fa-check"></i> Primary Admin Account</li>
                        <li><i class="fa-solid fa-check"></i> Root Console Privileges</li>
                        <li><i class="fa-solid fa-check"></i> Granular CSRF Protected</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Main Column: Profile & Password Form -->
        <div class="profile-form-col">
            <form id="adminProfileMainForm" action="<?= base_url('admin/profile/update') ?>" method="POST" autocomplete="off">
                <?= csrf_field() ?>

                <!-- Section 1: Profile Information -->
                <div class="profile-section-card">
                    <div class="section-card-header">
                        <div class="header-icon-box">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Profile Information</h3>
                            <p class="section-desc">Update your administrator display name and contact email address.</p>
                        </div>
                    </div>

                    <div class="section-card-body">
                        <div class="form-row-2">
                            <div class="profile-form-group">
                                <label for="input_admin_name">Display Name <span class="req-star">*</span></label>
                                <div class="profile-input-wrapper">
                                    <i class="fa-solid fa-user field-icon"></i>
                                    <input type="text" id="input_admin_name" name="admin_name" class="profile-form-control" value="<?= esc($adminDisplayName) ?>" placeholder="e.g. Administrator" required>
                                </div>
                                <span class="field-hint">Displayed in the header bar and top navigation.</span>
                            </div>

                            <div class="profile-form-group">
                                <label for="input_admin_email">Admin Email Address <span class="req-star">*</span></label>
                                <div class="profile-input-wrapper">
                                    <i class="fa-solid fa-envelope field-icon"></i>
                                    <input type="email" id="input_admin_email" name="admin_email" class="profile-form-control" value="<?= esc($adminDisplayEmail) ?>" placeholder="e.g. admin@SSPTEX.com" required>
                                </div>
                                <span class="field-hint">Used for primary administrator login authentication.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Change Password -->
                <div class="profile-section-card">
                    <div class="section-card-header">
                        <div class="header-icon-box key-icon">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div>
                            <h3 class="section-title">Change Password</h3>
                            <p class="section-desc">Authorize and set a new password for administrator access.</p>
                        </div>
                    </div>

                    <div class="section-card-body">
                        <div class="profile-form-group">
                            <label for="input_current_password">Current Password (Old Password)</label>
                            <div class="profile-input-wrapper">
                                <i class="fa-solid fa-lock-open field-icon"></i>
                                <input type="password" id="input_current_password" name="current_password" class="profile-form-control" placeholder="Enter current (old) password" autocomplete="current-password">
                                <button type="button" class="pwd-toggle-btn" data-target="input_current_password" tabindex="-1" title="Show / Hide Password" aria-label="Toggle password visibility">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </div>
                            <span class="field-hint">
                                <i class="fa-solid fa-circle-info"></i> Required only if changing password. Leave empty if updating display name or email.
                            </span>
                        </div>

                        <div class="form-row-2">
                            <div class="profile-form-group">
                                <label for="input_new_password">New Password</label>
                                <div class="profile-input-wrapper">
                                    <i class="fa-solid fa-lock field-icon"></i>
                                    <input type="password" id="input_new_password" name="new_password" class="profile-form-control" placeholder="Enter new password (min. 6 characters)" autocomplete="new-password">
                                    <button type="button" class="pwd-toggle-btn" data-target="input_new_password" tabindex="-1" title="Show / Hide Password" aria-label="Toggle password visibility">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                                <span class="field-hint">Must be at least 6 characters long.</span>
                            </div>

                            <div class="profile-form-group">
                                <label for="input_confirm_password">Confirm New Password</label>
                                <div class="profile-input-wrapper">
                                    <i class="fa-solid fa-shield-halved field-icon"></i>
                                    <input type="password" id="input_confirm_password" name="confirm_password" class="profile-form-control" placeholder="Re-enter new password to confirm" autocomplete="new-password">
                                    <button type="button" class="pwd-toggle-btn" data-target="input_confirm_password" tabindex="-1" title="Show / Hide Password" aria-label="Toggle password visibility">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                                <span class="field-hint">Must match new password exactly.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Buttons -->
                    <div class="section-card-footer">
                        <div class="footer-info">
                            <i class="fa-solid fa-shield-halved" style="color: #10b981;"></i>
                            Changes take effect immediately upon saving.
                        </div>
                        <div class="footer-buttons">
                            <button type="reset" class="btn-profile-secondary" id="btnResetForm">
                                <i class="fa-solid fa-arrow-rotate-left"></i> Reset
                            </button>
                            <button type="submit" class="btn-profile-primary" id="btnSaveProfile">
                                <i class="fa-solid fa-floppy-disk"></i>
                                <span id="btnSaveText">Save Changes</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* ==========================================================================
   Admin Profile Page Premium Styles
   ========================================================================== */
.admin-profile-page-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 10px 0 40px 0;
}

.admin-profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 15px;
}

.page-main-title {
    font-size: 24px;
    font-weight: 800;
    color: #1e293b;
    margin: 0 0 6px 0;
    display: flex;
    align-items: center;
    gap: 12px;
    letter-spacing: -0.3px;
}

.page-main-title .title-icon {
    color: #7a1530;
    font-size: 26px;
}

.page-subtitle {
    font-size: 13.5px;
    color: #64748b;
    margin: 0;
}

.btn-back-dashboard {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #475569;
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.btn-back-dashboard:hover {
    background: #f8fafc;
    border-color: #94a3b8;
    color: #1e293b;
    transform: translateY(-1px);
}

/* Alert Notifications */
.profile-page-alert {
    padding: 14px 20px;
    border-radius: 10px;
    margin-bottom: 24px;
    font-size: 13.5px;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: fadeIn 0.3s ease;
}

.profile-page-alert.alert-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
}

.profile-page-alert.alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
}

.profile-page-alert .alert-icon {
    font-size: 18px;
    flex-shrink: 0;
}

/* Layout Grid */
.profile-page-grid {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 24px;
    align-items: start;
}

@media (max-width: 960px) {
    .profile-page-grid {
        grid-template-columns: 1fr;
    }
}

/* Left Sidebar Summary Card */
.profile-summary-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 30px 24px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    text-align: center;
    position: sticky;
    top: 90px;
}

.card-avatar-wrap {
    position: relative;
    width: 90px;
    height: 90px;
    margin: 0 auto 16px auto;
}

.profile-avatar-large {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: linear-gradient(135deg, #7a1530 0%, #aa1a40 100%);
    color: #ffffff;
    font-size: 32px;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 3px solid #d4af37;
    box-shadow: 0 8px 20px rgba(122, 21, 48, 0.25);
    letter-spacing: 1px;
}

.status-indicator-dot {
    position: absolute;
    bottom: 4px;
    right: 4px;
    width: 18px;
    height: 18px;
    background: #10b981;
    border: 3px solid #ffffff;
    border-radius: 50%;
}

.profile-summary-name {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
}

.profile-role-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    background: rgba(122, 21, 48, 0.1);
    color: #7a1530;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 6px;
}

.profile-console-tag {
    font-size: 12.5px;
    color: #64748b;
    margin-bottom: 22px;
}

.profile-summary-meta {
    border-top: 1px solid #f1f5f9;
    border-bottom: 1px solid #f1f5f9;
    padding: 16px 0;
    margin-bottom: 20px;
    text-align: left;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 13px;
    color: #475569;
    margin-bottom: 10px;
}

.meta-item:last-child {
    margin-bottom: 0;
}

.meta-item i {
    width: 16px;
    color: #7a1530;
    text-align: center;
    font-size: 13px;
}

.security-checklist-box {
    background: #fdfbf7;
    border: 1px solid #f1ece4;
    border-radius: 10px;
    padding: 14px 16px;
    text-align: left;
}

.checklist-title {
    font-size: 12px;
    font-weight: 800;
    color: #7a1530;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.checklist-items {
    list-style: none;
    padding: 0;
    margin: 0;
}

.checklist-items li {
    font-size: 12px;
    color: #475569;
    margin-bottom: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.checklist-items li:last-child {
    margin-bottom: 0;
}

.checklist-items li i {
    color: #10b981;
    font-size: 11px;
}

/* Right Column Forms */
.profile-section-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    margin-bottom: 24px;
}

.section-card-header {
    padding: 20px 24px;
    background: #fafafa;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 14px;
}

.header-icon-box {
    width: 42px;
    height: 42px;
    background: rgba(122, 21, 48, 0.1);
    border: 1px solid rgba(122, 21, 48, 0.2);
    color: #7a1530;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.header-icon-box.key-icon {
    background: rgba(212, 175, 55, 0.15);
    border-color: rgba(212, 175, 55, 0.35);
    color: #b45309;
}

.section-title {
    font-size: 16px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 2px 0;
}

.section-desc {
    font-size: 12.5px;
    color: #64748b;
    margin: 0;
}

.section-card-body {
    padding: 24px;
}

.form-row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (max-width: 680px) {
    .form-row-2 {
        grid-template-columns: 1fr;
    }
}

.profile-form-group {
    margin-bottom: 20px;
}

.profile-form-group:last-child {
    margin-bottom: 0;
}

.profile-form-group label {
    display: block;
    font-size: 12.5px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.req-star {
    color: #ef4444;
}

.profile-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.field-icon {
    position: absolute;
    left: 14px;
    color: #94a3b8;
    font-size: 14px;
    pointer-events: none;
    transition: color 0.2s ease;
}

.profile-form-control {
    width: 100%;
    padding: 11px 42px 11px 38px;
    background: #f8fafc;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14px;
    color: #1e293b;
    outline: none;
    transition: all 0.2s ease;
    font-family: inherit;
}

.profile-form-control:focus {
    background: #ffffff;
    border-color: #7a1530;
    box-shadow: 0 0 0 3px rgba(122, 21, 48, 0.12);
}

.profile-input-wrapper:focus-within .field-icon {
    color: #7a1530;
}

.field-hint {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 6px;
    display: block;
}

/* Footer Section */
.section-card-footer {
    padding: 18px 24px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
}

.footer-info {
    font-size: 12.5px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 8px;
}

.footer-buttons {
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-profile-secondary {
    padding: 10px 18px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #475569;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.btn-profile-secondary:hover {
    background: #f1f5f9;
    color: #1e293b;
}

.btn-profile-primary {
    padding: 10px 24px;
    background: linear-gradient(135deg, #7a1530 0%, #580c21 100%);
    border: 1px solid #580c21;
    color: #ffffff;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(122, 21, 48, 0.25);
    transition: all 0.2s ease;
}

.btn-profile-primary:hover {
    background: linear-gradient(135deg, #580c21 0%, #400717 100%);
    box-shadow: 0 6px 16px rgba(122, 21, 48, 0.35);
    transform: translateY(-1px);
}

.btn-profile-primary:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('adminProfileMainForm');
    var ajaxAlert = document.getElementById('pageAjaxAlert');
    var btnSave = document.getElementById('btnSaveProfile');
    var btnSaveText = document.getElementById('btnSaveText');

    function showPageAlert(msg, type) {
        if (!ajaxAlert) return;
        ajaxAlert.className = 'profile-page-alert ' + (type === 'success' ? 'alert-success' : 'alert-error');
        var icon = ajaxAlert.querySelector('.alert-icon');
        if (icon) {
            icon.className = 'alert-icon fa-solid ' + (type === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation');
        }
        var content = ajaxAlert.querySelector('.alert-content');
        if (content) {
            content.innerHTML = '<strong>' + (type === 'success' ? 'Success!' : 'Notice:') + '</strong> ' + msg;
        }
        ajaxAlert.style.display = 'flex';
        ajaxAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    if (form) {
        form.addEventListener('submit', function(e) {
            var curPassEl = document.getElementById('input_current_password');
            var newPassEl = document.getElementById('input_new_password');
            var confPassEl = document.getElementById('input_confirm_password');

            var curPass = curPassEl ? curPassEl.value : '';
            var newPass = newPassEl ? newPassEl.value : '';
            var confPass = confPassEl ? confPassEl.value : '';

            // Password client-side validation
            if (newPass !== '' || curPass !== '' || confPass !== '') {
                if (!curPass) {
                    e.preventDefault();
                    showPageAlert('Please enter your current (old) password to authorize the change.', 'error');
                    if (curPassEl) curPassEl.focus();
                    return;
                }
                if (!newPass) {
                    e.preventDefault();
                    showPageAlert('Please enter your new password.', 'error');
                    if (newPassEl) newPassEl.focus();
                    return;
                }
                if (newPass.length < 6) {
                    e.preventDefault();
                    showPageAlert('New password must be at least 6 characters long.', 'error');
                    if (newPassEl) newPassEl.focus();
                    return;
                }
                if (newPass !== confPass) {
                    e.preventDefault();
                    showPageAlert('New password and confirm password do not match.', 'error');
                    if (confPassEl) confPassEl.focus();
                    return;
                }
            }

            // AJAX Submission for seamless interactive update
            e.preventDefault();
            if (btnSave) btnSave.disabled = true;
            if (btnSaveText) btnSaveText.textContent = 'Saving Changes...';

            var formData = new FormData(form);

            fetch(form.getAttribute('action'), {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(res) {
                return res.json();
            })
            .then(function(data) {
                // Update CSRF tokens
                if (data.csrf_hash || data.token) {
                    var tokenVal = data.csrf_hash || data.token;
                    document.querySelectorAll('input[type="hidden"]').forEach(function(inp) {
                        if (inp.name && inp.name.toLowerCase().indexOf('csrf') !== -1) {
                            inp.value = tokenVal;
                        }
                    });
                }

                if (data.success) {
                    showPageAlert(data.message || 'Profile and password updated successfully!', 'success');

                    // Update UI items on page
                    var nameInput = document.getElementById('input_admin_name');
                    var emailInput = document.getElementById('input_admin_email');
                    if (nameInput && nameInput.value.trim()) {
                        var newName = nameInput.value.trim();
                        var newInitials = newName.substring(0, 2).toUpperCase();

                        var pageAvatar = document.getElementById('profilePageAvatar');
                        if (pageAvatar) pageAvatar.textContent = newInitials;
                        var pageName = document.getElementById('profilePageName');
                        if (pageName) pageName.textContent = newName;

                        var topbarName = document.getElementById('topbarAdminName');
                        if (topbarName) topbarName.textContent = newName;
                        var topbarAvatar = document.getElementById('topbarAvatar');
                        if (topbarAvatar) topbarAvatar.textContent = newInitials;

                        var sidebarName = document.getElementById('sidebarAdminName');
                        if (sidebarName) sidebarName.textContent = newName;
                        var sidebarAvatar = document.getElementById('sidebarAvatar');
                        if (sidebarAvatar) sidebarAvatar.textContent = newInitials;
                    }
                    if (emailInput && emailInput.value.trim()) {
                        var pageEmail = document.getElementById('profilePageEmail');
                        if (pageEmail) pageEmail.textContent = emailInput.value.trim();
                    }

                    // Clear password fields
                    if (curPassEl) curPassEl.value = '';
                    if (newPassEl) newPassEl.value = '';
                    if (confPassEl) confPassEl.value = '';
                } else {
                    showPageAlert(data.message || 'Failed to update credentials.', 'error');
                }
            })
            .catch(function(err) {
                console.error(err);
                showPageAlert('A server error occurred. Please try again.', 'error');
            })
            .finally(function() {
                if (btnSave) btnSave.disabled = false;
                if (btnSaveText) btnSaveText.textContent = 'Save Changes';
            });
        });
    }
});
</script>

<?= $this->endSection() ?>
