<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class User
{
    private static $username;
    private static $password;
    private static $email;
    private static $firstname;
    private static $lastname;
    private static $user_id;
    private static $db;

    public static function init() {
        if (!self::$db) {
            try {
                self::$db = new PDO("mysql:host=localhost;dbname=webshop_db", "root", "");
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                if (isset($_SESSION['user_id'])) {
                    self::load($_SESSION['user_id']);
                }
            } catch(PDOException $e) {
                throw new Exception("Connection failed: " . $e->getMessage());
            }
        }
    }

    public static function login($username, $password) {
        self::init();
        try {
            $stmt = self::$db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userData && password_verify($password, $userData['password'])) {
                self::$username = $userData['username'];
                self::$password = $userData['password'];
                self::$email = $userData['email'];
                self::$firstname = $userData['firstname'];
                self::$lastname = $userData['lastname'];
                self::$user_id = $userData['user_id'];

                $_SESSION['user_id'] = self::$user_id;
                $_SESSION['username'] = self::$username;
                $_SESSION['firstname'] = self::$firstname;
                $_SESSION['lastname'] = self::$lastname;
                $_SESSION['email'] = self::$email;
                
                $_SESSION['last_activity'] = time();
                $_SESSION['expire_time'] = 7200;

                return true;
            }

            return false;
        } catch(PDOException $e) {
            throw new Exception("Login failed: " . $e->getMessage());
        }
    }

    public static function register($username, $password, $email, $firstname, $lastname) {
        self::init();
        try {
            self::initialize($username, $password, $email, $firstname, $lastname);
            return self::save();
        } catch(Exception $e) {
            return false;
        }
    }

    public static function logout() {
        self::$username = null;
        self::$password = null;
        self::$email = null;
        self::$firstname = null;
        self::$lastname = null;
        self::$user_id = null;

        session_unset();
        session_destroy();
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-3600, '/');
        }
    }

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !self::isSessionExpired();
    }

    private static function isSessionExpired() {
        if (!isset($_SESSION['last_activity']) || !isset($_SESSION['expire_time'])) {
            return true;
        }

        if (time() - $_SESSION['last_activity'] > $_SESSION['expire_time']) {
            self::logout();
            return true;
        }

        $_SESSION['last_activity'] = time();
        return false;
    }

    public static function initialize($username, $password, $email, $firstname, $lastname) {
        self::$username = $username;
        self::$password = $password;
        self::$email = $email;
        self::$firstname = $firstname;
        self::$lastname = $lastname;
    }

    public static function save() {
        try {
            $duplicateCheck = self::$db->prepare(
                "SELECT user_id FROM users WHERE (username = :username OR email = :email)" . 
                (self::$user_id ? " AND user_id != :user_id" : "")
            );

            $duplicateCheck->bindParam(':username', self::$username);
            $duplicateCheck->bindParam(':email', self::$email);
            if (self::$user_id) {
                $duplicateCheck->bindParam(':user_id', self::$user_id);
            }

            $duplicateCheck->execute();

            if ($duplicateCheck->rowCount() > 0) {
                $existingUser = self::$db->prepare(
                    "SELECT username, email FROM users WHERE username = :username OR email = :email"
                );
                $existingUser->bindParam(':username', self::$username);
                $existingUser->bindParam(':email', self::$email);
                $existingUser->execute();
                $duplicate = $existingUser->fetch(PDO::FETCH_ASSOC);

                if ($duplicate['username'] === self::$username) {
                    throw new Exception("Username already exists");
                }
                if ($duplicate['email'] === self::$email) {
                    throw new Exception("Email address already exists");
                }
            }

            if (self::$user_id) {
                $stmt = self::$db->prepare("UPDATE users SET 
                    username = :username,
                    password = :password,
                    email = :email,
                    firstname = :firstname,
                    lastname = :lastname
                    WHERE user_id = :user_id");

                $stmt->bindParam(':user_id', self::$user_id);
            } else {
                $stmt = self::$db->prepare("INSERT INTO users 
                    (username, password, email, firstname, lastname) 
                    VALUES (:username, :password, :email, :firstname, :lastname)");
            }

            $hashedPassword = password_hash(self::$password, PASSWORD_DEFAULT);

            $stmt->bindParam(':username', self::$username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':email', self::$email);
            $stmt->bindParam(':firstname', self::$firstname);
            $stmt->bindParam(':lastname', self::$lastname);

            $stmt->execute();

            if (!self::$user_id) {
                self::$user_id = self::$db->lastInsertId();
            }

            return true;
        } catch(PDOException $e) {
            throw new Exception("Save failed: " . $e->getMessage());
        }
    }

    public static function load($user_id) {
        self::init();
        try {
            $stmt = self::$db->prepare("SELECT * FROM users WHERE user_id = :user_id");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
    
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($userData) {
                self::$username = $userData['username'];
                self::$password = $userData['password'];
                self::$email = $userData['email'];
                self::$firstname = $userData['firstname'];
                self::$lastname = $userData['lastname'];
                self::$user_id = $userData['user_id'];
                return true;
            }
    
            return false;
        } catch(PDOException $e) {
            throw new Exception("Load failed: " . $e->getMessage());
        }
    }

    public static function loadByUsername($username) {
        self::init();
        try {
            $stmt = self::$db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userData) {
                self::$username = $userData['username'];
                self::$password = $userData['password'];
                self::$email = $userData['email'];
                self::$firstname = $userData['firstname'];
                self::$lastname = $userData['lastname'];
                self::$user_id = $userData['user_id'];
                return true;
            }

            return false;
        } catch(PDOException $e) {
            throw new Exception("Load failed: " . $e->getMessage());
        }
    }

    public static function getUserID() { return self::$user_id; }
    public static function getUsername() { return self::$username; }
    public static function getEmail() { return self::$email; }
    public static function getFirstname() { return self::$firstname; }
    public static function getLastname() { return self::$lastname; }

    public static function setUsername($username) { self::$username = $username; }
    public static function setPassword($password) { self::$password = $password; }
    public static function setEmail($email) { self::$email = $email; }
    public static function setFirstname($firstname) { self::$firstname = $firstname; }
    public static function setLastname($lastname) { self::$lastname = $lastname; }
}
?>
