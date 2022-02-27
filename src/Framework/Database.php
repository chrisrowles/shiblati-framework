<?php

namespace Shiblati\Framework;

use PDO;
use PDOException;
use Shiblati\Framework\Config\DatabaseConfig;

/**
 * Class Database
 */
class Database
{
    /** @var PDO  */
    private PDO $dbh;

    /** @var mixed */
    private mixed $stmt;

    /**
     * Database constructor.
     *
     * @throws PDOException if the attempt to connect to the requested database fails.
     */
    public function __construct(DatabaseConfig $config)
    {
        $dsn = 'mysql:host=' . $config->host . ';dbname=' . $config->database;

        $this->dbh = new PDO($dsn,  $config->username,  $config->password, [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    /**
     * @param $query
     */
    public function query($query)
    {
        $this->stmt = $this->dbh->prepare($query);
    }

    /**
     * @param $param
     * @param $value
     * @param null $type
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            $type = match (true) {
                is_int($value) => PDO::PARAM_INT,
                is_bool($value) => PDO::PARAM_BOOL,
                is_null($value) => PDO::PARAM_NULL,
                default => PDO::PARAM_STR,
            };
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * @return mixed
     */
    public function execute(): mixed
    {
        return $this->stmt->execute();
    }

    /**
     * @return mixed
     */
    public function all(): mixed
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return mixed
     */
    public function single(): mixed
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @return int|bool
     */
    public function count(): int|bool
    {
        return $this->stmt->rowCount();
    }

    /**
     * @return string|bool
     */
    public function lastInsertID(): string|bool
    {
        return $this->dbh->lastInsertId();
    }

    /**
     * @return bool
     */
    public function beginTransaction(): bool
    {
        return $this->dbh->beginTransaction();
    }

    /**
     * @return bool
     */
    public function endTransaction(): bool
    {
        return $this->dbh->commit();
    }

    /**
     * @return bool
     */
    public function cancelTransaction(): bool
    {
        return $this->dbh->rollBack();
    }
}