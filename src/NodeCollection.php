<?php

namespace Vb5UrlFixer;

class NodeCollection extends BaseCollection
{
    /**
     * @var string
     */
    protected $tableName = 'node';

    /**
     * @var string
     */
    protected $pkName = 'nodeid';

    /**
     * @var Node
     */
    protected $currentModel;

    /**
     * @return Node
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * @param int $id
     * @return Node
     */
    public function getByPk($id)
    {
        return parent::getByPk($id);
    }

    /**
     * @param array $params
     * @return Node
     */
    public function getOne($params = [])
    {
        return parent::getOne($params);
    }

    /**
     * @param BaseModel $model
     * @return Node
     */
    public function save(BaseModel $model)
    {
        return parent::save($model);
    }

    /**
     * @param array $data
     * @return Node
     */
    protected function createModel(array $data)
    {
        return new Node($data);
    }
}
