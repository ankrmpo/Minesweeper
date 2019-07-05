<?php

class Rank
{
    protected $username;
    protected $points;

    function __construct($username, $points)
    {
        $this->username=$username;
        $this->points=$points;
    }

    function __get($property)
    {
        return $this->$property;
    }

    function __set($property, $value)
    {
        $this->$property=$value;
        return $this;
    }
};

?>