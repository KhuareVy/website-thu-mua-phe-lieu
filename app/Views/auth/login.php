<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="../../assets/css/auth.css">
</head>
<body>
    <div class="form-container">
        <h1>Đăng nhập</h1>
        <?php if(!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="/login">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Đăng nhập</button>
        </form>
        <a class="form-link" href="/register">Bạn chưa có tài khoản? Đăng ký</a>
    </div>
</body>
</html>
