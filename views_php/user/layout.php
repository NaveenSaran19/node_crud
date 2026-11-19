<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - Selvi Tex</title>
    <!-- External CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body style="display: flex; flex-direction: column; min-height: 100vh;">

    <!-- User Header -->
    <header class="user-header">
        <a href="#" class="logo">
            <img src="<?= base_url('assets/images/logo.png') ?>" alt="Selvi Tex Logo" style="height: 50px; width: auto; object-fit: contain;">
        </a>
        <nav class="user-nav">
            <ul>
                <li><a href="#">Selvi Tex</a></li>
                <li><a href="#">Cotton</a></li>
                <li><a href="#">New Arrival</a></li>
                <li><a href="#">All Collection</a></li>
                <li><a href="#">Best Seller</a></li>
            </ul>
        </nav>
        <div class="user-actions" style="color: var(--text-light);">
            <!-- Icons for user, search, cart (placeholders) -->
            <span><i class="fa-solid fa-user"></i></span>
            <span><i class="fa-solid fa-magnifying-glass"></i></span>
            <span><i class="fa-solid fa-cart-shopping"></i></span>
        </div>
    </header>

    <!-- Main Content -->
    <main style="flex-grow: 1; padding: 40px; text-align: center;">
        <h1>Welcome to Selvi Tex</h1>
        <p>Your main content goes here.</p>
    </main>

    <!-- User Footer -->
    <footer class="user-footer">
        <p>&copy; 2026 SSSTEX. All rights reserved.</p>
    </footer>

    <!-- External JS -->
    <script src="<?= base_url('assets/js/script.js') ?>?v=<?= time() ?>"></script>
</body>
</html>
