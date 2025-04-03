<?php
class Database {
    private static $pdo;

    public static function getConnection() {
        if (!self::$pdo) {
            $dsn = "mysql:host=127.0.0.1;dbname=ctf_app;charset=utf8mb4";
            $username = "root";
            $password = "";
            self::$pdo = new PDO($dsn, $username, $password);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }
}