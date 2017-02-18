<?php

namespace Vb5UrlFixer;

class Fixer
{
    protected $nodes = [];
    protected $routes = [];

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

        foreach ($nodes as $node) {
            $this->fixNode($node);
        }

        Db::getInstance()->begin();
        foreach ($this->nodes as $nodeId => $newUrlident) {
            $node = NodeCollection::me()->getByPk($nodeId);
            $node->urlident = $newUrlident;

            NodeCollection::me()->save($node);
        }
        foreach ($this->routes as $routeId => $newValues) {
            list($newPrefix, $newRegex) = [$newValues['prefix'], $newValues['regex']];
            $route = RouteNewCollection::me()->getByPk($routeId);
            $route->prefix = $newPrefix;
            $route->regex = $newRegex;

            RouteNewCollection::me()->save($route);
        }
        Db::getInstance()->commit();
    }

    /**
     * @param Node $node
     */
    protected function fixNode(Node $node)
    {
        if ($node->isEmpty()) {
            return;
        }

        if (!mb_ereg_match('.*[а-яА-Я].*', $node->title)) {
            return;
        }

        if (isset($this->nodes[$node->nodeid])) {
            return;
        }

        $newUrlident = mb_strtolower($node->title);
        $newUrlident = str_replace(' ', '-', $newUrlident);
        $newUrlident = mb_ereg_replace('[^\w\d\-]', '', $newUrlident);

        $this->nodes[$node->nodeid] = $newUrlident;

        $this->fixRoute($node, $newUrlident);
        $this->fixChildRoutes($node, $newUrlident);
    }

    /**
     * @param Node $node
     * @param string $newUrlident
     * @param string $customUrlident
     */
    protected function fixRoute(Node $node, $newUrlident, $customUrlident = null)
    {
        if ($node->isEmpty()) {
            return;
        }

        if (!$node->hasRoute()) {
            return;
        }

        $prefix = '';
        $regex = '';
        if (isset($this->routes[$node->routeid])) {
            list($prefix, $regex) = [$this->routes[$node->routeid]['prefix'], $this->routes[$node->routeid]['regex']];
        } else {
            $route = $node->getRoute();
            if ($route->isEmpty()) {
                return;
            }

            list($prefix, $regex) = [$route->prefix, $route->regex];
        }

        $prefix = str_replace($customUrlident ?: $node->urlident, $newUrlident, $prefix);
        $regex = str_replace($customUrlident ?: $node->urlident, $newUrlident, $regex);

        $this->routes[$node->routeid] = ['prefix' => $prefix, 'regex' => $regex];
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
