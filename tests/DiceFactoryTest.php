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
use HolisticAgency\DiceRoll\DiceFactory;
use HolisticAgency\DiceRoll\DiceSet;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DiceFactory::class)]
#[CoversClass(DiceSet::class)]
#[CoversClass(Dice::class)]
class DiceFactoryTest extends TestCase
{
    public function testFromBadFormula()
    {
        // Given
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Bad formula "test".');

        // When
        DiceFactory::fromFormula('test');

        // Then
        // An exception is thrown
    }

    public function testFromFormula()
    {
        // Given
        $dice = DiceFactory::fromFormula('D+1+D+1');

        // When
        $actual = $dice->roll();

        // Then
        $this->assertGreaterThanOrEqual(4, $actual);
        $this->assertCount(2, $dice);
    }
}
