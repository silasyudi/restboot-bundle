<?php

namespace SilasYudi\RestBootBundle\Tests\Util\Entity;

class Phone
{
    private string $name;
    private string $number;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Phone
    {
        $this->name = $name;
        return $this;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): Phone
    {
        $this->number = $number;
        return $this;
    }
}
