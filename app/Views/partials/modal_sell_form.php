<!-- Modal Bán Phế Liệu (Đồng bộ giao diện Home) -->
<div class="modal fade" id="sellScrapModal" tabindex="-1" aria-labelledby="sellScrapModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content" style="border-radius: 25px; box-shadow: 0 8px 40px rgba(255,102,0,0.15); border: 3px solid var(--orange, #ff6600);">
					<form id="sellScrapForm" enctype="multipart/form-data" method="post" novalidate style="font-family: 'Inter', sans-serif;">
						<input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($_SESSION['_csrf_token'] ?? ($_SESSION['csrf_token'] ?? '')) ?>">
				<div class="modal-header" style="background: linear-gradient(90deg, #ff6600, #26264c); border-top-left-radius: 22px; border-top-right-radius: 22px;">
					<h5 class="modal-title text-white fw-bold" id="sellScrapModalLabel" style="letter-spacing: 1px;">Gửi thông tin bán phế liệu</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
				</div>
				<div class="modal-body" style="background: var(--gray, #f8f9fa); border-radius: 0 0 22px 22px;">
					<div id="sellScrapAlert" class="alert d-none" role="alert"></div>
					<div class="row g-4">
						<div class="col-md-6">
							<label for="fullName" class="form-label fw-semibold" style="color: var(--dark-blue, #26264c);">Họ tên <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="fullName" name="customer_name" required style="border-radius: 12px; border: 2px solid var(--gray-light, #e9ecef);">
							<div class="invalid-feedback">Vui lòng nhập họ tên.</div>
						</div>
						<div class="col-md-6">
							<label for="phone" class="form-label fw-semibold" style="color: var(--dark-blue, #26264c);">Số điện thoại <span class="text-danger">*</span></label>
							<input type="tel" class="form-control" id="phone" name="customer_phone" pattern="^0\d{9,10}$" required style="border-radius: 12px; border: 2px solid var(--gray-light, #e9ecef);">
							<div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ (10-11 số, bắt đầu bằng 0).</div>
						</div>
						<div class="col-md-6">
							<label for="province" class="form-label fw-semibold" style="color: var(--dark-blue, #26264c);">Tỉnh/Thành <span class="text-danger">*</span></label>
							<select class="form-select" id="province" name="province" required style="border-radius: 12px; border: 2px solid var(--gray-light, #e9ecef);">
								<option value="">-- Chọn tỉnh/thành --</option>
								<option value="Hà Nội">Hà Nội</option>
								<option value="TP Hồ Chí Minh">TP Hồ Chí Minh</option>
							</select>
							<div class="invalid-feedback">Vui lòng chọn tỉnh/thành.</div>
						</div>
						<div class="col-md-6">
							<label for="address" class="form-label fw-semibold" style="color: var(--dark-blue, #26264c);">Địa chỉ <span class="text-danger">*</span></label>
							<input type="text" class="form-control" id="address" name="customer_address" required style="border-radius: 12px; border: 2px solid var(--gray-light, #e9ecef);">
							<div class="invalid-feedback">Vui lòng nhập địa chỉ.</div>
						</div>
						<div class="col-12">
							<label for="notes" class="form-label fw-semibold" style="color: var(--dark-blue, #26264c);">Ghi chú (tùy chọn)</label>
							<textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Thông tin thêm nếu có..." style="border-radius: 12px; border: 2px solid var(--gray-light, #e9ecef);"></textarea>
						</div>
						<div class="col-12">
							<label for="scrapImages" class="form-label fw-semibold" style="color: var(--dark-blue, #26264c);">Đính kèm hình ảnh phế liệu</label>
							<input class="form-control" type="file" id="scrapImages" name="images[]" accept="image/*" multiple style="border-radius: 12px; border: 2px solid var(--gray-light, #e9ecef);">
							<div class="form-text" style="color: var(--orange, #ff6600); font-weight: 500;">Có thể chọn nhiều hình ảnh.</div>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-between" style="background: var(--white, #fff); border-bottom-left-radius: 22px; border-bottom-right-radius: 22px; box-shadow: 0 -2px 12px rgba(255,102,0,0.07);">
					<button type="button" class="btn btn-secondary" id="cancelSellScrap" style="min-width: 120px; border-radius: 10px; font-weight: 600; background: var(--gray, #f8f9fa); color: var(--dark-blue, #26264c); border: 2px solid var(--gray-light, #e9ecef); transition: all 0.2s;">Hủy</button>
					<button type="submit" class="btn btn-success" style="min-width: 120px; border-radius: 10px; font-weight: 600; background-color: #26264c; border: none;">Xác nhận</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Nhúng CSS/JS riêng nếu cần -->


