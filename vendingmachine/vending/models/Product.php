<?php


namespace Tests\Models\VendingMachine\Models;


class Product
{
    private string $name;
    private int $cost;

    /**
     * Product constructor.
     * @param string $name
     * @param int $cost
     */
    public function __construct(string $name, int $cost)
    {
        $this->name = $name;
        $this->cost = $cost;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }


}