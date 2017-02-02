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
     * @param array $data
     * @return Node
     */
    protected function createModel(array $data)
    {
        return new Node($data);
    }
}
