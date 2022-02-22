<?php

namespace Tests\Util\Entity;

use DateTime;

class Person
{
    private string $name;
    private int $age;
    private bool $male;
    private DateTime $birtydate;
    private ?Address $address = null;
    private ?array $phones = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Person
    {
        $this->name = $name;
        return $this;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): Person
    {
        $this->age = $age;
        return $this;
    }

    public function isMale(): bool
    {
        return $this->male;
    }

    public function setMale(bool $male): Person
    {
        $this->male = $male;
        return $this;
    }

    public function getBirtydate(): DateTime
    {
        return $this->birtydate;
    }

    public function setBirtydate(DateTime $birtydate): Person
    {
        $this->birtydate = $birtydate;
        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): Person
    {
        $this->address = $address;
        return $this;
    }

    public function getPhones(): ?array
    {
        return $this->phones;
    }

    public function setPhones(?array $phones): Person
    {
        $this->phones = $phones;
        return $this;
    }
}