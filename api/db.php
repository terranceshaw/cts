<?php

include_once "db-config.php";

class DB {
    protected static $instance = null;

    protected function __construct() {}
    protected function __clone() {}

    public static function instance() {
        if (self::$instance === null) {
            $opt  = array(
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => FALSE,
            );
            self::$instance = new PDO("odbc:Driver={SQL Server};server=" . DB_HOST . ";database=" . DB_NAME, DB_USER, DB_PASS, $opt);
        }
        return self::$instance;
    }

    public static function __callStatic($method, $args) {
        return call_user_func_array(array(self::instance(), $method), $args);
    }

    public static function run($sql, $args = []) {
        if (!$args) {
             return self::instance()->query($sql);
        }
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    public static function connectionStatus() {
        if (self::$instance) {
            echo "Connection established.";
        } else {
            echo "Error connecting to the database.";
        }
    }
}

?>