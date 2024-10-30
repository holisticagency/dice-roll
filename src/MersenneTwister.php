<?php

/*
 * This file is part of holistic-agency/dice-roll.
 *
 * (c) JamesRezo <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HolisticAgency\DiceRoll;

use HolisticAgency\Decouple\MersenneTwister as DecoupleMersenneTwister;
use HolisticAgency\Decouple\RangeException;

/**
 * ServerSide Proxy
 *
 * @codeCoverageIgnore
 */
class MersenneTwister extends DecoupleMersenneTwister implements NumberGeneratorInterface
{
    public function from(int $min): self
    {
        return new self($min, $this->max);
    }

    public function to(int $max): self
    {
        return new self($this->min, $max);
    }

    /**
     * @throws RangeException if a range error occurs at construction or at draw.
     */
    public function drawNumber(): int
    {
        return $this->draw();
    }
}
