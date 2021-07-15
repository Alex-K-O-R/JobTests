<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/Cmplx_Math.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/Cmplx_Utils.php');

use Complex\Utilities\TestUtility;
use Complex\Math\Complex;

TestUtility::wrLine("Creation test");

$startPoint = -1.5;
for($i = 1; $i < 5; $i++){
    TestUtility::testCreationFromNumbers($startPoint, $startPoint);
    $startPoint+=$i*0.75;
}
echo "<br/>";

TestUtility::testCreationFromString('2.5i', '2.5i');
TestUtility::testCreationFromString('1 -+-  2.5i', '1 -+-  2.5i');
TestUtility::testCreationFromString('-1 -  2.5i', '-1 -  2.5i');
echo "<br/>";

$startPoint = -1.5;
for($i = 1; $i < 5; $i++){
    TestUtility::testCreationFromString($startPoint.'+'.$startPoint.'i', $startPoint.'+'.$startPoint.'i');
    $startPoint+=$i*0.75;
}
echo "<br/>";
//TODO: check case 0.750.75i > 0.75i
$startPoint = -1.5;
for($i = 1; $i < 5; $i++){
    TestUtility::testCreationFromString($startPoint.$startPoint.'i', $startPoint.$startPoint.'i');
    $startPoint+=$i*0.75;
}

echo "<br/>";
echo "<br/>";
echo "<br/>";
TestUtility::wrLine("Math test");
$input = '-1 -  2.5i';
$complex = Complex::mul(new Complex($input), 2);
echo $input.' * 2 > '.$complex->toString();
echo "<br/>";
/*TestUtility::wrLine('');
$z1 = new Complex('');
$z2 = new Complex('');
TestUtility::testMath(
    $z1
    , $z2
    , $z1->SumWith($z2)
);*/

TestUtility::wrLine('https://www.webmath.ru/poleznoe/formules_16_8.php');
$z1 = new Complex('5-6i');
$z2 = new Complex('-3+2i');
TestUtility::testMath(
    $z1
    , $z2
    , $z1->SumWith($z2)
);
TestUtility::testMath(
    $z1
    , $z2
    , $z1->DiffWith($z2)
);

TestUtility::wrLine('https://www.webmath.ru/poleznoe/formules_16_9.php');
$z1 = new Complex('2 +3i');
$z2 = new Complex('-1+ i');
TestUtility::testMath(
    $z1
    , $z2
    , $z1->MulBy($z2)
);


TestUtility::wrLine('https://www.webmath.ru/poleznoe/formules_16_10.php');
$z1 = new Complex('-2 + i');
$z2 = new Complex('1 - i');
TestUtility::testMath(
    $z1
    , $z2
    , $z1->DivideBy($z2)
);

TestUtility::wrLine('http://mathportal.net/index.php/kompleksnye-chisla/dejstviya-s-kompleksnymi-chislami');
$z1 = new Complex('3i +2');
$z2 = new Complex('3- i');
TestUtility::testMath(
    $z1
    , $z2
    , $z1->MulBy($z2)
);

//TODO: check case - why -29+22i?
$z1 = new Complex('2i +  1');
$z2 = new Complex('1- 3i');
TestUtility::testMath(
    $z1
    , $z2
    , $z1->MulBy($z1)->SumWith($z2->MulBy($z2->MulBy($z2)))
);

$z1 = new Complex('- i + 2');
$z2 = new Complex('1+i');
TestUtility::testMath(
    $z1
    , $z2
    , $z1->DivideBy($z2)
);
// ... etc

?>