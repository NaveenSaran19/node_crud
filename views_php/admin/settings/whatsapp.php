<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin-settings.css') ?>?v=<?= time() ?>">

<div class="settings-container">
    <div class="settings-header">
        <h2>
            <i class="fa-brands fa-whatsapp" style="margin-right: 10px; color: #25D366;"></i> WhatsApp Business API Settings
        </h2>
        <p>Configure automated WhatsApp order confirmations, dispatch tracking alerts, and customer notifications.</p>
    </div>

    <?php if (!empty($flash_success)): ?>
        <div class="alert alert-success" style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fa-solid fa-circle-check"></i> <?= esc($flash_success) ?>
        </div>
    <?php endif; ?>

    <div class="premium-card" style="background: #fff; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
        <form action="<?= base_url('admin/settings/whatsapp/update') ?>" method="POST">
            <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-key" style="color: #25D366;"></i> WhatsApp API Gateway Credentials
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">Enable WhatsApp Notifications</label>
                    <select name="whatsapp_enabled" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                        <option value="1" <?= ($settings['whatsapp_enabled'] ?? '1') === '1' ? 'selected' : '' ?>>Enabled (Active)</option>
                        <option value="0" <?= ($settings['whatsapp_enabled'] ?? '') === '0' ? 'selected' : '' ?>>Disabled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">API Provider</label>
                    <select name="whatsapp_provider" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                        <option value="meta_cloud" <?= ($settings['whatsapp_provider'] ?? 'meta_cloud') === 'meta_cloud' ? 'selected' : '' ?>>Meta Official Cloud API (Graph v18+)</option>
                        <option value="ultramsg" <?= ($settings['whatsapp_provider'] ?? '') === 'ultramsg' ? 'selected' : '' ?>>UltraMsg WhatsApp API</option>
                        <option value="wati" <?= ($settings['whatsapp_provider'] ?? '') === 'wati' ? 'selected' : '' ?>>Wati.io Gateway</option>
                        <option value="twilio" <?= ($settings['whatsapp_provider'] ?? '') === 'twilio' ? 'selected' : '' ?>>Twilio WhatsApp</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">Phone Number ID</label>
                    <input type="text" name="whatsapp_phone_number_id" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;" value="<?= esc($settings['whatsapp_phone_number_id'] ?? '') ?>" placeholder="e.g. 109876543210987">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">WhatsApp Business Account ID (WABA)</label>
                    <input type="text" name="whatsapp_waba_id" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;" value="<?= esc($settings['whatsapp_waba_id'] ?? '') ?>" placeholder="e.g. 293847561029384">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">System User Permanent Access Token / API Key</label>
                    <input type="password" name="whatsapp_api_token" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;" value="<?= esc($settings['whatsapp_api_token'] ?? '') ?>" placeholder="EAAG... (Bearer Token)">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">Store Support WhatsApp Number</label>
                    <input type="text" name="whatsapp_support_number" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;" value="<?= esc($settings['whatsapp_support_number'] ?? '919789296751') ?>" placeholder="e.g. 919789296751">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">Auto Order Confirmation Notification</label>
                    <select name="whatsapp_notify_order" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                        <option value="1" <?= ($settings['whatsapp_notify_order'] ?? '1') === '1' ? 'selected' : '' ?>>Enabled (Send upon order placement)</option>
                        <option value="0" <?= ($settings['whatsapp_notify_order'] ?? '') === '0' ? 'selected' : '' ?>>Disabled</option>
                    </select>
                </div>
            </div>

            <button type="submit" style="background: #25D366; color: #fff; padding: 12px 28px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s;">
                <i class="fa-solid fa-save"></i> Save WhatsApp Configuration
            </button>
        </form>
    </div>

    <!-- Live Test Message Card -->
    <div class="premium-card" style="background: #fff; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-paper-plane" style="color: #25D366;"></i> Send Test WhatsApp Message
        </h3>
        <p style="color: #64748b; font-size: 13.5px; margin-bottom: 20px;">Send an instant test order confirmation alert to verify your WhatsApp API credentials.</p>

        <div style="display: flex; gap: 12px; max-width: 600px; margin-bottom: 20px;">
            <input type="text" id="testPhoneInput" class="premium-input" style="flex: 1; padding: 10px 14px; border-radius: 8px; border: 1px solid #cbd5e1;" placeholder="Enter 10-digit mobile number (e.g. 9789296751)" value="9789296751">
            <button type="button" onclick="sendTestWhatsApp()" style="background: #0f172a; color: #fff; padding: 10px 22px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">
                Send Test
            </button>
        </div>

        <div id="testResultBox" style="display: none; padding: 15px; border-radius: 8px; font-size: 13.5px;"></div>
    </div>
</div>

<script>
async function sendTestWhatsApp() {
    const phone = document.getElementById('testPhoneInput').value.trim();
    if (!phone) {
        alert('Please enter a recipient phone number.');
        return;
    }
    const box = document.getElementById('testResultBox');
    box.style.display = 'block';
    box.style.background = '#f8fafc';
    box.style.color = '#334155';
    box.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sending test notification...';

    try {
        const res = await fetch('<?= base_url('admin/settings/whatsapp/test') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ phone })
        });
        const data = await res.json();
        if (data.status === 'success') {
            box.style.background = '#ecfdf5';
            box.style.color = '#065f46';
            box.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + data.message;
        } else {
            box.style.background = '#fef2f2';
            box.style.color = '#991b1b';
            box.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> ' + data.message;
        }
    } catch (err) {
        box.style.background = '#fef2f2';
        box.style.color = '#991b1b';
        box.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Failed to send test: ' + err.message;
    }
}
</script>

<?= $this->endSection() ?>
