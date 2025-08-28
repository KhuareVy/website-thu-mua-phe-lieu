<?php include __DIR__ . '/header.php'; ?>
<div class="admin-layout">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <main class="admin-content">
        <?= $content ?? '' ?>
    </main>
</div>
<?php include __DIR__ . '/footer.php'; ?>
