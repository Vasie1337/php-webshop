<?php
class Order {
    private static $db = null;
    
    public function __construct() {
        if (self::$db === null) {
            try {
                self::$db = new PDO("mysql:host=localhost;dbname=webshop_db", "root", "");
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }
    }

    public function create(int $userId, int $productId, int $quantity, float $totalPrice): bool 
    {
        $stmt = self::$db->prepare("
            INSERT INTO orders (user_id, product_id, quantity, total_price)
            VALUES (:user_id, :product_id, :quantity, :total_price)
        ");

        return $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
            ':quantity' => $quantity,
            ':total_price' => $totalPrice
        ]);
    }

    public static function getOrdersByUser(int $userId): array {
        try {
            if (!self::$db) {
                self::$db = new PDO("mysql:host=localhost;dbname=webshop_db", "root", "");
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }

            $stmt = self::$db->prepare("
                SELECT o.*, p.name as product_name 
                FROM orders o
                JOIN products p ON o.product_id = p.product_id
                WHERE o.user_id = :user_id
                ORDER BY o.order_date DESC
            ");
            
            $stmt->execute([':user_id' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error retrieving orders: " . $e->getMessage());
            throw new Exception("Error retrieving orders: " . $e->getMessage());
        }
    }
}