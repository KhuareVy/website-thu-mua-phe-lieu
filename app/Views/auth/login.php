<!-- CSS -->
<link rel="stylesheet" href="/assets/css/auth.css">
<div class="auth-wrapper">
    <div class="auth-content">
        <div class="form-container">
            <h1 class="form-title">Đăng nhập</h1>
            <?php if(!empty($error)):
                $err = $error;
                if (is_array($err)) {
                    if (empty($err)) {
                        $err = 'Có lỗi dữ liệu.';
                    } else {
                        $err = implode("<br>", array_map('htmlspecialchars', array_values($err)));
                    }
                } else {
                    $err = htmlspecialchars($err);
                }
            ?>
                <div class="error-message"><?= $err ?></div>
            <?php endif; ?>
            <form method="POST" action="/login" autocomplete="on">
                <!-- CSRF Token -->
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token ?? ($_SESSION['csrf_token'] ?? '')) ?>">
                <div class="input-group">
                    <label for="email"><i class="fa fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" placeholder="Nhập email" required autofocus>
                </div>
                <div class="input-group">
                    <label for="password"><i class="fa fa-lock"></i> Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                <button type="submit" class="btn-main">Đăng nhập</button>
            </form>
            <div class="form-bottom">
                <a class="form-link" href="/register">Bạn chưa có tài khoản? <b>Đăng ký</b></a>
            </div>
        </div>
    </div>
</div>
