# Examples

## D666

```php
#!/usr/bin/env php
<?php

use HolisticAgency\Decouple\Frozen\Randomizer;
use HolisticAgency\DiceRoll\Dice;
use HolisticAgency\DiceRoll\DiceInterface;

require_once __DIR__ . '/../vendor/autoload.php';

class D666 implements DiceInterface
{
    private Dice $hundreds;
    private Dice $tens;
    private Dice $units;

    /**
     * @param array{hundreds?:int,tens?:int,units?:int}|null $faker
     */
    public function __construct(?array $faker = null)
    {
        $this->hundreds = new Dice('D', $this->setRandomizer('hundreds', $faker));
        $this->tens = new Dice('D', $this->setRandomizer('tens', $faker));
        $this->units = new Dice('D', $this->setRandomizer('units', $faker));
    }

    /**
     * @param array{hundreds?:int,tens?:int,units?:int}|null $faker
     */
    private function setRandomizer(string $part, ?array $faker): ?Randomizer
    {
        return isset($faker[$part]) ? new Randomizer($faker[$part]) : null;
    }

    public function roll(): int
    {
        return 100 * $this->hundreds->roll()
            + 10 * $this->tens->roll()
            + $this->units->roll()
        ;
    }

    public function __toString(): string
    {
        return 'D666';
    }
}

$d666 = new D666();

echo $d666 . ':' . $d666->roll() . PHP_EOL; // D666:...

$fakeD666 = new D666(['hundreds' => 6, 'tens' => 6, 'units' => 6]);

echo $fakeD666 . '(cheated):' . $fakeD666->roll() . PHP_EOL; // D666(cheated):666
```

## D100 (with two ten-sided dice)

```php
#!/usr/bin/env php
<?php

use HolisticAgency\Decouple\Frozen\Randomizer;
use HolisticAgency\DiceRoll\Dice;
use HolisticAgency\DiceRoll\DiceInterface;

require_once __DIR__ . '/../vendor/autoload.php';

class D100 implements DiceInterface
{
    private Dice $tens;
    private Dice $units;

    /**
     * @param array{tens?:int,units?:int}|null $faker
     */
    public function __construct(?array $faker = null)
    {
        $this->tens = new Dice('D10', $this->setRandomizer('tens', $faker), start: 0);
        $this->units = new Dice('D10', $this->setRandomizer('units', $faker), start: 0);
    }

    /**
     * @param array{tens?:int,units?:int}|null $faker
     */
    private function setRandomizer(string $part, ?array $faker): ?Randomizer
    {
        return isset($faker[$part]) ? new Randomizer($faker[$part]) : null;
    }

    public function roll(): int
    {
        $roll = 10 * $this->tens->roll()
            + $this->units->roll()
        ;

        return $roll == 0 ? 100 : $roll;
    }

    public function __toString(): string
    {
        return 'D100';
    }
}

$d100 = new D100();

echo $d100 . ':' . $d100->roll() . PHP_EOL; // D100:...

$fakeD100 = new D100(['tens' => 0, 'units' => 0]); // 00 + 0 = 100 !

echo $fakeD100 . '(cheated):' . $fakeD100->roll() . PHP_EOL; // D100(cheated):100
```
