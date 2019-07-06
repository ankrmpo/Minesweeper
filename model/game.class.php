<?php

class Game
{
    protected $players;
    protected $field;
    protected $points;

    function __construct($players)
    {
        $this->players=$players;
        //construct field
        //set points array to 0
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