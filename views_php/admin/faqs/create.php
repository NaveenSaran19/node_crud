<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div style="max-width: 900px; margin: 0 auto;">
    <div style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <a href="<?= base_url('admin/faqs') ?>" style="display: inline-flex; align-items: center; gap: 6px; color: #64748b; font-size: 13.5px; text-decoration: none; margin-bottom: 8px;">
                <i class="fa-solid fa-arrow-left"></i> Back to FAQs List
            </a>
            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 0;">Add New FAQ Question</h1>
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
        <form action="<?= base_url('admin/faqs/store') ?>" method="POST">
            <?= csrf_field() ?>

            <!-- Question -->
            <div style="margin-bottom: 24px;">
                <label for="question" style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                    Question <span style="color: #dc2626;">*</span>
                </label>
                <input type="text" id="question" name="question" value="<?= old('question') ?>" required placeholder="e.g. How do I place an order for Elampillai handlooms?" style="width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; color: #0f172a; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#7a1530'" onblur="this.style.borderColor='#cbd5e1'">
            </div>

            <!-- Category & Display Order Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div>
                    <label for="category" style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Category
                    </label>
                    <input type="text" id="category" name="category" value="<?= old('category', 'Ordering & Delivery') ?>" list="faq_categories" placeholder="e.g. Ordering, Payments, Shipping, Quality" style="width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; color: #0f172a; outline: none;" onfocus="this.style.borderColor='#7a1530'" onblur="this.style.borderColor='#cbd5e1'">
                    <datalist id="faq_categories">
                        <option value="Ordering">
                        <option value="Payments">
                        <option value="Shipping & Delivery">
                        <option value="Authenticity & Quality">
                        <option value="Returns & Exchanges">
                        <option value="Saree Care">
                    </datalist>
                </div>
                <div>
                    <label for="display_order" style="display: block; font-weight: 700; color: #1e293b; margin-bottom: 8px; font-size: 14px;">
                        Display Order Number
                    </label>
                    <input type="number" id="display_order" name="display_order" value="<?= old('display_order', $nextOrder ?? 1) ?>" min="0" style="width: 100%; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 15px; color: #0f172a; outline: none;" onfocus="this.style.borderColor='#7a1530'" onblur="this.style.borderColor='#cbd5e1'">
                    <small style="display: block; color: #64748b; margin-top: 5px;">Lower numbers appear first on the storefront.</small>
                </div>
            </div>

            <!-- Answer Content -->
            <div style="margin-bottom: 24px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <label for="answer" style="display: block; font-weight: 700; color: #1e293b; font-size: 14px; margin: 0;">
                        Answer <span style="color: #dc2626;">*</span>
                    </label>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" onclick="insertFaqBullet()" style="background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 600; color: #475569; cursor: pointer;">
                            <i class="fa-solid fa-list-ul"></i> + Bullet (•)
                        </button>
                        <button type="button" onclick="cleanFaqHtml()" style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px; padding: 4px 10px; font-size: 12px; font-weight: 600; color: #dc2626; cursor: pointer;">
                            <i class="fa-solid fa-broom"></i> Remove HTML Tags
                        </button>
                    </div>
                </div>
                <textarea id="answer" name="answer" rows="7" required placeholder="Write a clear, detailed explanation in plain text..." style="width: 100%; padding: 14px 16px; border: 1.5px solid #cbd5e1; border-radius: 8px; font-size: 14.5px; color: #0f172a; line-height: 1.7; outline: none; transition: border-color 0.2s;" onfocus="this.style.borderColor='#7a1530'" onblur="this.style.borderColor='#cbd5e1'"><?= old('answer') ?></textarea>
                <small style="display: block; color: #64748b; margin-top: 6px; font-size: 13px;">
                    <i class="fa-solid fa-circle-check" style="color: #16a34a; margin-right: 4px;"></i> Plain text only. Line breaks and bullet points (•) will be formatted automatically.
                </small>
            </div>

            <script>
            function insertFaqBullet() {
                var ta = document.getElementById('answer');
                if (!ta) return;
                var start = ta.selectionStart;
                var end = ta.selectionEnd;
                var val = ta.value;
                var prefix = (start > 0 && val[start - 1] !== '\n') ? '\n• ' : '• ';
                ta.value = val.substring(0, start) + prefix + val.substring(end);
                ta.focus();
                ta.selectionStart = ta.selectionEnd = start + prefix.length;
            }
            function cleanFaqHtml() {
                var ta = document.getElementById('answer');
                if (!ta) return;
                var temp = document.createElement('div');
                temp.innerHTML = ta.value.replace(/<\/li>/gi, '\n').replace(/<\/p>/gi, '\n\n').replace(/<br\s*[\/]?>/gi, '\n');
                ta.value = (temp.textContent || temp.innerText || '').replace(/\n{3,}/g, '\n\n').trim();
            }
            </script>

            <!-- Active Status Toggle -->
            <div style="margin-bottom: 30px; padding: 16px 20px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <label for="is_active" style="font-weight: 700; color: #0f172a; cursor: pointer; display: block; margin-bottom: 2px;">
                        Enable this FAQ
                    </label>
                    <span style="font-size: 13px; color: #64748b;">When enabled, this question and answer will appear live in the frontend FAQ section.</span>
                </div>
                <div>
                    <input type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active', '1') == '1' ? 'checked' : '' ?> style="width: 20px; height: 20px; accent-color: #7a1530; cursor: pointer;">
                </div>
            </div>

            <!-- Actions -->
            <div style="display: flex; gap: 14px; justify-content: flex-end;">
                <a href="<?= base_url('admin/faqs') ?>" class="btn" style="padding: 12px 24px; background: #f1f5f9; color: #475569; border-radius: 8px; font-weight: 600; text-decoration: none; border: 1px solid #cbd5e1;">
                    Cancel
                </a>
                <button type="submit" class="btn btn-primary" style="padding: 12px 28px; background: #7a1530; color: #fff; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(122,21,48,0.25);">
                    <i class="fa-solid fa-check"></i> Save FAQ Question
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
