<style>
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
    }
</style>
<style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background: #f5f7fa;
    }
    .register-container {
        max-width: 350px;
        margin: 60px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        padding: 32px 28px 24px 28px;
    }
    .register-container h1 {
        text-align: center;
        margin-bottom: 24px;
        color: #2d3748;
        font-size: 2rem;
        font-weight: 600;
    }
    .register-container form input[type="text"],
    .register-container form input[type="email"],
    .register-container form input[type="password"] {
        width: 100%;
        padding: 10px 12px;
        margin-bottom: 16px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 1rem;
        background: #f9fafb;
        transition: border-color 0.2s;
    }
    .register-container form input:focus {
        border-color: #3182ce;
        outline: none;
        background: #fff;
    }
    .register-container button[type="submit"] {
        width: 100%;
        padding: 10px 0;
        background: linear-gradient(90deg, #3182ce 0%, #63b3ed 100%);
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 1.1rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
        margin-bottom: 8px;
    }
    .register-container button[type="submit"]:hover {
        background: linear-gradient(90deg, #2563eb 0%, #4299e1 100%);
    }
    .register-container .login-link {
        display: block;
        text-align: center;
        margin-top: 12px;
        color: #3182ce;
        text-decoration: none;
        font-size: 0.98rem;
        transition: color 0.2s;
    }
    .register-container .login-link:hover {
        color: #2563eb;
        text-decoration: underline;
    }
    .register-container .error-message {
        color: #e53e3e;
        background: #fff5f5;
        border: 1px solid #fed7d7;
        border-radius: 5px;
        padding: 8px 12px;
        margin-bottom: 16px;
        font-size: 0.97rem;
        text-align: center;
    }
</style>

<div class="register-container">
    <h1>Đăng ký</h1>
    <?php if(!empty($error)) echo "<div class='error-message'>$error</div>"; ?>
    <form method="POST" action="/register">
        <input type="text" name="name" placeholder="Tên" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        <button type="submit">Đăng ký</button>
    </form>
    <a class="login-link" href="/login">Đã có tài khoản? Đăng nhập</a>
</div>
