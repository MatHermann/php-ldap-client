<?php

namespace MatHermann\Ldap;

class Result
{
    /**
     * @var array $data
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (is_array($this->data) && array_key_exists($key, $this->data))
            return $this->data[$key];

        return $default;
    }

    /**
     * @param string|int $key
     * @return bool
     */
    public function has($key)
    {
        return is_array($this->data) && array_key_exists($key, $this->data);
    }

    /**
     * @return int
     */
    public function count()
    {
        return $this->get('count', 0);
    }
}
