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

interface NumberGeneratorInterface
{
    public function from(int $min): self;

    public function to(int $max): self;

    public function drawNumber(): int;
}
