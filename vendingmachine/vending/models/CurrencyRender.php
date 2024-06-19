<?php


namespace Tests\Models\VendingMachine\Models;


use Tests\Models\VendingMachine\Models\VendingMachine;

abstract class CurrencyRender
{
    // Currency short name
    protected string $sign;
    // Currency name delimeter
    protected string $space;
    // Currency render setting
    protected int $position;

    /**
     * CurrencyRender constructor.
     * @param string $sign
     * @param string $space
     * @param int $position
     */
    public function __construct(string $sign, string $space, int $position)
    {
        $this->sign = $sign;
        $this->space = $space;
        $this->position = $position;
    }


    protected function getResultString(string $amount, string $crncShortName) : string
    {
        $resStr = '';

        if($this->position == VendingMachine::CURRENCY_POSITION_AFTER) {
            $resStr = strval($amount) . $this->space . $crncShortName;
        } elseif ($this->position == VendingMachine::CURRENCY_POSITION_BEFORE) {
            $resStr = $crncShortName . $this->space . strval($amount);
        }
        return $resStr;
    }

    public abstract function getMoneyResultRender(int $amount) : string;
}