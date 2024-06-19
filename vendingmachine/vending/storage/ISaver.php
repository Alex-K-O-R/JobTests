<?php


namespace Tests\Models\VendingMachine\Storage;


interface ISaver
{
    public static function saveState(array $data) : void;
    public static function readState() : array;
}