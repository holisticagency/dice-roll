# holistic-agency/dice-roll

One or More Dice to Roll.

## Install

```bash
composer require holistic-agency/dice-roll:^1.x-dev
```

## Usage

```php
use HolisticAgency\DiceRoll\Dice;

$best3of4 = (new Dice('4D6'))->bestOf(3);
for ($i=0; $i < 6; $i++) {
    echo "Roll of $best3of4:" . $best3of4->roll() . PHP_EOL;
}

$doubleRoll = new Dice('2D20');
$advantage = $doubleRoll->bestOf(1);
$disadvantage = $doubleRoll->leastOf(1);

echo $advantage . ':' . $advantage->roll() . PHP_EOL;
echo $disadvantage . ':' . $disadvantage->roll() . PHP_EOL;

$potionOfHealing = new Dice('2D4+2');
echo $potionOfHealing . ':' . $potionOfHealing->roll() . PHP_EOL;

// signed dice -xDy±z is actually treated as -(xDy±z)
$sub1d4 = new Dice('-1D4+1');
echo $sub1d4 . ':' . $sub1d4->roll() . PHP_EOL;
```

[See other examples](docs/Examples.md)
