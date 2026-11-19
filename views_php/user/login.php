<?= view('user/header') ?>

<?php
    $logoUrl = (!empty($settings['business_logo'])) ? base_url($settings['business_logo']) : base_url('assets/images/logo.png');
    $bizName = !empty($settings['business_name']) ? $settings['business_name'] : 'Sri Sakthi Selvi Tex';
    $shortName = !empty($settings['short_name']) ? $settings['short_name'] : 'Selvi Tex';
?>

<style>
.customer-login-main {
    background-color: #f8fafc;
    padding: 50px 20px;
    min-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Inter', sans-serif;
    box-sizing: border-box;
}
.customer-login-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.08);
    display: flex;
    overflow: hidden;
    max-width: 950px;
    width: 100%;
    border: 1px solid #e2e8f0;
    box-sizing: border-box;
}
.customer-login-brand {
    flex: 1;
    background: radial-gradient(circle at 10% 20%, rgba(212, 175, 55, 0.18) 0%, transparent 45%), linear-gradient(145deg, #e11d48 0%, #3e0716 100%);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    min-height: 520px;
    padding: 38px 34px;
    color: white;
    box-sizing: border-box;
    overflow: hidden;
}
.brand-bg-pattern {
    position: absolute;
    inset: 0;
    opacity: 0.05;
    background-image: radial-gradient(#d4af37 1px, transparent 1px);
    background-size: 16px 16px;
    pointer-events: none;
}
.customer-login-form-wrap {
    flex: 1.1;
    padding: 45px 40px;
    width: 100%;
    box-sizing: border-box;
}
@media (max-width: 820px) {
    .customer-login-main {
        padding: 20px 12px 60px 12px !important;
        min-height: auto !important;
    }
    .customer-login-card {
        flex-direction: column !important;
        max-width: 100% !important;
        border-radius: 12px !important;
    }
    .customer-login-brand {
        flex: none !important;
        min-height: auto !important;
        padding: 26px 20px !important;
        text-align: center !important;
        justify-content: center !important;
        align-items: center !important;
    }
    .customer-login-brand h2 {
        font-size: 20px !important;
    }
    .customer-login-brand p {
        font-size: 13px !important;
    }
    .customer-login-form-wrap {
        flex: none !important;
        padding: 26px 16px !important;
        width: 100% !important;
    }
    .customer-login-form-wrap h1 {
        font-size: 22px !important;
    }
}
</style>

<main class="customer-login-main">
    
    <div class="customer-login-card">
        
        <!-- Left Side: Handloom Brand Image & Heritage Highlights -->
        <div class="customer-login-brand">
            <div class="brand-bg-pattern"></div>
            <?php if(!empty($settings['login_banner'])): ?>
                <img src="<?= base_url($settings['login_banner']) ?>" style="position: absolute; top:0; left:0; width: 100%; height: 100%; object-fit: cover; opacity: 0.25;" alt="Store Banner">
            <?php endif; ?>
            
            <div style="position: relative; z-index: 2; display: flex; flex-direction: column; justify-content: space-between; height: 100%; gap: 24px;">
                
                <!-- Brand Header -->
                <div>
                    <div style="display: flex; align-items: center; gap: 14px; margin-bottom: 14px;">
                        <img src="<?= $logoUrl ?>" alt="<?= esc($bizName) ?>" style="height: 52px; width: auto; max-width: 130px; object-fit: contain; filter: drop-shadow(0 2px 6px rgba(0,0,0,0.5));" onerror="this.onerror=null;this.src='<?= base_url('assets/images/logo.png') ?>';">
                        <div>
                            <div style="font-family: 'Cinzel', Georgia, serif; font-size: 19px; font-weight: 800; color: #ffffff; letter-spacing: 1px; line-height: 1.2;"><?= esc($bizName) ?></div>
                            <div style="font-size: 10.5px; text-transform: uppercase; letter-spacing: 1.4px; color: #facc15; font-weight: 700;">Pure Silk &amp; Handlooms</div>
                        </div>
                    </div>
                    <span style="background: rgba(197, 155, 39, 0.22); border: 1px solid rgba(212, 175, 55, 0.5); color: #fef08a; font-size: 10.5px; font-weight: 800; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 1px; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-crown" style="color: #ffd700;"></i> Authentic Elampillai Weaves
                    </span>
                </div>

                <!-- Customer Benefits / Trust Highlights -->
                <div style="display: flex; flex-direction: column; gap: 14px; background: rgba(0, 0, 0, 0.22); padding: 18px; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.08);">
                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(212, 175, 55, 0.2); border: 1px solid rgba(212, 175, 55, 0.45); color: #ffd700; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;">
                            <i class="fa-solid fa-gem"></i>
                        </div>
                        <div>
                            <div style="color: #ffffff; font-size: 13.5px; font-weight: 700; line-height: 1.2;">Direct Weaver Pricing</div>
                            <div style="color: #f1f5f9; font-size: 12px; opacity: 0.85; margin-top: 2px;">Authentic handlooms at direct loom manufacturer rates</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(212, 175, 55, 0.2); border: 1px solid rgba(212, 175, 55, 0.45); color: #ffd700; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <div>
                            <div style="color: #ffffff; font-size: 13.5px; font-weight: 700; line-height: 1.2;">Express Doorstep Delivery</div>
                            <div style="color: #f1f5f9; font-size: 12px; opacity: 0.85; margin-top: 2px;">Carefully packed &amp; dispatched across all India pin codes</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(212, 175, 55, 0.2); border: 1px solid rgba(212, 175, 55, 0.45); color: #ffd700; display: flex; align-items: center; justify-content: center; font-size: 13px; flex-shrink: 0;">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <div style="color: #ffffff; font-size: 13.5px; font-weight: 700; line-height: 1.2;">Live Order Tracking</div>
                            <div style="color: #f1f5f9; font-size: 12px; opacity: 0.85; margin-top: 2px;">Check dispatch, courier AWB tracking &amp; download bills</div>
                        </div>
                    </div>

                    <div style="display: flex; align-items: flex-start; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(37, 211, 102, 0.2); border: 1px solid rgba(37, 211, 102, 0.5); color: #4ade80; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0;">
                            <i class="fa-brands fa-whatsapp"></i>
                        </div>
                        <div>
                            <div style="color: #ffffff; font-size: 13.5px; font-weight: 700; line-height: 1.2;">Dedicated WhatsApp Help</div>
                            <div style="color: #f1f5f9; font-size: 12px; opacity: 0.85; margin-top: 2px;">Instant personal support for color &amp; drape inquiries</div>
                        </div>
                    </div>
                </div>

                <!-- Footer Guarantee -->
                <div style="border-top: 1px solid rgba(255,255,255,0.12); padding-top: 14px;">
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; color: #fdf5e6;">
                        <span style="color: #ffd700; letter-spacing: 2px;">★★★★★</span>
                        <span>Trusted by 10,000+ handloom lovers</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Side: Form & Google Sign-in -->
        <div class="customer-login-form-wrap">
            <div style="text-align: center; margin-bottom: 25px;">
                <h1 style="color: #0f172a; font-size: 26px; font-weight: 800; margin: 0 0 6px 0;">Customer Sign In</h1>
                <p style="color: #64748b; font-size: 14px; margin: 0;">Access your customer dashboard and order tracking</p>
            </div>

            <?php if(session()->getFlashdata('error')): ?>
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;">
                    <i class="fa-solid fa-circle-exclamation"></i> <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if(session()->getFlashdata('success')): ?>
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-bottom: 20px;">
                    <i class="fa-solid fa-circle-check"></i> <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <!-- Google Official Sign In Button & One-Tap -->
            <div style="margin-bottom: 22px;">
                <!-- Google GSI Script -->
                <script src="https://accounts.google.com/gsi/client" async defer></script>
                <div id="g_id_onload"
                    data-client_id="<?= esc($googleClientId ?? '796019416522-6r427f081dfsng8qdtdqs0kd7dbjpk55.apps.googleusercontent.com') ?>"
                    data-context="signin"
                    data-ux_mode="popup"
                    data-callback="handleGoogleCredentialResponse"
                    data-auto_prompt="false">
                </div>

                <!-- Primary Official Google Sign In Button -->
                <a href="<?= base_url('login/google?action=redirect') ?>" style="display: flex; align-items: center; justify-content: center; gap: 12px; border: 1.5px solid #cbd5e1; padding: 12px 16px; border-radius: 8px; text-decoration: none; color: #1e293b; font-weight: 700; font-size: 14.5px; transition: all 0.2s; background: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.06);" onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#94a3b8';" onmouseout="this.style.background='#ffffff'; this.style.borderColor='#cbd5e1';">
                    <svg width="20" height="20" viewBox="0 0 18 18" style="flex-shrink: 0;">
                        <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844c-.209 1.125-.843 2.078-1.796 2.717v2.258h2.908c1.702-1.567 2.684-3.874 2.684-6.616z"/>
                        <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.258c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332C2.438 15.983 5.482 18 9 18z"/>
                        <path fill="#FBBC05" d="M3.964 10.707c-.18-.54-.282-1.117-.282-1.707s.102-1.167.282-1.707V4.961H.957C.347 6.175 0 7.55 0 9s.347 2.825.957 4.039l3.007-2.332z"/>
                        <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0 5.482 0 2.438 2.017.957 4.961L3.964 7.293C4.672 5.166 6.656 3.58 9 3.58z"/>
                    </svg>
                    <span>Continue with Google</span>
                </a>

                <script>
                    function handleGoogleCredentialResponse(response) {
                        if (response && response.credential) {
                            var form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '<?= base_url("login/google") ?>';
                            
                            var input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'credential';
                            input.value = response.credential;
                            form.appendChild(input);
                            
                            var csrf = document.createElement('input');
                            csrf.type = 'hidden';
                            csrf.name = '<?= csrf_token() ?>';
                            csrf.value = '<?= csrf_hash() ?>';
                            form.appendChild(csrf);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    }
                </script>
            </div>

            <div style="display: flex; align-items: center; text-align: center; color: #94a3b8; font-size: 11px; font-weight: 700; margin-bottom: 22px;">
                <div style="flex: 1; height: 1px; background-color: #e2e8f0;"></div>
                <span style="padding: 0 12px; text-transform: uppercase; letter-spacing: 0.5px;">Or sign in with email</span>
                <div style="flex: 1; height: 1px; background-color: #e2e8f0;"></div>
            </div>

            <!-- Email Form -->
            <form action="<?= base_url('login/process') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Full Name (Optional for new users)</label>
                    <input type="text" name="name" placeholder="e.g. Naveen S" style="width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-size: 14px; box-sizing: border-box; transition: border-color 0.2s;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px;">Email Address *</label>
                    <input type="email" name="email" value="" placeholder="name@example.com" required style="width: 100%; padding: 12px 14px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-size: 14px; box-sizing: border-box; transition: border-color 0.2s;">
                </div>

                <button type="submit" style="width: 100%; background: #e11d48; color: white; border: none; padding: 14px; font-size: 15px; font-weight: 700; border-radius: 8px; cursor: pointer; transition: background 0.2s; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> Enter Customer Dashboard
                </button>
            </form>

            <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #64748b;">
                <i class="fa-solid fa-shield-halved" style="color: #16a34a; margin-right: 4px;"></i>
                Secured customer authentication & order history
            </div>
        </div>
    </div>

</main>

<?= view('user/footer') ?>
