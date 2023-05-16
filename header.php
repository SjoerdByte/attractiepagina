<header>
    <div class="container header">
        <img src="/img/logo-big-v4.png" alt="logo" class="logo" />
        <h1>Attracties</h1>
        <nav>
            <a href="/index.php">Attracties</a> |
            <a href="/admin/index.php">Admin</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                | <a href="<?php echo $base_url; ?>/admin/logout.php">Uitloggen</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
