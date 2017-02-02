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
     * @param array $data
     * @return RouteNew
     */
    protected function createModel(array $data)
    {
        return new RouteNew($data);
    }
}
