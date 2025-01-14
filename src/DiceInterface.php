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

interface DiceInterface extends \Stringable
{
    public const FORMULA_REGEX = '(?<sign>[+-])?(?<number>\d*)D(?<faces>\d*)(?<modifier>[+-]\d+)?';

    public function roll(): int;
}
