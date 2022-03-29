<?php

namespace SymfonyBoot\SymfonyBootBundle\Tests\Util\Entity;

class GameScore
{
    private int $balanceWinningsLosses;
    private float $balancePoints;

    public function getBalanceWinningsLosses(): int
    {
        return $this->balanceWinningsLosses;
    }

    public function setBalanceWinningsLosses(int $balanceWinningsLosses): GameScore
    {
        $this->balanceWinningsLosses = $balanceWinningsLosses;
        return $this;
    }

    public function getBalancePoints(): float
    {
        return $this->balancePoints;
    }

    public function setBalancePoints(float $balancePoints): GameScore
    {
        $this->balancePoints = $balancePoints;
        return $this;
    }
}
