<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Cart 
{
    private static $items = [];
    private static $total = 0;

    private function __construct() {}

    public static function init() {
        if (isset($_SESSION['cart_items'])) {
            self::$items = $_SESSION['cart_items'];
            self::calculateTotal();
        }
    }

    public static function addItem($productId, $productName, $price, $quantity = 1) {
        if ($productId <= 0 || $price < 0 || $quantity <= 0) {
            return false;
        }

        if (isset(self::$items[$productId])) {
            self::$items[$productId]['quantity'] += $quantity;
        } else {
            self::$items[$productId] = [
                'product_id' => $productId,
                'name' => $productName,
                'price' => $price,
                'quantity' => $quantity
            ];
        }

        self::saveCart();
        self::calculateTotal();
        return true;
    }

    public static function removeItem($productId) {
        if (isset(self::$items[$productId])) {
            unset(self::$items[$productId]);
            self::saveCart();
            self::calculateTotal();
            return true;
        }
        return false;
    }

    public static function updateQuantity($productId, $quantity) {
        if ($quantity <= 0) {
            return self::removeItem($productId);
        }

        if (isset(self::$items[$productId])) {
            self::$items[$productId]['quantity'] = $quantity;
            self::saveCart();
            self::calculateTotal();
            return true;
        }
        return false;
    }

    public static function getItems() {
        return self::$items;
    }

    public static function getTotal() {
        return self::$total;
    }

    public static function getItemCount() {
        $count = 0;
        foreach (self::$items as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    public static function clear() {
        self::$items = [];
        self::$total = 0;
        self::saveCart();
    }

    private static function calculateTotal() {
        self::$total = 0;
        foreach (self::$items as $item) {
            self::$total += $item['price'] * $item['quantity'];
        }
    }

    private static function saveCart() {
        $_SESSION['cart_items'] = self::$items;
    }
}