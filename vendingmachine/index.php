<?php
use Tests\Models\VendingMachine\Settings\BGN;

include_once "./vending/include.php";

class VendingMachine extends \Tests\Models\VendingMachine\Models\VendingMachine
{
    /**
     * VendingMachine with lame constructor.
     */
    public function __construct(array $currencyDesc, array $productsDesc, bool $resetMode = true)
    {
        if (!isset($currencyDesc['sign']) || !isset($currencyDesc['space']) || !isset($currencyDesc['position']))
            throw new \Error('Bad currency description.');
        //TODO: not too good due to source task constructor input
        if($currencyDesc['sign'] == 'лв.'){
            $currencyDesc = new BGN($currencyDesc['sign'], $currencyDesc['space'], $currencyDesc['position']);
        } else
            $currencyDesc = null;

        parent::__construct($currencyDesc, $productsDesc, $resetMode);
    }
}

// START
$machine = new VendingMachine(
    [
        'sign' => 'лв.',
        'space' => '',
        'position' => VendingMachine::CURRENCY_POSITION_AFTER,
    ],
    [
        'Milk' => 0.50,
        'Espresso' => 0.40,
        'Long Espresso' => 0.60,
    ]
);

$machine
    ->buyDrink( 'espresso' )
    ->buyDrink( 'Espresso' )
    ->viewDrinks()
    ->putCoin( 2 )
    // if machine doesn't have coins inside on start, no change could be withdrawn with these conditions
    ->putCoin( 1 )
    // rather than that
/*    ->putCoin( 0.1 )
    ->putCoin( 0.1 )
    ->putCoin( 0.2 )
    ->putCoin( 0.2 )
    ->putCoin( 0.5 )*/
    ->buyDrink( 'Espresso' )
    ->getCoins()
    ->viewAmount()
    ->getCoins();
?>