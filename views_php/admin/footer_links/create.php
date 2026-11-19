<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div style="max-width: 900px; margin: 0 auto;">
    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <a href="<?= base_url('admin/footer-links') ?>" style="display: inline-flex; align-items: center; gap: 6px; color: #64748b; font-size: 13.5px; text-decoration: none; margin-bottom: 8px;">
                <i class="fa-solid fa-arrow-left"></i> Back to Footer Links
            </a>
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0;">Add New Footer Link</h1>
        </div>
    </div>

    <?php if(session()->getFlashdata('errors')): ?>
        <div style="background: #fef2f2; color: #991b1b; padding: 15px 18px; border-radius: 8px; margin-bottom: 22px; border: 1px solid #fecaca;">
            <strong style="display: block; margin-bottom: 6px;"><i class="fa-solid fa-circle-exclamation"></i> Please fix the following errors:</strong>
            <ul style="margin: 0; padding-left: 20px;">
                <?php foreach(session()->getFlashdata('errors') as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card" style="padding: 32px; border-radius: 14px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); background: #fff;">
        <form action="<?= base_url('admin/footer-links/store') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Link Title -->
            <div style="margin-bottom: 24px;">
                <label for="title" style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                    Link Title / Label <span style="color: #dc2626;">*</span>
                </label>
                <input type="text" id="title" name="title" value="<?= old('title') ?>" required placeholder="e.g. About Our Story" style="width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; color: #0f172a; outline: none;" onfocus="this.style.borderColor='#7a1530'" onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <!-- Destination URL -->
            <div style="margin-bottom: 24px;">
                <label for="url" style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                    Destination URL <span style="color: #dc2626;">*</span>
                </label>
                <input type="text" id="url" name="url" value="<?= old('url') ?>" required placeholder="e.g. /pages/about-selvi-tex or /policies/contact-information or https://..." style="width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; color: #0f172a; outline: none; font-family: monospace;" onfocus="this.style.borderColor='#7a1530'" onblur="this.style.borderColor='#cbd5e1'">
                <small style="display: block; color: #64748b; margin-top: 5px;">
                    Supports relative internal paths (e.g. <code>/pages/about-selvi-tex</code>, <code>/policies/shipping-policy</code>, <code>/blogs/news</code>) or external URLs (e.g. <code>https://instagram.com/...</code>).
                </small>
            </div>

            <!-- Section & Display Order Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div>
                    <label for="section" style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Footer Section / Column <span style="color: #dc2626;">*</span>
                    </label>
                    <select id="section_select" onchange="handleSectionChange(this)" style="width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; color: #0f172a; outline: none; background: #fff; margin-bottom: 8px;">
                        <option value="customer_care" <?= old('section', $section ?? '') == 'customer_care' ? 'selected' : '' ?>>Customer Care (Footer Column 2)</option>
                        <option value="our_collections" <?= old('section', $section ?? '') == 'our_collections' ? 'selected' : '' ?>>Our Collections (Footer Column 3)</option>
                        <option value="_custom">-- Custom Section Key --</option>
                    </select>
                    <input type="text" id="section" name="section" value="<?= old('section', $section ?? 'customer_care') ?>" required placeholder="section_key" style="width: 100%; padding: 10px 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; color: #0f172a; outline: none;">
                    <small style="display: block; color: #64748b; margin-top: 4px;">Sections displayed in the footer: <code>customer_care</code> and <code>our_collections</code>.</small>
                </div>
                <div>
                    <label for="display_order" style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Display Order
                    </label>
                    <input type="number" id="display_order" name="display_order" value="<?= old('display_order', $nextOrder ?? 1) ?>" min="0" style="width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; color: #0f172a; outline: none;">
                    <small style="display: block; color: #64748b; margin-top: 5px;">Lower numbers appear first within the section column.</small>
                </div>
            </div>

            <!-- Options: Open in New Tab & Active Status -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                <div style="padding: 16px 20px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <label for="is_active" style="font-weight: 700; color: #0f172a; cursor: pointer; display: block; margin-bottom: 2px;">
                            Enable Link
                        </label>
                        <span style="font-size: 12.5px; color: #64748b;">Show this link in footer navigation</span>
                    </div>
                    <div>
                        <input type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active', '1') == '1' ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: #7a1530; cursor: pointer;">
                    </div>
                </div>

                <div style="padding: 16px 20px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <label for="open_in_new_tab" style="font-weight: 700; color: #0f172a; cursor: pointer; display: block; margin-bottom: 2px;">
                            Open in New Tab
                        </label>
                        <span style="font-size: 12.5px; color: #64748b;">Adds target="_blank" (useful for external links)</span>
                    </div>
                    <div>
                        <input type="checkbox" id="open_in_new_tab" name="open_in_new_tab" value="1" <?= old('open_in_new_tab') == '1' ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: #7a1530; cursor: pointer;">
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 14px; justify-content: flex-end;">
                <a href="<?= base_url('admin/footer-links') ?>" class="btn" style="padding: 12px 24px; background: #f1f5f9; color: #475569; border-radius: 8px; font-weight: 600; text-decoration: none; border: 1px solid #cbd5e1;">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 12px 28px; background: #7a1530; color: #fff; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(122,21,48,0.25);">
                    <i class="fa-solid fa-check"></i> Save Footer Link
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function handleSectionChange(sel) {
    var textInput = document.getElementById('section');
    if (sel.value !== '_custom') {
        textInput.value = sel.value;
    } else {
        textInput.value = '';
        textInput.focus();
    }
}
</script>

<?= $this->endSection() ?>
