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

use HolisticAgency\Decouple\Randomizer;
use HolisticAgency\Decouple\RandomizerInterface;

/**
 * One or More Dice to Roll.
 *
 * @author JamesRezo <james@rezo.net>
 */
class Dice implements DiceInterface
{
    public const PLATONIC_SOLIDS = [4, 6, 8, 12, 20];

    public const PENTAGONAL_TRAPEZOHEDRON = 10;

    public const DEFAULTS = [
        'number' => 1,
        'faces' => 6,
        'modifier' => 0,
    ];

    private RandomizerInterface $randomizer;

    private string $originalFormula;

    private int $faces;

    private int $number;

    private int $modifier;

    private ?int $bestOfNumber = \null;

    private ?int $leastOfNumber = \null;

    public function __construct(string $formula, ?RandomizerInterface $randomizer = null, int $start = 1)
    {
        if (!\in_array($start, [0, 1])) {
            throw new \LogicException('Bad start number "'.$start.'" (must be 0 or 1 (default).');
        }

        $this->parseFormula($formula);
        $this->originalFormula = $formula;
        $this->randomizer = $randomizer ?? new Randomizer($start, $this->faces - (1 - $start));
    }

    private function parseFormula(string $formula): void
    {
        $matches = [];
        if (!\preg_match(
            ',^(?<number>\d*)D(?<faces>\d*)(?<modifier>[+-]\d+)?$,i',
            $formula,
            $matches,
        ) || $matches['number'] == 0) {
            throw new \LogicException('Bad formula "' . $formula . '".');
        }

        $this->number = $this->setDefault('number', $matches);
        $this->faces = $this->setDefault('faces', $matches);
        $this->modifier = $this->setDefault('modifier', $matches);
    }

    /**
     * @param array{number?:string,faces?:string,modifier?:string} $matches
     */
    private function setDefault(string $part, array $matches): int
    {
        return !empty($matches[$part]) ? (int) $matches[$part] : self::DEFAULTS[$part];
    }

    public function roll(): int
    {
        $rolls = [];
        for ($times = 0; $times < $this->number; $times++) {
            $rolls[] = $this->randomizer->random();
        }
        \sort($rolls, \SORT_NUMERIC);

        if (!\is_null($this->bestOfNumber)) {
            $rolls = \array_slice($rolls, -$this->bestOfNumber, $this->bestOfNumber);
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

    private function xOff(int $number): self
    {
        if ($number > $this->number || $number < 1) {
            throw new \LogicException('Bad number "' . $number . '".');
        }

        return clone $this;
    }

    public function bestOf(int $number): self
    {
        $dice = $this->xOff($number);

        $dice->bestOfNumber = $number;
        $dice->leastOfNumber = \null;

        return $dice;
    }

    public function leastOf(int $number = 1): self
    {
        $dice = $this->xOff($number);

        $dice->bestOfNumber = \null;
        $dice->leastOfNumber = $number;

        return $dice;
    }
}
