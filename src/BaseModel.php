<?php

namespace Vb5UrlFixer;

abstract class BaseModel
{
    /**
     * @var string
     */
    protected $tablePrefix;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var string
     */
    protected $pkName;

    /**
     * @param $prefix string
     */
    public function __construct($data, $prefix = null)
    {
        $this->tablePrefix = $prefix;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tablePrefix . '_' . $this->tableName;
    }

    public function getById($id)
    {

    }

    public function getOne($where = null, $orderBy = null, $limit = null, $offset = null)
    {

    }

    public function getAll($where = null, $orderBy = null, $limit = null, $offset = null)
    {

    }
}
