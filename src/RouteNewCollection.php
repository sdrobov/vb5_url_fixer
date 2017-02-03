<?php

namespace Vb5UrlFixer;

class RouteNewCollection extends BaseCollection
{
    /**
     * @var string
     */
    protected $tableName = 'routenew';

    /**
     * @var string
     */
    protected $pkName = 'routeid';

    /**
     * @var RouteNew
     */
    protected $currentModel;

    /**
     * @return RouteNew
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * @param int $id
     * @return RouteNew
     */
    public function getByPk($id)
    {
        return parent::getByPk($id);
    }

    /**
     * @param array $params
     * @return RouteNew
     */
    public function getOne($params = [])
    {
        return parent::getOne($params);
    }

    /**
     * @param BaseModel $model
     * @return RouteNew
     */
    public function save(BaseModel $model)
    {
        return parent::save($model);
    }

    /**
     * @param array $data
     * @return RouteNew
     */
    protected function createModel(array $data)
    {
        return new RouteNew($data);
    }
}
