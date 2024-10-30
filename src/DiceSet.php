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

/**
 * One or More Dice to Roll.
 *
 * @author JamesRezo <james@rezo.net>
 */
class DiceSet implements DiceInterface, \Countable
{
    /** @var DiceInterface[] */
    private array $dice = [];

    public function count(): int
    {
        return \count($this->dice);
    }

    public function add(DiceInterface $dice): static
    {
        $diceSet = clone $this;
        $diceSet->dice[] = $dice;

        return $diceSet;
    }

    public function roll(): int
    {
        return \array_reduce(
            $this->dice,
            function (int $carry, DiceInterface $dice) {
                $carry += $dice->roll();

                return $carry;
            },
            0,
        );
    }

    public function __toString(): string
    {
        return \array_reduce(
            $this->dice,
            function (string $carry, DiceInterface $dice) {
                $carry .= (string) $dice;

                return $carry;
            },
            '',
        );
    }
}
