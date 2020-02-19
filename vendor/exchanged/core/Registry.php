<?php


namespace exchanged;


class Registry
{
    use TSingleton;

    public $properties = [];

    public function getProperties()
    {
        return $this->properties;
    }

    public function setProper($name, $date)
    {
        $this->properties[$name] = $date;
    }

    public function getProper($name)
    {
        if (isset($this->properties[$name])) {
            return $this->properties[$name];
        }
        return false;
    }


}