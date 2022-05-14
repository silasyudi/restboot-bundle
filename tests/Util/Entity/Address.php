<?php

namespace SilasYudi\RestBootBundle\Tests\Util\Entity;

class Address
{
    private string $street;
    private int $number;
    private ?string $complement = null;

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): Address
    {
        $this->street = $street;
        return $this;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function setNumber(int $number): Address
    {
        $this->number = $number;
        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): Address
    {
        $this->complement = $complement;
        return $this;
    }
}
