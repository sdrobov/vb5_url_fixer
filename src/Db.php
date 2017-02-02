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
     * @param string $tablePrefix
     * @return Db
     */
    public static function getInstance($tablePrefix = null)
    {
        if (!static::$instance) {
            static::$instance = new static();
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
     * @param string $sql
     * @return \PDOStatement
     */
    public function query($sql)
    {
        $this->assertConnection();

        return $this->pdo->query($sql);
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

    protected function __construct($tablePrefix = null)
    {
        $this->tablePrefix = $tablePrefix;
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
