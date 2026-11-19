<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="font-size: 18px; font-weight: 600;">Manage Banners</h2>
        <a href="<?= base_url('admin/banners/create') ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Add New Banner</a>
    </div>

    <?php if(session()->getFlashdata('success')): ?>
        <div style="background: rgba(34, 197, 94, 0.1); color: #22c55e; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Position</th>
                    <th>Media</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($banners as $banner): ?>
                <tr>
                    <td><?= esc($banner['title']) ?></td>
                    <td>
                        <?php
                            $posLabels = [
                                'hero_section' => '1. Hero Section (Main Top Banner)',
                                'secondary-banner-carousel' => '2. Secondary Carousel (Exclusive Sarees)',
                                'diwali-dropdownvideo-banner' => '3. Diwali Saree Section (Scrolling Background)'
                            ];
                        ?>
                        <span style="font-weight: 600; color: #f59e0b;"><?= esc($posLabels[$banner['position']] ?? $banner['position']) ?></span>
                    </td>
                    <td>
                        <?php if($banner['media_type'] == 'image'): ?>
                            <img src="<?= base_url($banner['media_url']) ?>" style="height: 50px; object-fit: cover; border-radius: 4px;">
                        <?php else: ?>
                            <span style="padding: 4px 8px; background: rgba(255,255,255,0.1); border-radius: 4px; font-size: 12px;">Video</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($banner['is_active']): ?>
                            <span style="color: #22c55e;">Active</span>
                        <?php else: ?>
                            <span style="color: #ef4444;">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= base_url('admin/banners/edit/'.$banner['id']) ?>" class="btn btn-action"><i class="fa-solid fa-pen"></i></a>
                        <a href="<?= base_url('admin/banners/delete/'.$banner['id']) ?>" class="btn btn-action btn-danger" onclick="return confirm('Are you sure you want to delete this banner?')"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($banners)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: var(--text-muted);">No banners found. Click "Add New Banner" to create one.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
