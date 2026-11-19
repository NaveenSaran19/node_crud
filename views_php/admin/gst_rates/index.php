<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>


<div style="max-width: 950px; margin: 0 auto;">
    <h2 style="font-size: 24px; font-weight: 800; color: #7a1530; margin-bottom: 30px;"><i class="fa-solid fa-percent" style="margin-right: 10px;"></i> GST Settings</h2>

    <?php if(session()->getFlashdata('success')): ?>
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 15px 20px; border-radius: 10px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
            <i class="fa-solid fa-circle-check"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="premium-card">
        <h3 class="section-title"><i class="fa-solid fa-power-off"></i> Enable / Disable GST Functionality</h3>
        <form action="<?= base_url('admin/gst-rates/toggle') ?>" method="POST" style="display: flex; align-items: center; justify-content: space-between;">
            <div>
                <strong style="color: #1e293b; font-size: 16px;">Global GST Status</strong>
                <p style="color: #64748b; margin-top: 5px; font-size: 14px;">If enabled, GST Dropdown will be shown while creating or editing products.</p>
            </div>
            <div style="display: flex; align-items: center; gap: 20px;">
                <label class="toggle-switch">
                    <input type="checkbox" name="enable_gst" value="1" <?= $enable_gst == '1' ? 'checked' : '' ?> onchange="this.form.submit()">
                    <span class="slider"></span>
                </label>
            </div>
        </form>
    </div>

    <div class="premium-card">
        <h3 class="section-title"><i class="fa-solid fa-plus"></i> Add New GST Rate</h3>
        <form action="<?= base_url('admin/gst-rates/store') ?>" method="POST" style="display: flex; gap: 20px; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0; flex: 1;">
                <label class="form-label">GST Rate (%)</label>
                <input type="number" step="0.01" name="rate" class="premium-input" required placeholder="e.g. 18.00">
            </div>
            <button type="submit" class="btn-premium"><i class="fa-solid fa-save"></i> Save Rate</button>
        </form>
    </div>

    <div class="premium-card">
        <h3 class="section-title"><i class="fa-solid fa-list-ol"></i> Configured GST Rates</h3>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rate (%)</th>
                        <th style="width: 250px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($gst_rates)): ?>
                        <?php foreach($gst_rates as $rate): ?>
                        <tr>
                            <td><?= $rate['id'] ?></td>
                            <td>
                                <form action="<?= base_url('admin/gst-rates/update/'.$rate['id']) ?>" method="POST" style="display: flex; gap: 10px;">
                                    <input type="number" step="0.01" name="rate" class="premium-input" value="<?= esc($rate['rate']) ?>" required style="padding: 8px; width: 100px;">
                                    <button type="submit" class="btn-edit"><i class="fa-solid fa-check"></i></button>
                                </form>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/gst-rates/delete/'.$rate['id']) ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this GST rate?')"><i class="fa-solid fa-trash"></i> Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" style="text-align: center; color: #64748b;">No GST rates configured.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
