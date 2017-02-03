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
     * @var array
     */
    protected $whereParams;

    /**
     * @return static
     */
    public static function me()
    {
        return new static();
    }

    public function __construct($where = null, $limit = 0, $offset = 0)
    {
        $this->parseWhere($where);
        $this->limit = $limit;
        $this->offset = $offset;
        $this->currentOffset = 0;
        $this->total = 0;
        $this->currentModel = null;
    }

    public function where($where)
    {
        $this->parseWhere($where);

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
        list($whereString, $whereParams) = $this->parseWhere([$this->pkName => $id], true);
        $query = "SELECT * FROM {$this->getTableName()} {$whereString}";

        return $this->createModel(Db::getInstance()->query($query, $whereParams)->fetch());
    }

    public function getOne($params = [])
    {
        list($where, $whereParams) = $this->parseWhere($params, true);
        $query = "SELECT * FROM {$this->getTableName()} {$where} LIMIT 1 OFFSET 0";

        return $this->createModel(Db::getInstance()->query($query, $whereParams)->fetch());
    }

    public function save(BaseModel $model)
    {
        if (!$model->isChanged()) {
            return $model;
        }

        list($queryPart, $queryParams) = $this->parseUpdateParams(
            $model->getChangedData(),
            [$this->pkName => $model->{$this->pkName}]
        );
        $query = "UPDATE {$this->getTableName()} SET {$queryPart}";
        Db::getInstance()->query($query, $queryParams);

        return $model->updateChangedData();
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

        $countQuery = "SELECT COUNT(*) FROM {$this->getTableName()} {$this->where}";
        $this->total = Db::getInstance()->query($countQuery, $this->whereParams)->fetchColumn();
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
        $query = "SELECT * FROM {$this->getTableName()} {$this->where} LIMIT 1 OFFSET {$this->currentOffset}";
        $this->currentModel = $this->createModel(Db::getInstance()->query($query, $this->whereParams)->fetch());
    }

    protected function parseWhere($where = null, $returnOnly = false, $keyPrefix = '')
    {
        $whereString = '';
        $whereParams = [];
        if (!$where) {
            if (!$returnOnly) {
                $this->where = $whereString;
                $this->whereParams = $whereParams;
            }

            return [$whereString, $whereParams];
        }

        $whereArr = [];
        foreach ($where as $key => $value) {
            $operator = '=';
            if (is_array($value)) {
                list($operator, $value) = $value;
            }

            $whereArr[] = "{$key} {$operator} :{$keyPrefix}{$key}";
            $whereParams[":{$keyPrefix}{$key}"] = $value;
        }

        $whereString = ' WHERE ' . implode(' AND ', $whereArr);

        if (!$returnOnly) {
            $this->where = $whereString;
            $this->whereParams = $whereParams;
        }

        return [$whereString, $whereParams];
    }

    /**
     * @param array $params
     * @param array $where
     * @return array
     */
    protected function parseUpdateParams(array $params, array $where)
    {
        $queryArray = [];
        $paramsArray = [];

        foreach ($params as $key => $value) {
            $queryArray[] = "$key = :$key";
            $paramsArray[":$key"] = $value;
        }

        $queryString = implode(', ', $queryArray);

        list($whereString, $whereParams) = $this->parseWhere($where, true, 'where_');
        $queryString .= $whereString;
        $paramsArray = array_merge($paramsArray, $whereParams);

        return [$queryString, $paramsArray];
    }

    abstract protected function createModel(array $data);
}
