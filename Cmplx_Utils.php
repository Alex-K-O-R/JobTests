<?php
namespace Complex\Utilities;
use Complex\Math\Complex;

class TestUtility {
    public static function testCreationFromNumbers($a, $b){
        $complex = new Complex($a, $b);
        echo "[$a, $b] > ".$complex->toString();
        echo "<br/>";
    }

    public static function testCreationFromString($input, $outputComment = ''){
        $complex = new Complex($input);
        echo $outputComment.' > '.$complex->toString();
        echo "<br/>";
    }

    public static function testMath(Complex $arg1, Complex $arg2, $mathOp){
        echo $arg1->toString().' [x] '.$arg2->toString().' > ';
        if (is_numeric($mathOp) || $mathOp == null) echo $mathOp;
        else
            echo $mathOp->toString();
        echo "<br/>";
    }

    public static function wrLine($input){
        echo $input;
        echo "<br/>";
    }
}
?>