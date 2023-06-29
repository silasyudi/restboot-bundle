<?php

namespace SilasYudi\RestBootBundle\Tests\Util\Entity;

use DateTime;
use JMS\Serializer\Annotation as Serializer;

class Person
{
    private string $name;
    /**
     * @Serializer\Type("array<string>")
     */
    private ?array $nicknames = null;
    private int $age;
    private bool $male;
    /**
     * @Serializer\Type("DateTime<'Y-m-d H:i:s'>")
     */
    private DateTime $birthdate;
    private float $weight;
    private ?Address $address = null;
    /**
     * @Serializer\Type("array<SilasYudi\RestBootBundle\Tests\Util\Entity\Phone>")
     */
    private ?array $phones = null;
    private ?GameScore $gameScore = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Person
    {
        $this->name = $name;
        return $this;
    }

    public function getNicknames(): ?array
    {
        return $this->nicknames;
    }

    public function setNicknames(?array $nicknames): Person
    {
        $this->nicknames = $nicknames;
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

    public function getBirthdate(): DateTime
    {
        return $this->birthdate;
    }

    public function setBirthdate(DateTime $birthdate): Person
    {
        $this->birthdate = $birthdate;
        return $this;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): Person
    {
        $this->weight = $weight;
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

    public function getGameScore(): ?GameScore
    {
        return $this->gameScore;
    }

    public function setGameScore(?GameScore $gameScore): Person
    {
        $this->gameScore = $gameScore;
        return $this;
    }
}
