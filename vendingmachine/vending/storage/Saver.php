<?php


namespace Tests\Models\VendingMachine\Storage;


class Saver implements ISaver
{
    public const SAVE_PATH = '/vendingmachine/vending/cache/';
    public const FILE_MASK = 'vending_cache.json';

    public static function getFileName() : string
    {
        return Saver::FILE_MASK;
    }


    /**
     * @param array $data
     * @throws Exception
     */
    public static function saveState(array $data) : void
    {
        $relPath = Saver::SAVE_PATH;
        $uploadPath = $_SERVER["DOCUMENT_ROOT"] . $relPath;

        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0777, true)) {
            throw new Exception('Directory creation error');
        }
        // Delete existing file
        if(file_exists($uploadPath . self::getFileName()))
            unlink($uploadPath . self::getFileName());

        $flResource = fopen($uploadPath . self::getFileName(), 'a+');
        if($flResource){
            fwrite($flResource, json_encode($data));
            fclose($flResource);
        }
    }

    /**
     * @return array|null
     */
    public static function readState() : array
    {
        $relPath = Saver::SAVE_PATH;
        $uploadPath = $_SERVER["DOCUMENT_ROOT"] . $relPath;

        if(is_readable($uploadPath . self::getFileName())) {
            return json_decode(file_get_contents($uploadPath . self::getFileName()), true);
        }
        return [];
    }
}