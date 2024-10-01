<?php

namespace core;

use PDO;
use PDOException;

class Database
{
    private static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo === null) {
            try {
                // Use __DIR__ to get the directory of the current file
                $config = require_once __DIR__ . '/../config/database.php';

                $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbname'];
                self::$pdo = new PDO($dsn, $config['user'], $config['password']);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
