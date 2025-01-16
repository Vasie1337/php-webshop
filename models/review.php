<?php

class Review
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

    public static function getAllReviews() {
        $stmt = self::$db->prepare("SELECT * FROM reviews");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function placeReview($stars, $comment, $username) {
        $stmt = self::$db->prepare("INSERT INTO reviews (stars, comment, username) VALUES (:stars, :comment, :username)");
        $stmt->bindParam(":stars", $stars);
        $stmt->bindParam(":comment", $comment);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
    }
}