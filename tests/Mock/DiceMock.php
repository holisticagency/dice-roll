<?php

declare(strict_types=1);

/*
 * This file is part of holistic-agency/dice-roll.
 *
 * (c) JamesRezo <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HolisticAgency\Test\DiceRoll\Mock;

use HolisticAgency\Decouple\Frozen\NumberGenerator;
use HolisticAgency\DiceRoll\DiceInterface;

class DiceMock extends NumberGenerator implements DiceInterface
{
    public function roll(): int
    {
        return $this->draw();
    }

    public function __toString(): string
    {
        return 'mock';
    }
}
