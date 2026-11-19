<?= view('user/header') ?>
<main style="flex-grow: 1; padding: 100px 40px; text-align: center; background-color: #fdf5e6;">
    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 60px 40px; border-radius: 12px; box-shadow: 0 15px 40px rgba(225, 29, 72, 0.08); border-top: 6px solid #e11d48;">
        
        <h1 style="color: #e11d48; font-size: 36px; font-weight: 900; margin-bottom: 25px; font-family: 'Georgia', serif; letter-spacing: 0.5px;">
            <?= isset($title) ? esc($title) : 'Placeholder Page' ?>
        </h1>
        
        <div style="color: #5a4b42; font-size: 16px; line-height: 1.8; margin-bottom: 40px;">
            <?= isset($message) ? $message : 'Content coming soon.' ?>
        </div>
        
        <a href="<?= base_url('/') ?>" style="display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 14px 35px; background-color: #d4af37; color: #1a0f14; font-weight: 800; text-decoration: none; border-radius: 6px; transition: transform 0.2s, background-color 0.2s;">
            <i class="fa-solid fa-arrow-left"></i> Return to Homepage
        </a>
    </div>
</main>
<?= view('user/footer') ?>
