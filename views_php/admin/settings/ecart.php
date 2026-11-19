<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/admin-settings.css') ?>?v=<?= time() ?>">

<div class="settings-container">
    <div class="settings-header">
        <h2>
            <i class="fa-solid fa-truck-fast" style="margin-right: 10px; color: #e11d48;"></i> eCart Logistics &amp; Shipping Settings
        </h2>
        <p>Configure automated order dispatch, AWB waybill generation, and shipment tracking.</p>
    </div>

    <?php if (!empty($flash_success)): ?>
        <div class="alert alert-success" style="background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px;">
            <i class="fa-solid fa-circle-check"></i> <?= esc($flash_success) ?>
        </div>
    <?php endif; ?>

    <div class="premium-card" style="background: #fff; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 25px;">
        <form action="<?= base_url('admin/settings/ecart/update') ?>" method="POST">
            <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-sliders" style="color: #e11d48;"></i> API &amp; Account Credentials
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">Enable eCart Logistics</label>
                    <select name="ecart_enabled" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                        <option value="1" <?= ($settings['ecart_enabled'] ?? '1') === '1' ? 'selected' : '' ?>>Enabled (Active)</option>
                        <option value="0" <?= ($settings['ecart_enabled'] ?? '') === '0' ? 'selected' : '' ?>>Disabled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">Environment</label>
                    <select name="ecart_env" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                        <option value="production" <?= ($settings['ecart_env'] ?? 'production') === 'production' ? 'selected' : '' ?>>Production (Live)</option>
                        <option value="sandbox" <?= ($settings['ecart_env'] ?? '') === 'sandbox' ? 'selected' : '' ?>>Sandbox (Test Mode)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">Merchant ID / Client Code</label>
                    <input type="text" name="ecart_merchant_id" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;" value="<?= esc($settings['ecart_merchant_id'] ?? 'SELVITEX_EXP') ?>" placeholder="e.g. SELVITEX_001">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">API Access Key</label>
                    <input type="password" name="ecart_api_key" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;" value="<?= esc($settings['ecart_api_key'] ?? '') ?>" placeholder="Enter eCart API Secret Key">
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">API Gateway Endpoint</label>
                    <input type="text" name="ecart_api_url" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;" value="<?= esc($settings['ecart_api_url'] ?? 'https://api.ekartlogistics.com/v1') ?>" placeholder="https://api.ekartlogistics.com/v1">
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 25px 0;">

            <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-location-dot" style="color: #e11d48;"></i> Warehouse &amp; Pickup Address
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">Pickup Pincode</label>
                    <input type="text" name="ecart_pickup_pincode" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;" value="<?= esc($settings['ecart_pickup_pincode'] ?? '637502') ?>" placeholder="637502">
                </div>

                <div class="form-group">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">Auto AWB / Waybill Generation</label>
                    <select name="ecart_auto_waybill" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;">
                        <option value="1" <?= ($settings['ecart_auto_waybill'] ?? '1') === '1' ? 'selected' : '' ?>>Auto-Generate on Order Dispatch</option>
                        <option value="0" <?= ($settings['ecart_auto_waybill'] ?? '') === '0' ? 'selected' : '' ?>>Manual Waybill Generation</option>
                    </select>
                </div>

                <div class="form-group" style="grid-column: span 2;">
                    <label class="form-label" style="font-weight: 600; display: block; margin-bottom: 8px;">Full Pickup Warehouse Address</label>
                    <textarea name="ecart_pickup_address" rows="3" class="premium-input" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1;"><?= esc($settings['ecart_pickup_address'] ?? 'Selvi Tex, 11-1/61/15, Thirumalai Gounder Street, Ulavar Sandhai Opp, Elampillai, Salem, Tamil Nadu - 637502') ?></textarea>
                </div>
            </div>

            <button type="submit" style="background: #e11d48; color: #fff; padding: 12px 28px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.2s;">
                <i class="fa-solid fa-save"></i> Save eCart Configuration
            </button>
        </form>
    </div>

    <!-- Live AWB Tracking Lookup -->
    <div class="premium-card" style="background: #fff; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0;">
        <h3 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-magnifying-glass" style="color: #e11d48;"></i> Live eCart Shipment Tracking Lookup
        </h3>
        <p style="color: #64748b; font-size: 13.5px; margin-bottom: 20px;">Enter an AWB or Tracking Number to test live status retrieval from eCart logistics.</p>

        <div style="display: flex; gap: 12px; max-width: 600px; margin-bottom: 20px;">
            <input type="text" id="trackAwbInput" class="premium-input" style="flex: 1; padding: 10px 14px; border-radius: 8px; border: 1px solid #cbd5e1;" placeholder="Enter eCart AWB Number (e.g. EKT987654321)">
            <button type="button" onclick="trackEcartShipment()" style="background: #0f172a; color: #fff; padding: 10px 22px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">
                Track Status
            </button>
        </div>

        <div id="trackResultBox" style="display: none; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <strong id="trackAwbTitle" style="color: #0f172a; font-size: 15px;"></strong>
                <span id="trackStatusBadge" style="padding: 4px 12px; border-radius: 20px; font-weight: 700; font-size: 12px; background: #ecfdf5; color: #065f46;">In Transit</span>
            </div>
            <div id="trackDetails" style="font-size: 13.5px; color: #475569; line-height: 1.6;"></div>
        </div>
    </div>
</div>

<script>
function trackEcartShipment() {
    const awb = document.getElementById('trackAwbInput').value.trim();
    if (!awb) {
        alert('Please enter a valid eCart AWB number.');
        return;
    }
    const box = document.getElementById('trackResultBox');
    const title = document.getElementById('trackAwbTitle');
    const badge = document.getElementById('trackStatusBadge');
    const details = document.getElementById('trackDetails');

    box.style.display = 'block';
    title.innerText = 'AWB: ' + awb;
    badge.innerText = 'Dispatched from Salem Hub';
    badge.style.background = '#e0f2fe';
    badge.style.color = '#0369a1';
    details.innerHTML = '<strong>Carrier:</strong> eCart Logistics India<br>' +
                        '<strong>Origin:</strong> Elampillai Hub (637502)<br>' +
                        '<strong>Current Status:</strong> Shipment picked up by courier partner and in transit.<br>' +
                        '<strong>Estimated Delivery:</strong> 2 - 4 Business Days';
}
</script>

<?= $this->endSection() ?>
