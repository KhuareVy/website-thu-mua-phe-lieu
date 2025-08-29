  <footer class="footer" role="contentinfo" itemscope itemtype="https://schema.org/Organization">
    <div class="footer-logo">
      <img src="../../assets/images/mhk-logo-fit.png" 
           alt="Logo Minh Hải Khương" 
           width="200" 
           height="100"
           loading="lazy"
           itemprop="logo">
    </div>
    <div class="company-info">
      <h2 itemprop="name">CÔNG TY THU MUA PHẾ LIỆU MINH HẢI KHƯƠNG</h2>
      <span>
        <b>Hotline:</b> 
        <a href="tel:0971519789" itemprop="telephone">097.15.19.789</a> (Mr. Phong)
      </span>
      <span>
        <b>Email:</b> 
        <a href="mailto:phieulieuminhhaikhuong@gmail.com" itemprop="email">phieulieuminhhaikhuong@gmail.com</a>
      </span>
      <address itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
        <i class="fa-solid fa-location-dot" aria-hidden="true"></i> 
        <b>Địa chỉ 1:</b> 
        <span itemprop="Address-1">140 Lê Trọng Tấn, Phường Sơn Kỳ, Quận Tân Phú, Thành phố Hồ Chí Minh</span>
        <b>Địa chỉ 2:</b>
        <span itemprop="Address-2">345 Kinh Dương Vương, Phường An Lạc, Quận Bình Tân, Thành phố Hồ Chí Minh</span>
        <b>Địa chỉ 3:</b>
        <span itemprop="Address-3">277 Lê Văn Việt, Phường Hiệp Phú, Quận Thủ Đức, Thành phố Hồ Chí Minh</span>
      </address>
    </div>
    <nav class="category-list" aria-label="Danh mục sản phẩm">
      <h2>DANH MỤC THU MUA</h2>
      <ul>
        <li><a href="#phe-lieu-sat">Phế liệu Sắt</a></li>
        <li><a href="#phe-lieu-dong">Phế liệu Đồng</a></li>
        <li><a href="#phe-lieu-inox">Phế liệu Inox</a></li>
        <li><a href="#phe-lieu-nhom">Phế liệu Nhôm</a></li>
        <li><a href="#phe-lieu-thiec">Phế liệu Thiếc</a></li>
        <li><a href="#phe-lieu-chi">Phế liệu Chì</a></li>
        <li><a href="#phe-lieu-nhua">Phế liệu Nhựa</a></li>
        <li><a href="#phe-lieu-niken">Phế liệu Giấy</a></li>
      </ul>
    </nav>
  </footer>
  <style>
    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
    }
  </style>
  <script>
    window.addEventListener('load', function() {
      const fontAwesome = document.querySelector('link[href*="font-awesome"]');
      if (fontAwesome && fontAwesome.media === 'print') {
        fontAwesome.media = 'all';
      }
    });
  </script>
  <script>
  let lastScrollTop = 0;
  const header = document.querySelector("header");
  window.addEventListener("scroll", function () {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    if (scrollTop > lastScrollTop) {
      header.classList.add("hide-header");
    } else {
      header.classList.remove("hide-header");
    }
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
  });
  </script>
  <script src="../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/assets/js/modal_sell_form.js"></script>
</body>
</html>
