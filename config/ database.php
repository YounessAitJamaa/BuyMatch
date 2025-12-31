<?php

    class Database {
        private static ?PDO $instance = null;

        private function __construct() {}

        public static function getInstance(): PDO 
        {
            if (self::$instance === null) {
                $host = 'localhost';
                $db = 'BuyMatch';
                $user = 'root';
                $pass = 'Youcode@2025';

                $dsn = "mysql:host=$host;dbname=$db;";

                try {
                    self::$instance = new PDO($dsn, $user, $pass);
                    self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    die('Database Connetion error : ' . $e->getMessage());
                }
            }
            
            return self::$instance;
        }
    }

?>