<?php
// Source: https://phpdelusions.net/pdo/pdo_wrapper
// Database class to do the heavy lifting of all those sweet, sweet datas.
// Blatant rip from the source URL
require_once "db-config.php";

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
            $dsn = 'odbc:Driver={SQL Server};Server='.DB_HOST.';Database='.DB_NAME.';';
            self::$instance = new PDO($dsn, DB_USER, DB_PASS, $opt);
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
}

?>