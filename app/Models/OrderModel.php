<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class OrderModel
{
    protected PDO $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    public function createOrder($user_id, $total_price)
    {
        $stmt = $this->db->prepare("INSERT INTO orders (user_id, total_price, status, created_at, updated_at) VALUES (?, ?, 'pending', NOW(), NOW())");
        $stmt->execute([$user_id, $total_price]);
        return $this->db->lastInsertId();
    }
    public function addOrderItem($order_id, $type, $name, $qty, $price, $total)
    {
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, scrap_type, scrap_name, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$order_id, $type, $name, $qty, $price, $total]);
    }
}
        