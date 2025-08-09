<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <p style="color:red"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <?php if (!empty($_SESSION['success'])): ?>
        <p style="color:green"><?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?></p>
    <?php endif; ?>
        
    <form method="POST" action="/website_thu_mua_phe_lieu/public/login">
        <label>Email:</label><br />
        <input type="email" name="email" required /><br />

        <label>Mật khẩu:</label><br />
        <input type="password" name="password" required /><br /><br />

        <button type="submit">Đăng nhập</button>
    </form>

    <p>Chưa có tài khoản? <a href="/register">Đăng ký ngay</a></p>
</body>
</html>
