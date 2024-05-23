<?php
class Database {
    private static $instance = null;

    private $connection = null;

    protected function __construct() {
        $this->connection = new \PDO(
            "mysql:host=localhost;dbname=pharmacy;charset=utf8",
            'root',
            '',
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_STRINGIFY_FETCHES => false,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
            );
    }

    protected function __clone() {
    }

    public function __wakeup() {
    }

    public static function get_instance(): Database {
        if (null === self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public static function connection(): \PDO {
        return static::get_instance()->connection;
    }

    public static function prepare($statement): \PDOStatement {
        return static::connection()->prepare($statement);
    }

    public static function last_insert_id(): int {
        return intval(static::connection()->lastInsertId());
    }
}
