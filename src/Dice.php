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
class Dice implements DiceInterface, NumberGeneratorAwareInterface
{
    public const PLATONIC_SOLIDS = [4, 6, 8, 12, 20];

    public const PENTAGONAL_TRAPEZOHEDRON = 10;

    public const DEFAULTS = [
        'number' => 1,
        'faces' => 6,
        'modifier' => 0,
    ];

    protected ?NumberGeneratorInterface $generator = \null;

    private string $originalFormula;

    private int $sign;

    private int $number;

    private int $faces;

    private int $modifier;

    private ?int $bestOfNumber = \null;

    private ?int $leastOfNumber = \null;

    public function __construct(
        string $formula,
    ) {
        $this->parseFormula($formula);
        $this->originalFormula = $formula;
    }

    public function setNumberGenerator(NumberGeneratorInterface $generator, int $start = 1): void
    {
        if (!\in_array($start, [0, 1], true)) {
            throw new \LogicException('Bad start number "' . $start . '" (must be 0 or 1 (default).');
        }

        $this->generator = $generator->from($start)->to($this->faces - (1 - $start));
    }

    private function parseFormula(string $formula): void
    {
        $matches = [];
        if (!\preg_match(
            ',^' . self::FORMULA_REGEX . '$,i',
            $formula,
            $matches,
        ) || $matches['number'] == 0) {
            throw new \LogicException('Bad formula "' . $formula . '".');
        }

        $this->sign = $matches['sign'] == '-' ? -1 : 1;
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
        if (\is_null($this->generator)) {
            throw new \LogicException('A Number Generator must be set before a roll.');
        }

        $rolls = [];
        for ($times = 0; $times < $this->number; $times++) {
            $rolls[] = $this->generator->drawNumber();
        }
        \sort($rolls, \SORT_NUMERIC);

        if (!\is_null($this->bestOfNumber)) {
            $rolls = \array_slice($rolls, -$this->bestOfNumber, $this->bestOfNumber);
        }
        if (!\is_null($this->leastOfNumber)) {
            $rolls = \array_slice($rolls, 0, $this->leastOfNumber);
        }

        return (\array_sum($rolls) + $this->modifier) * $this->sign;
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

    private function xOf(int $number): self
    {
        if ($number > $this->number || $number < 1) {
            throw new \LogicException('Bad number "' . $number . '".');
        }

        return clone $this;
    }

    public function bestOf(int $number): self
    {
        $dice = $this->xOf($number);

        $dice->bestOfNumber = $number;
        $dice->leastOfNumber = \null;

        return $dice;
    }

    public function leastOf(int $number = 1): self
    {
        $dice = $this->xOf($number);

        $dice->bestOfNumber = \null;
        $dice->leastOfNumber = $number;

        return $dice;
    }
}
