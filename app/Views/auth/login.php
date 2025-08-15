<?php $title = 'Đăng nhập'; ?>
<form method="post" action="/login">
    <h2>Đăng nhập</h2>
    <?php if(!empty($message)) echo "<p>{$message}</p>"; ?>
    <input type="text" name="name" placeholder="Tên người dùng" required>
    <input type="password" name="password" placeholder="Mật khẩu" required>
    <button type="submit">Đăng nhập</button>
    <p>Chưa có tài khoản? <a href="/register">Đăng ký</a></p>
</form>
