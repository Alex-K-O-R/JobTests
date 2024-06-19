<?php


namespace Tests\Models\VendingMachine\Models;


use Tests\Models\VendingMachine\Settings\BGN;
use Tests\Models\VendingMachine\Settings\ISettings;
use Tests\Models\VendingMachine\Storage\Models\State;

/** This class represents a simple model of the Vending machine
 * $resetMode parameter allows you to clear state (reset to default),
 * otherwise trade results would be saved till the next launch
 * Class VendingMachine
 * @package Tests\Models\VendingMachine\Models
 */
class VendingMachine
{
    use \Tests\Models\VendingMachine\Constants\VendingMachine\VendingMachine;

    protected ISettings|CurrencyRender $settings;
    /**
     * @var Product[]
     */
    protected array $products;
    private State $state;

    public function __construct(CurrencyRender $currencyDesc, array $productsDesc, bool $resetMode = true)
    {
        $this->settings = $currencyDesc;

        foreach ($productsDesc as $prdctNm => $prdctCst){
            $this->products[$prdctNm] = new Product(
                $prdctNm, is_float($prdctCst) ? $this->getSettings()->parseFromFloat($prdctCst) : $prdctCst
            );
        }

        $this->state = State::getInstance($resetMode);
    }


    // Required block
    public function ViewDrinks() : self
    {
        try{
            self::printStr("Напитĸи:", self::OUTPUT_BOLD, 1);
            foreach ($this->getProducts() as $product){
                self::printStr(
                    "{$product->getName()}: ".$this->getSettings()->getMoneyResultRender($product->getCost()), 0, 1
                );
            }
            self::printStr('', 0, 1);
        } catch (\Exception $ex) {
            self::printStr($ex->getMessage(), self::OUTPUT_WARN);
        } catch (\Error $er) {
            self::printStr($er->getMessage(), self::OUTPUT_ERROR);
        }

        return $this;
    }

    public function Putcoin(float $nominee) : self
    {
        try {
            $nominee = $this->getSettings()->parseFromFloat($nominee);
            if(!in_array($nominee, $this->getSettings()::getSupportedCoinsDenominee())) {
                $nomStr = '';
                foreach ($this->getSettings()::getSupportedCoinsDenominee() as $nom) {
                    $nomStr .= $this->getSettings()->getMoneyResultRender($nom).', ';
                }
                $nomStr = substr($nomStr, 0, -2);
                throw new \Exception('Автомата приема монети от: '.$nomStr);
            }

            if($this->getState()->insertCoin($nominee)) {
                $this->getState()->addBalance($nominee);
                self::printStr(
                    "Успешно поставихте {$this->getSettings()->getMoneyResultRender($nominee)}, теĸущата Ви сума е ".
                    $this->getSettings()->getMoneyResultRender($this->getState()->getBalance())
                );
            }
        } catch (\Exception $ex) {
            self::printStr($ex->getMessage(), self::OUTPUT_WARN);
        } catch (\Error $er) {
            self::printStr($er->getMessage(), self::OUTPUT_ERROR);
        }

        return $this;
    }

    public function Buydrink(string $drinkName) : self
    {
        try {
            if(!array_key_exists($drinkName, $this->getProducts()))
                throw new \Exception('Исĸаният продуĸт не е намерен.');

            $selected = $this->getProducts()[$drinkName];

            if($this->getState()->getBalance() < $selected->getCost())
                throw new \Exception('Недостатъчна наличност.');

            $this->getState()->setBalance(
                $this->getState()->getBalance() - $selected->getCost()
            );

            self::printStr("Успешно заĸупихте '{$selected->getName()}' от ".
                $this->getSettings()->getMoneyResultRender($selected->getCost()).", теĸущата Ви сума е ".
                $this->getSettings()->getMoneyResultRender($this->getState()->getBalance())
            );
        } catch (\Exception $ex) {
            self::printStr($ex->getMessage(), self::OUTPUT_WARN);
        } catch (\Error $er) {
            self::printStr($er->getMessage(), self::OUTPUT_ERROR);
        }

        return $this;
    }

    public function GetCoins() : self
    {
        try {
            if(empty($this->getState()->getBalance()))
                throw new \Exception('Няма ресто за връщане.');

            $maxMin = $this->getSettings()::getSupportedCoinsDenominee();
            sort($maxMin);
            $lastInd = count($maxMin) - 1;
            $changeResult = array();

            for($i = $lastInd; $i >= 0; $i--){
                while($this->getState()->getBalance() >= $maxMin[$i] && $this->getState()->getCoinsLeft($maxMin[$i])) {
                    if($this->getState()->withdrawCoin($maxMin[$i])) {
                        $this->getState()->setBalance(
                            $this->getState()->getBalance() - $maxMin[$i]
                        );

                        if(!isset($changeResult[$maxMin[$i]])) {
                            $changeResult[$maxMin[$i]] = 0;
                        }
                        $changeResult[$maxMin[$i]] = $changeResult[$maxMin[$i]] + 1;
                    }
                }
            }

            if($this->getState()->getBalance() > 0){
                self::printStr("Няма достатъчно монети от необходимата номинална стойност за доставка", 1);
            }

            $total = 0;
            $changeResultStr = '';
            foreach ($changeResult as $nom => $quant){
                $total += $nom * $quant;
                $changeResultStr .= "{$quant}x{$this->getSettings()->getMoneyResultRender($nom)}, ";
            }
            $changeResultStr = substr($changeResultStr, 0, -2);

            if(count($changeResult))
                self::printStr("Получихте ресто {$this->getSettings()->getMoneyResultRender($total)} в монети от: $changeResultStr");
        } catch (\Exception $ex) {
            self::printStr($ex->getMessage(), self::OUTPUT_WARN);
        } catch (\Error $er) {
            self::printStr($er->getMessage(), self::OUTPUT_ERROR);
        }

        return $this;
    }

    public function ViewAmount() : self
    {
        try {
            self::printStr(
                "Tеĸущата Ви сума е ".$this->getSettings()->getMoneyResultRender($this->getState()->getBalance())
            );
        } catch (\Exception $ex) {
            self::printStr($ex->getMessage(), self::OUTPUT_WARN);
        } catch (\Error $er) {
            self::printStr($er->getMessage(), self::OUTPUT_ERROR);
        }

        return $this;
    }


    // Additional block
    /**
     * @return CurrencyRender|ISettings
     */
    public function getSettings(): CurrencyRender|ISettings
    {
        return $this->settings;
    }

    /**
     * @return Product[]
     */
    public function getProducts() : array
    {
        return $this->products;
    }

    /**
     * @return State
     */
    public function getState(): State
    {
        return $this->state;
    }

    /**
     * @param string $str
     * @param int $mode
     */
    private static function printStr(string $str, $mode = 0, $nLineAfter = 2) : void
    {
        if($mode == VendingMachine::OUTPUT_WARN) {
            $str = '<span style="color: red;">' . $str . '</span>';
        } elseif ($mode == VendingMachine::OUTPUT_ERROR) {
            $str = '<span style="color: red;font-weight: bold;">' . $str . '</span>';
        } elseif ($mode == VendingMachine::OUTPUT_BOLD) {
            $str = '<span style="font-weight: bold;">' . $str . '</span>';
        }

        for($i = 0; $i < $nLineAfter; $i++)
        {
            $str .= "\r\n<br/>";
        }
        echo $str;
    }
}