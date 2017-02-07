<?php

namespace Vb5UrlFixer;

class Db
{
    /**
     * @var Db
     */
    protected static $instance;

    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var string
     */
    protected $tablePrefix;

    /**
     * @var bool
     */
    protected $dryUpdate;

    /**
     * @param string $tablePrefix
     * @param bool $dryUpdate
     * @return Db
     */
    public static function getInstance($tablePrefix = null, $dryUpdate = true)
    {
        if (!static::$instance) {
            static::$instance = new static($tablePrefix, $dryUpdate);
        }

        return static::$instance;
    }

    /**
     * @param string $host
     * @param string $dbname
     * @param string $user
     * @param string $password
     * @return bool
     */
    public function connect($host, $dbname, $user, $password)
    {
        try {
            $this->pdo = new \PDO("mysql:dbname=$dbname;host=$host", $user, $password);
        } catch (\PDOException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param string $query
     * @param array $params
     * @return \PDOStatement
     */
    public function query($query, $params = [])
    {
        $this->assertConnection();
        if ($this->dryUpdate && strpos($query, 'UPDATE') === 0) {
            $keys = array_keys($params);
            $values = array_map(function($value) {
                return is_numeric($value) ? $value : "'{$value}'";
            }, array_values($params));

            echo str_replace($keys, $values, $query) . PHP_EOL;

            return null;
        }

        $statement = $this->pdo->prepare($query);
        $statement->execute($params ?: null);

        return $statement;
    }

    /**
     * @return bool
     */
    public function begin()
    {
        $this->assertConnection();

        return $this->pdo->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commit()
    {
        $this->assertConnection();

        return $this->pdo->commit();
    }

    /**
     * @return bool
     */
    public function rollback()
    {
        $this->assertConnection();

        return $this->pdo->rollBack();
    }

    /**
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    /**
     * @throws \Exception
     */
    protected function assertConnection()
    {
        if (!$this->pdo) {
            throw new \Exception('Connect to db first');
        }
    }

    /**
     * Db constructor.
     *
     * @param string $tablePrefix
     * @param bool $dryUpdate
     */
    protected function __construct($tablePrefix = null, $dryUpdate = true)
    {
        $this->tablePrefix = $tablePrefix;
        $this->dryUpdate = $dryUpdate;
    }

    protected function __clone()
    {
        throw new \Exception('Cant clone singletone');
    }

    protected function __wakeup()
    {
        throw new \Exception('Cant wakeup singletone');
    }
}
