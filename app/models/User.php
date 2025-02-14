<?php

namespace App\models;
class User{
    private string $name;
    private int $age;

    public function __construct(string $name,int $age)
    {
        $this->age = $age;
        $this->name = $name;
    }

    public  function getInfo():void{
        echo "My name is ".$this->name." ".$this->age;
    }
}