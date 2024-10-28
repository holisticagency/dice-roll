# holistic-agency/dice-roll

dice roll formula

```bash
composer require holistic-agency/dice-roll:^1.x-dev
```

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
```
