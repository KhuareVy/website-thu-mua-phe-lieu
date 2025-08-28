# Website Thu Mua Phế Liệu Minh Hải Khương

Dự án này là một hệ thống website MVC PHP thuần, phục vụ cho công ty thu mua phế liệu Minh Hải Khương. Hệ thống hỗ trợ quản lý người dùng, đăng nhập/đăng ký, phân quyền admin/khách hàng, đăng bài bán/mua phế liệu, và các chức năng quản trị cơ bản.

## Tính năng chính

- Đăng ký, đăng nhập, đăng xuất cho khách hàng và admin
- Phân quyền truy cập: admin (dashboard), khách hàng (user page)
- Đăng bài bán/mua phế liệu
- Quản lý thông tin người dùng
- CSRF protection, session bảo mật
- Giao diện responsive, hỗ trợ layout động

## Cấu trúc thư mục

- `app/` - Chứa code chính (Controllers, Models, Views, Core, Middleware)
- `public/` - Thư mục public, chứa index.php và tài nguyên tĩnh (css, js, images)
- `configs/` - Cấu hình ứng dụng và database
- `routes/` - Định nghĩa route web
- `vendor/` - Thư viện cài qua composer

## Yêu cầu hệ thống

- PHP >= 8.1
- MySQL/MariaDB
- Composer
- XAMPP/Laragon hoặc server PHP bất kỳ

## Cài đặt

1. Clone project về máy:
   ```sh
   git clone https://github.com/your-username/website-thu-mua-phe-lieu.git
   ```
2. Cài đặt composer:
   ```sh
   composer install
   ```
3. Cấu hình database trong `configs/database.php`.
4. Import file SQL (nếu có) để tạo bảng dữ liệu.
5. Chạy server:
   ```sh
   cd public
   php -S localhost:8000
   ```
6. Truy cập http://localhost:8000 trên trình duyệt.

## Tài khoản mẫu

- Admin:
  - Email: admin@thumuaphelieu.com
  - Mật khẩu: admin
- Khách hàng:
  - Đăng ký trực tiếp trên website

## Ghi chú

- Đảm bảo PHP bật các extension: pdo, pdo_mysql, mbstring
- Để bảo mật, không commit file `.env` hoặc thông tin nhạy cảm lên git
- Nếu gặp lỗi session hoặc CSRF, kiểm tra lại cấu hình cookie và domain

## Đóng góp

Mọi đóng góp, báo lỗi hoặc đề xuất vui lòng tạo issue hoặc pull request trên Github.

---

© 2025 Minh Hải Khương. All rights reserved.
