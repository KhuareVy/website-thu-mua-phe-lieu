<?php 
use App\Helpers\Session;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="fas fa-recycle"></i> Thu Mua Phế Liệu
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Trang Chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/about">Giới Thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/contact">Liên Hệ</a>
                </li>
            </ul>
            
            <ul class="navbar-nav">
                <?php if (Session::has('user_id')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i> <?= Session::get('user_name') ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/dashboard">Bảng Điều Khiển</a></li>
                            <li><a class="dropdown-item" href="/orders">Đơn Hàng</a></li>
                            <li><a class="dropdown-item" href="/profile">Thông Tin</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout">Đăng Xuất</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login">Đăng Nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register">Đăng Ký</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>