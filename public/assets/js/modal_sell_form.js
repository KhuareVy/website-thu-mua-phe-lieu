// JS cho modal bán phế liệu

document.addEventListener('DOMContentLoaded', function () {
  // Mở modal khi nhấn nút "Bán phế liệu"
  const sellBtn = document.querySelector(
    '.float-btn[href="/submit-sell-request"]'
  );
  if (sellBtn) {
    sellBtn.setAttribute('data-bs-toggle', 'modal');
    sellBtn.setAttribute('data-bs-target', '#sellScrapModal');
    sellBtn.addEventListener('click', function (e) {
      e.preventDefault();
    });
  }

  const form = document.getElementById('sellScrapForm');
  const alertBox = document.getElementById('sellScrapAlert');
  const modalEl = document.getElementById('sellScrapModal');
  let bsModal = null;
  if (typeof bootstrap !== 'undefined') {
    bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
  }

  // Xác nhận gửi form bằng AJAX
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    e.stopPropagation();
    alertBox.classList.add('d-none');
    alertBox.classList.remove('alert-success', 'alert-danger', 'alert-warning');

    let isValid = true;

    // Kiểm tra họ tên
    const fullName = document.getElementById('fullName');
    if (!fullName.value.trim()) {
      fullName.classList.add('is-invalid');
      isValid = false;
    } else {
      fullName.classList.remove('is-invalid');
    }

    // Kiểm tra số điện thoại
    const phone = document.getElementById('phone');
    const phonePattern = /^0\d{9,10}$/;
    if (!phonePattern.test(phone.value.trim())) {
      phone.classList.add('is-invalid');
      isValid = false;
    } else {
      phone.classList.remove('is-invalid');
    }

    // Kiểm tra địa chỉ
    const address = document.getElementById('address');
    if (!address.value.trim()) {
      address.classList.add('is-invalid');
      isValid = false;
    } else {
      address.classList.remove('is-invalid');
    }

    // Kiểm tra bắt buộc phải có ít nhất 1 hình ảnh
    const imagesInput = document.getElementById('scrapImages');
    if (!imagesInput.files || imagesInput.files.length === 0) {
      isValid = false;
      imagesInput.classList.add('is-invalid');
    } else {
      imagesInput.classList.remove('is-invalid');
    }

    if (!isValid) {
      alertBox.textContent = 'Vui lòng điền đầy đủ thông tin';
      alertBox.classList.remove('d-none');
      alertBox.classList.add('alert-warning');
      return;
    }

    // Gửi form bằng AJAX
    const formData = new FormData(form);
    fetch('/submit-sell-request', {
      method: 'POST',
      body: formData,
    })
      .then(async (res) => {
        if (res.ok) {
          // Nếu trả về HTML (view success), hiển thị thông báo thành công
          alertBox.textContent =
            'Gửi thông tin thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.';
          alertBox.classList.remove('d-none', 'alert-warning', 'alert-danger');
          alertBox.classList.add('alert-success');
          setTimeout(function () {
            if (bsModal) bsModal.hide();
            form.reset();
            form.querySelectorAll('.is-invalid').forEach(function (el) {
              el.classList.remove('is-invalid');
            });
            alertBox.classList.add('d-none');
          }, 1800);
        } else {
          // Nếu trả về lỗi (JSON)
          let data;
          try {
            data = await res.json();
          } catch {
            data = {};
          }
          alertBox.textContent =
            data.error || 'Đã xảy ra lỗi. Vui lòng thử lại.';
          alertBox.classList.remove('d-none', 'alert-success', 'alert-warning');
          alertBox.classList.add('alert-danger');
        }
      })
      .catch(() => {
        alertBox.textContent =
          'Không thể gửi yêu cầu. Vui lòng kiểm tra kết nối mạng.';
        alertBox.classList.remove('d-none', 'alert-success', 'alert-warning');
        alertBox.classList.add('alert-danger');
      });
  });

  // Hủy form: đóng modal ngay lập tức, reset form, không cần delay
  document
    .getElementById('cancelSellScrap')
    .addEventListener('click', function () {
      if (bsModal) bsModal.hide();
      form.reset();
      form.querySelectorAll('.is-invalid').forEach(function (el) {
        el.classList.remove('is-invalid');
      });
      alertBox.textContent = 'Bạn đã hủy gửi thông tin bán phế liệu.';
      alertBox.classList.remove('d-none', 'alert-success', 'alert-warning');
      alertBox.classList.add('alert-danger');
      setTimeout(function () {
        alertBox.classList.add('d-none');
      }, 1200);
    });
});
