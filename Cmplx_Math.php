<?php
namespace Complex\Math;

class ComplexMath {
    public static function sum(Complex $z1, Complex $z2){
        return new Complex($z1->getReal()+$z2->getReal(), $z1->getComplex()+$z2->getComplex());
    }

    public static function diff(Complex $z1, Complex $z2){
        return new Complex($z1->getReal()-$z2->getReal(), $z1->getComplex()-$z2->getComplex());
    }

    //TODO: No overload in php7
    public static function mul(Complex $z1, $z2){
        if(is_numeric($z2)){
            return new Complex($z1->getReal() * $z2, $z1->getComplex() * $z2);
        } else {
            return new Complex(
                $z1->getReal()*$z2->getReal() - $z1->getComplex()*$z2->getComplex()
                , $z1->getReal()*$z2->getComplex() + $z2->getReal()*$z1->getComplex()
            );
        }
    }
    //TODO:  No overload in php7
    public static function div(Complex $z1, $z2){
        if(is_numeric($z2)){
            return self::mul($z1, 1/$z2);
        } else {
            $dvsr = pow($z2->getReal(), 2) + pow($z2->getComplex(), 2);
            if($dvsr == 0) return null;
            return new Complex(
                ($z1->getReal()*$z2->getReal() + $z1->getComplex()*$z2->getComplex()) / $dvsr
                , ($z1->getComplex()*$z2->getReal() - $z1->getReal()*$z2->getComplex()) / $dvsr
            );
        }
    }
}

class Complex extends ComplexMath{
    private $vector = array(0, 0);

    function __construct()
    {
        $arguments = func_get_args();
        if(count($arguments) > 0){
            $this->tryConstructFromDoubles($arguments);
            $this->tryConstructFromString($arguments);
        }
    }


    private function tryConstructFromDoubles($arguments)
    {
        if(isset($arguments[0])&& ($a = $arguments[0]) !== null && isset($arguments[1]) && ($b = $arguments[1]) !== null){
            if(is_numeric($a) && is_numeric($b)) {
                $this->setReal($a);
                $this->setComplex($b);
            }
        }
    }

    private function tryConstructFromString($arguments)
    {
        if(isset($arguments[0])&& ($a = $arguments[0]) !== null){
            if(is_string($a)){
                $a = trim($a);
                $a = str_ireplace(' ', '', $a);


                $b = $a;
                if($b[0]!='-') {
                    $b = '+'.$b;
                }

                //preg_match(
                //'/^(?=[iI.\\d+-])([+-]?(?:\\d+(?:\\.\\d*)?|\\.\\d+)(?:[eE][+-]?\\d+)?(?![iI.\\d]))?([+-]?(?:(?:\\d+(?:\\.\\d*)?|\\.\\d+)(?:[eE][+-]?\\d+)?)?[iI])?$/'
                //'/^(?:(?<real>\d+(?:(?:\.\d+)?(?:e[+\-]\d+)?)?)?(?:[+\-]))?(?<imaginary>\d+(?:(?:\.\d+)?(?:e[+\-]\d+)?)?)?[iI]$/'
                //'/(?:(?<real>\d+(?:(?:\.\d+)?(?:e[+\-]\d+)?)?)?(?:\s?[+\-]\s?))?(?<imaginary>\d+(?:(?:\.\d+)?(?:e[+\-]\d+)?)?)?([iI])\s*/'
                //'/([0-9]*[.]?[0-9]+)*([+-])*([0-9]*[.]?[0-9]+i{1})*i/'
                //'/([-+]?\d+\.?\d*|[-+]?\d*\.?\d+)\s*\+\s*([-+]?\d+\.?\d*|[-+]?\d*\.?\d+)i /'
                //    , $a, $resArr);
                //print_r($arguments);
                //print_r($this->vector);
                // no way...
                $this->extractParts($b);
            }
        }
    }

    // Get every part of number from string
    private function extractParts(&$str){
        $cmplI = $this->extractPart($str, 'i');
        $real = $str;

        if($real == '' || count(mb_split('[+-]{1}', $real))==2){
            //print_r(array($real, $cmplI));
            $this->setReal($real);
            if($cmplI != '') $this->setComplex($cmplI);
        }
    }

    // Get specific part of number by anchor (i), the rest what was left should be a correct real part
    private function extractPart(&$str, $partMarker){
        $rBorder = mb_stripos($str, $partMarker);
        if($rBorder){
            $lBorder = $rBorder;
            while($lBorder > 0 && $str[$lBorder]!='+' && $str[$lBorder]!='-') {$lBorder--;}
            $result = mb_substr ($str, $lBorder, $rBorder+1);
            $part1 = mb_substr ($str, 0, $lBorder);
            $part2 = mb_substr ($str, $rBorder+1, mb_strlen($str));
            $str = $part1.$part2;
            return $result;
        }
    }

    private function setReal($a){
        return $this->vector[0] = floatval($a);
    }

    private function setComplex($b){
        $b = str_ireplace('i', '', $b);
        if ($b=='+') $b=1;
        if ($b=='-') $b=-1;
        return $this->vector[1] = floatval($b);
    }

    public function getReal(){
        return $this->vector[0];
    }

    public function getComplex(){
        return $this->vector[1];
    }

    public function toString(){
        return ($this->getReal()== 0 && $this->getComplex() == 0)?0:(
            ($this->getReal()==0?'':$this->getReal()).($this->getComplex()==0?'':(($this->getReal()!=0 && $this->getComplex()>0)?'+':'').(abs($this->getComplex())==1?($this->getComplex()<0?'-':''):$this->getComplex()).'i')
        );
    }

    public function SumWith(Complex $z2){
        return ComplexMath::sum($this, $z2);
    }

    public function DiffWith(Complex $z2){
        return ComplexMath::diff($this, $z2);
    }

    public function MulBy(Complex $z2){
        return ComplexMath::mul($this, $z2);
    }

    public function DivideBy(Complex $z2){
        return ComplexMath::div($this, $z2);
    }
}
?>