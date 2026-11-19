<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>




<!-- All Categories Section -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="font-size: 18px; font-weight: 600;">All Categories</h2>
        <a href="<?= base_url('admin/categories/create') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Category</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div style="background: rgba(16, 185, 129, 0.15); color: #10b981; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid rgba(16, 185, 129, 0.3);">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div style="background: rgba(239, 68, 68, 0.15); color: #ef4444; padding: 12px 20px; border-radius: 6px; margin-bottom: 20px; border: 1px solid rgba(239, 68, 68, 0.3);">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Header Category Name</th>
                    <th>Subcategory Name</th>
                    <th>Category Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($subcategories)): foreach($subcategories as $cat): ?>
                <tr>
                    <td style="font-weight: 600;">#<?= esc($cat['id']) ?></td>
                    <td style="font-weight: 600; color: var(--primary-color);">
                        <?= esc($cat['main_category']) ?>
                    </td>
                    <td style="font-weight: 600;">
                        <?= esc($cat['name']) ?>
                    </td>
                    <td>
                        <?php if(!empty($cat['image'])): ?>
                            <img src="<?= base_url($cat['image']) ?>" class="table-img">
                        <?php else: ?>
                            <span style="color: #94a3b8; font-size: 12px;">No Image</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="<?= base_url('admin/categories/edit/'.$cat['id']) ?>" class="btn btn-action" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                            <a href="<?= base_url('admin/categories/delete/'.$cat['id']) ?>" class="btn btn-action btn-danger" onclick="return confirm('Are you sure you want to delete this category?')" title="Delete"><i class="fa-solid fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">No categories found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>



<?= $this->endSection() ?>
