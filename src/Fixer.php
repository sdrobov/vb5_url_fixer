<?php

namespace Vb5UrlFixer;

class Fixer
{
    /**
     * Fixer constructor.
     *
     * @param string $dbUserName
     * @param string $dbPassword
     * @param string $dbName
     * @param string $dbTablePrefix
     * @param string $dbHost
     * @param bool $dry
     * @param bool $logQueries
     * @throws \Exception
     */
    public function __construct(
        $dbUserName,
        $dbPassword,
        $dbName,
        $dbTablePrefix = '',
        $dbHost = 'localhost',
        $dry = true,
        $logQueries = false
    )
    {
        if (!Db::getInstance($dbTablePrefix, $dry, $logQueries)->connect($dbHost, $dbName, $dbUserName, $dbPassword)) {
            throw new \Exception('Cant connect to db!');
        }
    }

    public function fix()
    {
        $nodes = NodeCollection::me();

        Db::getInstance()->begin();
        foreach ($nodes as $node) {
            $this->fixNode($node);
        }
        Db::getInstance()->commit();
    }

    /**
     * @param Node $node
     */
    protected function fixNode(Node $node)
    {
        $newUrlident = mb_strtolower($node->title);
        $newUrlident = str_replace(' ', '-', $newUrlident);
        $newUrlident = mb_ereg_replace('[^\w\d\-]', '', $newUrlident);

        $this->fixRoute($node, $newUrlident);
        $this->fixChildRoutes($node, $newUrlident);

        $node->urlident = $newUrlident;
        NodeCollection::me()->save($node);
    }

    /**
     * @param Node $node
     * @param string $newUrlident
     * @param string $customUrlident
     */
    protected function fixRoute(Node $node, $newUrlident, $customUrlident = null)
    {
        if (!$node->hasRoute()) {
            return;
        }

        $route = $node->getRoute();
        $route->prefix = str_replace($customUrlident ?: $node->urlident, $newUrlident, $route->prefix);
        $route->regex = str_replace($customUrlident ?: $node->urlident, $newUrlident, $route->regex);

        RouteNewCollection::me()->save($route);
    }

    /**
     * @param Node $node
     * @param string $newUrlident
     */
    protected function fixChildRoutes(Node $node, $newUrlident)
    {
        foreach ($node->getChildren() as $child) {
            $this->fixRoute($child, $newUrlident, $node->urlident);
        }
    }
}
