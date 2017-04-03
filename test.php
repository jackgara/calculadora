<?php
/**
 * Author: Joaquin Garavaglia
 *
 * Basic Test Operations
 */
require_once('clsOperation.php');
require_once('stdio.php');

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 1);
assert_options(ASSERT_QUIET_EVAL, 1);
assert_options(ASSERT_CALLBACK, 'my_assert_handler');

// Create a handler function
function my_assert_handler($file, $line, $code, $desc = null)
{
    echo "Assertion failed at $file:$line: $code";
    if ($desc) {
        echo ": $desc";
    }
    echo "\n";
}

function basicTest($str,$value){
    $op = new clsOperation($str);
    $op->Calculate();
    $res = $op->getResult();
    if(assert($res==$value)){
        echo "Testing:".$str."=".$value."...........OK \n";
    };
}

basicTest('1+1','2');
basicTest('1*3/2','1.5');
basicTest('-1+5*-2','-11');
basicTest('((3*2)-(3-4))','7');
basicTest('((3*2)-(3-4)*2)/4--1*-3','-1');
basicTest('1-(((3-4)*2-1)*2)','7');
basicTest('  8-2*(3  *( 7/-7))+2  ','16');
basicTest('1.5+2','3.5');
basicTest('(9-8*(8-9))','17');