<?php

/**
 * This file is part of DiceRoll.
 *
 * (c) JamesRezo <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HolisticAgency\DiceRoll;

use HolisticAgency\Decouple\Randomizer;
use HolisticAgency\Decouple\RandomizerInterface;

/**
 * One or More Dice to Roll.
 *
 * @author JamesRezo <james@rezo.net>
 */
class Dice implements \Stringable
{
    public const PLATONIC_SOLIDS = [4, 6, 8, 12, 20];

    public const PENTAGONAL_TRAPEZOHEDRON = 10;

    public const DEFAULT_NUMBER = 1;

    public const DEFAULT_FACES = 6;

    public const DEFAULT_MODIFIER = 0;

    private RandomizerInterface $randomizer;

    private string $originalFormula;

    private int $faces;

    private int $number;

    private int $modifier;

    private ?int $bestOfNumber = \null;

    private ?int $leastOfNumber = \null;

    public function __construct(string $formula, ?RandomizerInterface $randomizer = null)
    {
        $this->parseFormula($formula);
        $this->originalFormula = $formula;
        $this->randomizer = $randomizer ?? new Randomizer(1, $this->faces);
    }

    private function parseFormula(string $formula): void
    {
        $matches = [];
        if (!\preg_match(
            ',^(?<number>\d*)D(?<faces>\d*)(?<modifier>[+-]\d+)?$,i',
            $formula,
            $matches
        )) {
            throw new \LogicException('Bad formula "'.$formula.'".');
        }

        $this->number = !empty($matches['number']) ? (int) $matches['number'] : self::DEFAULT_NUMBER;
        $this->faces = !empty($matches['faces']) ? (int) $matches['faces'] : self::DEFAULT_FACES;
        $this->modifier = !empty($matches['modifier']) ? (int) $matches['modifier'] : self::DEFAULT_MODIFIER;
    }

    public function roll(): int
    {
        $rolls = [];
        for ($times=0; $times < $this->number; $times++) { 
            $rolls[] = $this->randomizer->random();
        }
        \sort($rolls, \SORT_NUMERIC);

        if (!\is_null($this->bestOfNumber)) {
            $rolls = \array_slice($rolls,  -$this->bestOfNumber, $this->bestOfNumber);
        }
        if (!\is_null($this->leastOfNumber)) {
            $rolls = \array_slice($rolls, 0, $this->leastOfNumber);
        }

        return \array_sum($rolls) + $this->modifier;
    }

    public function __toString(): string
    {
        $bestOrLeast = '';

        if ($this->bestOfNumber) {
            $bestOrLeast = $this->bestOfNumber . ' best of ';
        }
        if ($this->leastOfNumber) {
            $bestOrLeast = $this->leastOfNumber . ' least of ';
        }

        return $bestOrLeast . $this->originalFormula;
    }

    public function bestOf(int $number): self
    {
        if ($number > $this->number || $number < 1) {
            throw new \LogicException('Bad number "'.$number.'".');
        }

        $dice = clone $this;
        $dice->bestOfNumber = $number;
        $dice->leastOfNumber = \null;
        
        return $dice;
    }
    
    public function leastOf(int $number = 1): self
    {
        if ($number > $this->number || $number < 1) {
            throw new \LogicException('Bad number "'.$number.'".');
        }
        
        $dice = clone $this;
        $dice->bestOfNumber = \null;
        $dice->leastOfNumber = $number;

        return $dice;
    }
}
