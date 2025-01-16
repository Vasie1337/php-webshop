<?php

class Product
{
    private static $db;

    public static function init() {
        if (!self::$db) {
            try {
                self::$db = new PDO("mysql:host=localhost;dbname=webshop_db", "root", "");
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }
    }

    public static function getAllProducts() {
        self::init();
        $stmt = self::$db->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getProductById($productId) {
        self::init();
        $stmt = self::$db->prepare("SELECT * FROM products WHERE product_id = :product_id");
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}