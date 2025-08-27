<!-- CSS -->
<link rel="stylesheet" href="/assets/css/auth.css">
<div class="auth-wrapper">
    <div class="auth-content">
        <div class="form-container">
            <h1 class="form-title">Đăng ký</h1>
            <?php if(!empty($error)) {
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
                echo "<div class='error-message'>" . $err . "</div>";
            } ?>
            <form method="POST" action="/register" autocomplete="on">
                <!-- CSRF Token -->
                <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? ($_SESSION['csrf_token'] ?? '')); ?>">
                <div class="input-group">
                    <label for="name"><i class="fa fa-user"></i> Họ và tên</label>
                    <input type="text" id="name" name="name" placeholder="Nhập họ và tên" required>
                </div>
                <div class="input-group">
                    <label for="email"><i class="fa fa-envelope"></i> Email</label>
                    <input type="email" id="email" name="email" placeholder="Nhập email" required>
                </div>
                <div class="input-group">
                    <label for="phone"><i class="fa fa-phone"></i> Số điện thoại</label>
                    <input type="text" id="phone" name="phone" placeholder="Nhập số điện thoại" required pattern="[0-9]{10,15}" title="Số điện thoại hợp lệ từ 10-15 số">
                </div>
                <div class="input-group">
                    <label for="password"><i class="fa fa-lock"></i> Mật khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>
                <button type="submit" class="btn-main">Đăng ký</button>
            </form>
            <div class="form-bottom">
                <a class="form-link" href="/login">Đã có tài khoản? <b>Đăng nhập</b></a>
            </div>
        </div>
    </div>
</div>

