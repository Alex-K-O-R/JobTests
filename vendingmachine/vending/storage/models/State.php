<?php


namespace Tests\Models\VendingMachine\Storage\Models;


use Tests\Models\VendingMachine\Storage\Saver;

class State
{
    private array $data;

    public function __construct(array $data = [])
    {
        if(empty($data)) {
            $this->data = array(
                'COINS' => [

                ],
                'BALANCE' => 0,
            );
        } else {
            $this->data = $data;
        }
    }

    /**
     * TODO: perhaps, save by every setter?
     */
    public function __destruct()
    {
        Saver::saveState($this->data);
    }

    public static function getInstance(bool $clearAll = false) : self
    {
        if($clearAll) {
            Saver::saveState([]);
        } else
            $data = Saver::readState();

        return new self($data ?? []);
    }

    public function setBalance(int $remains) : self
    {
        $this->data['BALANCE'] = $remains;
        return $this;
    }

    public function addBalance(int $amount) : self
    {
        $this->setBalance($this->getBalance() + $amount);
        return $this;
    }

    public function getBalance(): int
    {
        return $this->data['BALANCE'];
    }

    public function setCoinsLeft(int $denom, int $remains) : self
    {
        $this->data['COINS'][$denom] = $remains;
        return $this;
    }

    public function getCoinsLeft(int $denom) : int
    {
        return $this->data['COINS'][$denom] ?? 0;
    }

    public function withdrawCoin(int $denom) : bool
    {
        $remains = $this->getCoinsLeft($denom) - 1;
        if ($remains < 0)
            return false;

        $this->setCoinsLeft($denom, $remains);
        return true;
    }

    public function insertCoin(int $denom) : bool
    {
        $this->setCoinsLeft($denom, $this->getCoinsLeft($denom) + 1);
        return true;
    }

    public function exportOptionsArray() : array
    {
        return $this->data;
    }
}