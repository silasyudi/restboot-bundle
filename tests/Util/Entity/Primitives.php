<?php

namespace SilasYudi\RestBootBundle\Tests\Util\Entity;

use DateTime;
use JMS\Serializer\Annotation as Serializer;

class Primitives
{
    private bool $boolStringVazia;
    private bool $boolStringZero;
    private bool $boolStringInteiroPositivo;
    private bool $boolStringInteiroNegativo;
    private bool $boolStringNaoVazia;
    private string $stringVazia;
    private string $stringNaoVazia;
    private ?string $nulo = null;
    private ?string $notIsSet = null;
    private int $positivo;
    private int $negativo;
    private int $stringPositivo;
    private int $stringNegativo;
    private float $floatPositivo;
    private float $floatNegativo;
    private float $floatStringPositivo;
    private float $floatStringNegativo;
    /**
     * @Serializer\Type("array<string>")
     */
    private array $arrayVazio;

    public function isBoolStringVazia(): bool
    {
        return $this->boolStringVazia;
    }

    public function setBoolStringVazia(bool $boolStringVazia): Primitives
    {
        $this->boolStringVazia = $boolStringVazia;
        return $this;
    }

    public function isBoolStringZero(): bool
    {
        return $this->boolStringZero;
    }

    public function setBoolStringZero(bool $boolStringZero): Primitives
    {
        $this->boolStringZero = $boolStringZero;
        return $this;
    }

    public function isBoolStringInteiroPositivo(): bool
    {
        return $this->boolStringInteiroPositivo;
    }

    public function setBoolStringInteiroPositivo(bool $boolStringInteiroPositivo): Primitives
    {
        $this->boolStringInteiroPositivo = $boolStringInteiroPositivo;
        return $this;
    }

    public function isBoolStringInteiroNegativo(): bool
    {
        return $this->boolStringInteiroNegativo;
    }

    public function setBoolStringInteiroNegativo(bool $boolStringInteiroNegativo): Primitives
    {
        $this->boolStringInteiroNegativo = $boolStringInteiroNegativo;
        return $this;
    }

    public function isBoolStringNaoVazia(): bool
    {
        return $this->boolStringNaoVazia;
    }

    public function setBoolStringNaoVazia(bool $boolStringNaoVazia): Primitives
    {
        $this->boolStringNaoVazia = $boolStringNaoVazia;
        return $this;
    }

    public function getStringVazia(): string
    {
        return $this->stringVazia;
    }

    public function setStringVazia(string $stringVazia): Primitives
    {
        $this->stringVazia = $stringVazia;
        return $this;
    }

    public function getStringNaoVazia(): string
    {
        return $this->stringNaoVazia;
    }

    public function setStringNaoVazia(string $stringNaoVazia): Primitives
    {
        $this->stringNaoVazia = $stringNaoVazia;
        return $this;
    }

    public function getNulo(): ?string
    {
        return $this->nulo;
    }

    public function setNulo(?string $nulo): Primitives
    {
        $this->nulo = $nulo;
        return $this;
    }

    public function getNotIsSet(): ?string
    {
        return $this->notIsSet;
    }

    public function setNotIsSet(?string $notIsSet): Primitives
    {
        $this->notIsSet = $notIsSet;
        return $this;
    }

    public function getPositivo(): int
    {
        return $this->positivo;
    }

    public function setPositivo(int $positivo): Primitives
    {
        $this->positivo = $positivo;
        return $this;
    }

    public function getNegativo(): int
    {
        return $this->negativo;
    }

    public function setNegativo(int $negativo): Primitives
    {
        $this->negativo = $negativo;
        return $this;
    }

    public function getStringPositivo(): int
    {
        return $this->stringPositivo;
    }

    public function setStringPositivo(int $stringPositivo): Primitives
    {
        $this->stringPositivo = $stringPositivo;
        return $this;
    }

    public function getStringNegativo(): int
    {
        return $this->stringNegativo;
    }

    public function setStringNegativo(int $stringNegativo): Primitives
    {
        $this->stringNegativo = $stringNegativo;
        return $this;
    }

    public function getFloatPositivo(): float
    {
        return $this->floatPositivo;
    }

    public function setFloatPositivo(float $floatPositivo): Primitives
    {
        $this->floatPositivo = $floatPositivo;
        return $this;
    }

    public function getFloatNegativo(): float
    {
        return $this->floatNegativo;
    }

    public function setFloatNegativo(float $floatNegativo): Primitives
    {
        $this->floatNegativo = $floatNegativo;
        return $this;
    }

    public function getFloatStringPositivo(): float
    {
        return $this->floatStringPositivo;
    }

    public function setFloatStringPositivo(float $floatStringPositivo): Primitives
    {
        $this->floatStringPositivo = $floatStringPositivo;
        return $this;
    }

    public function getFloatStringNegativo(): float
    {
        return $this->floatStringNegativo;
    }

    public function setFloatStringNegativo(float $floatStringNegativo): Primitives
    {
        $this->floatStringNegativo = $floatStringNegativo;
        return $this;
    }

    public function getArrayVazio(): array
    {
        return $this->arrayVazio;
    }

    public function setArrayVazio(array $arrayVazio): Primitives
    {
        $this->arrayVazio = $arrayVazio;
        return $this;
    }
}
