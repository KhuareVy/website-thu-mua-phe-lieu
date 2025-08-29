<div class="alert alert-success mt-4">
    <h4>Gửi yêu cầu thành công!</h4>
    <p>Cảm ơn bạn đã gửi thông tin bán phế liệu. Chúng tôi sẽ liên hệ lại sớm nhất.</p>
    <?php if (!empty($request_code)): ?>
        <p><strong>Mã yêu cầu:</strong> <?= htmlspecialchars($request_code) ?></p>
    <?php endif; ?>
    <a href="/" class="btn btn-primary mt-3">Về trang chủ</a>
</div>