<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="../../assets/css/auth.css">
</head>
<body>
    <div class="form-container">
        <h1>Đăng ký</h1>
        <?php if(!empty($error)) echo "<div class='error-message'>" . htmlspecialchars($error) . "</div>"; ?>
        <form method="POST" action="/register">
            <input type="text" name="name" placeholder="Tên" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Đăng ký</button>
        </form>
        <a class="form-link" href="/login">Đã có tài khoản? Đăng nhập</a>
    </div>
</body>
</html>

