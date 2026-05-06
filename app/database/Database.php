<?php

namespace app\database;

use PDO;

class Database {
    public static function connect() {
        static $pdo;

        if ($pdo === null) {
            $dsn = "mysql:host={$_ENV['DB_HOST']};
            dbname={$_ENV['DB_NAME']};
            charset={$_ENV['DB_CHARSET']}";

            $pdo = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        return $pdo;
    }
}