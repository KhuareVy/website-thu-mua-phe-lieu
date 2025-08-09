<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <title>Đăng ký tài khoản</title>
</head>
<body>
    <h2>Đăng ký</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <p style="color:red"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></p>
    <?php endif; ?>

    <form method="POST" action="/website_thu_mua_phe_lieu/public/register">

        <label>Tên:</label><br />
        <input type="text" name="name" required /><br />

        <label>Email:</label><br />
        <input type="email" name="email" required /><br />

        <label>Mật khẩu:</label><br />
        <input type="password" name="password" required /><br />

        <label>Xác nhận mật khẩu:</label><br />
        <input type="password" name="password_confirm" required /><br /><br />

        <button type="submit">Đăng ký</button>
    </form>

    <p>Đã có tài khoản? <a href="/login">Đăng nhập ngay</a></p>
</body>
</html>
