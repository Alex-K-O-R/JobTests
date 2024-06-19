<?php


namespace Tests\Models\VendingMachine\Settings;


use Tests\Models\VendingMachine\Models\CurrencyRender;

class BGN extends CurrencyRender implements \Tests\Models\VendingMachine\Settings\ISettings
{
    public static function getSupportedCoinsDenominee() : array
    {
        return array(5, 10, 20, 50, 100);
    }

    public function parseFromFloat(float $amountFloat): int
    {
        return $amountFloat * 100;
    }

    public function getMoneyResultRender(?int $amount = null) : string
    {
        $amount = floatval($amount) / 100;
        $amount = number_format($amount, 2);
        return $this->getResultString($amount, $this->sign);
    }

}