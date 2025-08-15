<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <style>
        body {
            background: #f4f6fb;
            font-family: 'Segoe UI', Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background: #fff;
            padding: 32px 28px 24px 28px;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            min-width: 340px;
        }
        .login-container h1 {
            margin-bottom: 18px;
            font-size: 2rem;
            color: #2d3748;
            text-align: center;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .login-container input[type="email"],
        .login-container input[type="password"] {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 1rem;
            background: #f9fafb;
            transition: border 0.2s;
        }
        .login-container input:focus {
            border-color: #3182ce;
            outline: none;
            background: #fff;
        }
        .login-container button {
            padding: 10px 0;
            background: linear-gradient(90deg, #3182ce 0%, #63b3ed 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .login-container button:hover {
            background: linear-gradient(90deg, #2563eb 0%, #4299e1 100%);
        }
        .login-container .register-link {
            display: block;
            margin-top: 18px;
            text-align: center;
            color: #3182ce;
            text-decoration: none;
            font-size: 0.98rem;
            transition: color 0.2s;
        }
        .login-container .register-link:hover {
            color: #2563eb;
            text-decoration: underline;
        }
        .login-container .error-message {
            color: #e53e3e;
            background: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 5px;
            padding: 8px 12px;
            margin-bottom: 10px;
            text-align: center;
            font-size: 0.98rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Đăng nhập</h1>
        <?php if(!empty($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="/login">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Đăng nhập</button>
        </form>
        <a class="register-link" href="/register">Bạn chưa có tài khoản? Đăng ký</a>
    </div>
</body>
</html>
