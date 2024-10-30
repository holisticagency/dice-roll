# Examples

## D666

```php
#!/usr/bin/env php
<?php
// bin/d666.php

use HolisticAgency\DiceRoll\MersenneTwister;
use HolisticAgency\DiceRoll\Dice;
use HolisticAgency\DiceRoll\DiceInterface;

require_once __DIR__ . '/../vendor/autoload.php';

class D666 implements DiceInterface
{
    private Dice $hundreds;
    private Dice $tens;
    private Dice $units;

    public function __construct()
    {
        $this->hundreds = new Dice('D');
        $this->hundreds->setNumberGenerator(new MersenneTwister());
        $this->tens = new Dice('D');
        $this->tens->setNumberGenerator(new MersenneTwister());
        $this->units = new Dice('D');
        $this->units->setNumberGenerator(new MersenneTwister());
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
```

## D100 (with two ten-sided dice)

```php
#!/usr/bin/env php
<?php
// bin/d100.php

use HolisticAgency\DiceRoll\Dice;
use HolisticAgency\DiceRoll\DiceInterface;
use HolisticAgency\DiceRoll\MersenneTwister;

require_once __DIR__ . '/../vendor/autoload.php';

class D100 implements DiceInterface
{
    private Dice $tens;
    private Dice $units;

    public function __construct()
    {
        $this->tens = new Dice('D10');
        $this->tens->setNumberGenerator(new MersenneTwister(), 0);
        $this->units = new Dice('D10');
        $this->units->setNumberGenerator(new MersenneTwister(), 0);
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
```
