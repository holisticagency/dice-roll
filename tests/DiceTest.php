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

use HolisticAgency\Decouple\Frozen\Randomizer;
use HolisticAgency\DiceRoll\Dice;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Dice::class)]
class DiceTest extends TestCase
{
    private Randomizer $randomizer;

    protected function setUp(): void
    {
        $this->randomizer = new Randomizer(2);
    }

    public function testRoll()
    {
        // Given
        $dice = new Dice('2D6-2', $this->randomizer);

        // When
        $actual = $dice->roll(); // 2+2-2

        // Then
        $this->assertSame(2, $actual);
    }

    public function testNegativeRoll()
    {
        // Given
        $dice = new Dice('-1D6', $this->randomizer);

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
        $dice = (new Dice('4D6', $this->randomizer))->bestOf(3);

        // When
        $actual = $dice->roll();

        // Then
        $this->assertSame(6, $actual);
        $this->assertSame('3 best of 4D6', (string) $dice);
    }

    public function testLeastOfRoll()
    {
        // Given
        $dice = (new Dice('2D20', $this->randomizer))->leastOf(1);

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
        new Dice('D', start: 2);

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
}
