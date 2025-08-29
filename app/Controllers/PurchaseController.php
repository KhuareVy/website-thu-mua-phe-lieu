<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\PurchaseRequestModel;
use App\Models\PurchaseRequestImageModel;

/**
 * Controller xử lý các yêu cầu liên quan đến bán phế liệu.
 */
class PurchaseController extends Controller
{
	public function submitSellRequest()
	{
		// Kiểm tra CSRF token
		if (empty($_POST['_csrf_token']) || !isset($_POST['_csrf_token'])) {
			http_response_code(419);
			exit(json_encode(['error' => 'CSRF token missing']));
		}
		$sessionToken = $_SESSION['_csrf_token'] ?? ($_SESSION['csrf_token'] ?? null);
		if ($_POST['_csrf_token'] !== $sessionToken) {
			http_response_code(419);
			exit(json_encode(['error' => 'CSRF token mismatch']));
		}


		// Kiểm tra bắt buộc phải có ít nhất 1 hình ảnh
		if (empty($_FILES['images']['name'][0])) {
			http_response_code(422);
			exit(json_encode(['error' => 'Vui lòng tải lên ít nhất 1 hình ảnh phế liệu.']));
		}

		// Lấy dữ liệu từ form
		$data = [
			'customer_name'    => $_POST['customer_name'] ?? '',
			'customer_phone'   => $_POST['customer_phone'] ?? '',
			'customer_address' => $_POST['customer_address'] ?? '',
			'notes'            => $_POST['notes'] ?? '',
			'status'           => 'pending',
			'user_id'          => $_SESSION['user_id'] ?? null,
			'request_code'     => 'REQ' . time(),
		];

		// Lưu yêu cầu bán phế liệu
		$purchaseRequest = new PurchaseRequestModel();
		$purchaseRequest->fill($data);
		$purchaseRequest->save();
		$requestId = $purchaseRequest->id;

		// Xử lý upload nhiều ảnh
		$uploadDir = __DIR__ . '/../../public/uploads/requests/';
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0777, true);
		}
		foreach ($_FILES['images']['name'] as $idx => $name) {
			$tmpName = $_FILES['images']['tmp_name'][$idx];
			$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
			$fileName = uniqid('img_') . '.' . $ext;
			$targetPath = $uploadDir . $fileName;

			$compressed = false;
			if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
				if ($ext === 'png') {
					$img = @imagecreatefrompng($tmpName);
					if ($img) {
						imagepng($img, $targetPath, 6); // PNG: nén mức 6
						imagedestroy($img);
						$compressed = true;
					}
				} else {
					$img = @imagecreatefromjpeg($tmpName);
					if ($img) {
						imagejpeg($img, $targetPath, 90); // JPEG: nén chất lượng 90%
						imagedestroy($img);
						$compressed = true;
					}
				}
			}
			if (!$compressed) {
				move_uploaded_file($tmpName, $targetPath);
			}

			$imgModel = new PurchaseRequestImageModel();
			$imgModel->fill([
				'purchase_request_id' => $requestId,
				'image_url' => '/uploads/requests/' . $fileName,
			]);
			$imgModel->save();
		}

		// Trả về view thông báo thành công
		return $this->render('purchase/success', ['request_code' => $data['request_code']]);
	}
}
