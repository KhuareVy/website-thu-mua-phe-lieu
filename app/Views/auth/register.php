<?php $title = 'Đăng ký'; ?>
<form method="post" action="/register">
    <h2>Đăng ký</h2>
    <?php if(!empty($message)) echo "<p>{$message}</p>"; ?>
    <input type="text" name="name" placeholder="Tên người dùng" required>
    <input type="password" name="password" placeholder="Mật khẩu" required>
    <button type="submit">Đăng ký</button>
    <p>Đã có tài khoản? <a href="/login">Đăng nhập</a></p>
</form>
