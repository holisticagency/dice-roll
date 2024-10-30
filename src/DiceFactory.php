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

/**
 * UserSide Factory
 */
class DiceFactory
{
    /**
     * Creates the Dice set based on a litteral formula.
     *
     * @throws \LogicException if the formula is invalid
     */
    public static function fromFormula(string $formula): DiceSet
    {
        $generator = new MersenneTwister();
        $diceSet = new DiceSet();

        while (\preg_match(
            ',^(?<dice>' . DiceInterface::FORMULA_REGEX . ')([+-]|$),i',
            $formula,
            $matches,
        )) {
            $dice = new Dice($matches['dice']);
            $dice->setNumberGenerator($generator);

            $diceSet = $diceSet->add($dice);
            $formula = (string) \preg_replace(
                ',^' . \str_replace(
                    '+',
                    '\+',
                    $matches['dice'],
                ) . ',',
                '',
                $formula,
            );
        }

        if (\count($diceSet) == 0) {
            throw new \LogicException('Bad formula "' . $formula . '".');
        }

        return $diceSet;
    }
}
