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

use HolisticAgency\DiceRoll\DiceSet;
use HolisticAgency\Test\DiceRoll\Mock\DiceMock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DiceSet::class)]
class DiceSetTest extends TestCase
{
    public function testRoll()
    {
        // Given
        $dice = new DiceSet();
        $dice = $dice
            ->add(new DiceMock(1))
            ->add(new DiceMock(6))
        ;

        // When
        $actual = $dice->roll();

        // Then
        $this->assertSame(7, $actual);
        $this->assertSame('mockmock', (string) $dice);
        $this->assertCount(2, $dice);
    }
}
