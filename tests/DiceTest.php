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

namespace HolisticAgency\Test\DiceRoll;

use HolisticAgency\DiceRoll\Dice;
use HolisticAgency\DiceRoll\NumberGeneratorInterface;
use HolisticAgency\Test\DiceRoll\Stub\GeneratorStub;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Dice::class)]
class DiceTest extends TestCase
{
    private NumberGeneratorInterface $generator;

    protected function setUp(): void
    {
        $this->generator = new GeneratorStub();
    }

    public function testRoll()
    {
        // Given
        $dice = new Dice('2D6-2');
        $dice->setNumberGenerator($this->generator);

        // When
        $actual = $dice->roll(); // 2+2-2

        // Then
        $this->assertSame(2, $actual);
    }

    public function testNegativeRoll()
    {
        // Given
        $dice = new Dice('-1D6');
        $dice->setNumberGenerator($this->generator);

        // When
        $actual = $dice->roll();

        // Then
        $this->assertSame(-2, $actual);
    }

    public function test0D()
    {
        // Given
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Bad formula "0D".');

        // When
        new Dice('0D');

        // Then
        // An exception is thrown
    }

    public function testBadFormula()
    {
        // Given
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Bad formula "test".');

        // When
        new Dice('test');

        // Then
        // An exception is thrown
    }

    public function testBestOfRoll()
    {
        // Given
        $dice = (new Dice('4D6'))->bestOf(3);
        $dice->setNumberGenerator($this->generator);

        // When
        $actual = $dice->roll();

        // Then
        $this->assertSame(6, $actual);
        $this->assertSame('3 best of 4D6', (string) $dice);
    }

    public function testLeastOfRoll()
    {
        // Given
        $dice = (new Dice('2D20'))->leastOf(1);
        $dice->setNumberGenerator($this->generator);

        // When
        $actual = $dice->roll();

        // Then
        $this->assertSame(2, $actual);
        $this->assertSame('1 least of 2D20', (string) $dice);
    }

    public function testBadStartNumber()
    {
        // Given
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Bad start number "2" (must be 0 or 1 (default).');

        // When
        (new Dice('D'))->setNumberGenerator($this->generator, 2);


        // Then
        // An exception is thrown
    }

    public function testBadBestNumber()
    {
        // Given
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Bad number "0".');

        // When
        (new Dice('D'))->bestOf(0);

        // Then
        // An exception is thrown
    }

    public function testBadLeastNumber()
    {
        // Given
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Bad number "2".');

        // When
        (new Dice('D'))->leastOf(2);

        // Then
        // An exception is thrown
    }

    public function testMissingNumberGenerator()
    {
        // Given
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('A Number Generator must be set before a roll.');

        // When
        (new Dice('D'))->roll();

        // Then
        // An exception is thrown
    }
}
