<?php

namespace Vb5UrlFixer;

abstract class BaseModel
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $toUpdate = [];

    /**
     * BaseModel constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->data);
    }

    /**
     * @return bool
     */
    public function isChanged()
    {
        return !empty($this->toUpdate);
    }

    /**
     * @return array
     */
    public function getChangedData()
    {
        return $this->toUpdate;
    }

    /**
     * @return BaseModel
     */
    public function updateChangedData()
    {
        foreach ($this->toUpdate as $key => $value) {
            $this->data[$key] = $value;
        }

        $this->toUpdate = [];

        return $this;
    }

    public function __get($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function __set($name, $value)
    {
        if (!isset($this->data[$name])) {
            return;
        }

        if ($this->data[$name] == $value) {
            return;
        }

        $this->toUpdate[$name] = $value;
    }
}
