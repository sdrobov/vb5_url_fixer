<?php

namespace Vb5UrlFixer;

class Node extends BaseModel
{
    public function __construct($data, $prefix = null)
    {
        $this->tableName = 'node';
        parent::__construct($data, $prefix);
    }
}
