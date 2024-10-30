# holistic-agency/dice-roll

One or More Dice to Roll.

## Install

```bash
composer require holistic-agency/dice-roll:^1.x-dev
```

## Usage

```php
#!/usr/bin/env php
<?php

use HolisticAgency\DiceRoll\DiceFactory;

// signed dice -xDy±z is actually treated as -(xDy±z)
$sub1d4 = DiceFactory::fromFormula('-1D4+1');
echo $sub1d4 . ':' . $sub1d4->roll() . PHP_EOL; // Between -5 and -1, not -3 to 0
```

```php
#!/usr/bin/env php
<?php

use HolisticAgency\DiceRoll\DiceFactory;

$potionOfHealing = DiceFactory::fromFormula('2D4+2');
echo $potionOfHealing . ':' . $potionOfHealing->roll() . PHP_EOL;

$sneakAttack = DiceFactory::fromFormula('1D8+2+3D6');
echo $sneakAttack . ':' . $sneakAttack->roll() . PHP_EOL;
```

```php
#!/usr/bin/env php
<?php

use HolisticAgency\DiceRoll\Dice;
use HolisticAgency\DiceRoll\MersenneTwister;

$best3of4 = (new Dice('4D6'))->bestOf(3);
$best3of4->setNumberGenerator(new MersenneTwister());
for ($i = 0; $i < 6; $i++) {
    echo "Roll of $best3of4:" . $best3of4->roll() . PHP_EOL;
}
```

[See other examples](docs/Examples.md)
