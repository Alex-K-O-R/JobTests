<?php


namespace Tests\Models\VendingMachine\Settings;


interface ISettings
{
    public static function getSupportedCoinsDenominee() : array;

    public function parseFromFloat(float $amountFloat) : int;
}