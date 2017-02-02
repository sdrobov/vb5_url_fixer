<?php

namespace Vb5UrlFixer;

abstract class BaseCollection implements \Iterator
{
    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var int
     */
    protected $currentOffset;

    /**
     * @var string
     */
    protected $pkName;

    protected $currentModel;

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var string
     */
    protected $where;

    /**
     * @return static
     */
    public static function me()
    {
        return new static();
    }

    public function __construct($where = null, $limit = 0, $offset = 0)
    {
        $this->where = $where;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->currentOffset = 0;
        $this->total = 0;
        $this->currentModel = null;
    }

    public function where($where)
    {
        $this->where = $where;

        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function getByPk($id)
    {
        $id = is_numeric($id) ? $id : "'{$id}'";
        $query = "SELECT * FROM {$this->getTableName()} WHERE {$this->pkName} = $id";

        return $this->createModel(Db::getInstance()->query($query)->fetch());
    }

    public function getOne($where)
    {
        $query = "SELECT * FROM {$this->getTableName()} WHERE {$where}";

        return $this->createModel(Db::getInstance()->query($query)->fetch());
    }

    public function current()
    {
        return $this->currentModel;
    }

    public function next()
    {
        $this->currentOffset++;
        $this->fetch();
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->currentOffset;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->currentOffset + 1 < $this->total;
    }

    public function rewind()
    {
        $this->currentOffset = 0;

        $where = $this->where ?: '';
        $countQuery = "SELECT COUNT(*) FROM {$this->getTableName()} {$where}";
        $this->total = Db::getInstance()->query($countQuery)->fetchColumn();
        $this->fetch();
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return Db::getInstance()->getTablePrefix()
            ? Db::getInstance()->getTablePrefix() . '_' . $this->tableName
            : $this->tableName;
    }

    protected function fetch()
    {
        $where = $this->where ?: '';
        $query = "SELECT * FROM {$this->getTableName()} {$where} LIMIT 1 OFFSET {$this->currentOffset}";
        $this->currentModel = $this->createModel(Db::getInstance()->query($query)->fetch());
    }

    abstract protected function createModel(array $data);
}
