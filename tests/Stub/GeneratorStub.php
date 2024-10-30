<?php

namespace HolisticAgency\Test\DiceRoll\Stub;

use HolisticAgency\DiceRoll\NumberGeneratorInterface;

class GeneratorStub implements NumberGeneratorInterface
{
    public function from(int $min): self
    {
        return $this;
    }

    public function to(int $max): self
    {
        return $this;
    }

    public function drawNumber(): int
    {
        return 2;
    }
}
