<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                $host = $_ENV['DB_HOST'];
                $dbname = $_ENV['DB_DATABASE'];
                $user = $_ENV['DB_USER'];
                $pass = $_ENV['DB_PASSWORD'];

                $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
                
                self::$instance = new PDO($dsn, $user, $pass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            } catch (PDOException $e) {
                die("Erro de conexão com o banco de dados: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}