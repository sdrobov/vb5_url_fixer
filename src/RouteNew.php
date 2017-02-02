<?php

namespace Vb5UrlFixer;

class RouteNew extends BaseModel
{
    public function __construct($data, $prefix = null)
    {
        $this->tableName = 'routenew';
        parent::__construct($data, $prefix);
    }
}
