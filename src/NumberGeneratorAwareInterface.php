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

namespace HolisticAgency\DiceRoll;

interface NumberGeneratorAwareInterface
{
    public function setNumberGenerator(NumberGeneratorInterface $generator, int $start = 1): void;
}
