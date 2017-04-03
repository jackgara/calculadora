<?php

/**
 * Author: Joaquin Garavaglia
 *
 * Class to manage Operations
 */

class clsOperation{

    private $strOp ;
    private $isSingle;
    private $result;

    public function __construct($strOperation){

        $this->strOp = $strOperation;
        $this->isSingle = $this->isSingle();
    }

    public function Calculate(){

        if($this->isSingle){
            $this->result= clsOperation::calcSimple($this->strOp);
        }else{
            //Split Operation by ()
            $recursive='/(\((?>[^()]|(?R))*\)?)/';
            $arrOp = preg_split($recursive,$this->strOp,-1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

            //Solve Recursive Operations
            foreach($arrOp as $i=>$strOp){
                if(preg_match($recursive,$strOp)){
                    $strOp=preg_replace('/(^\s*\()|(\)\s*$)/','',$strOp );//Removing Outer Brackets
                    $subOp= new clsOperation($strOp); //Recursion
                    if($subOp->Calculate()){
                        $arrOp[$i]=$subOp->getResult();
                    }else{
                        try{
                           throw  new Exception('Calculating Recursive Operands.');

                        }catch( Exception $e){
                            echo 'Exception captured: ',  $e->getMessage(), "\n";
                            exit(1);
                        }
                    }
                }
            }
            //Implode Operation to Array and CalculateSimple
            $strOp=implode("",$arrOp);
            $this->result= clsOperation::calcSimple($strOp);
        }
        return true;
    }

    public function WriteResult(){
        echo "RESULTADO : ".$this->result;
        return true;
    }
    /**
     * Function determine if the Operation is simple with no (..)
     */
    private function isSingle(){
        $singOp='/[\d]+([\+\-\/\*][\d]+)*/';
        preg_match($singOp ,$this->strOp,$match);
        if(empty($match)){
            try {
                throw  new Exception('Single Operand');
            }catch(Exception $e){
                echo 'Exception captured: ',  $e->getMessage(), "\n";
                exit(1);
            }
        }
        return strlen($match[0]) == strlen($this->strOp);

    }
    function getResult(){
        return $this->result;
    }

    /**
     * Function to Calculate simple Operations
     * Asumes a right syntax Operation with no ()
     */
    function calcSimple($strOp){
        //Split by + & - taking care of the sign (-)
        $arrOp = preg_split('/(?<=[^\-\+\/\*])([\+\-])/',$strOp, -1, PREG_SPLIT_DELIM_CAPTURE| PREG_SPLIT_NO_EMPTY);

        //Split againg by * & / to solve those operations in subarrays
        foreach ($arrOp as $i=>$Op){
            $arrSubOp = preg_split('/([\*\/])/',$Op, -1, PREG_SPLIT_DELIM_CAPTURE| PREG_SPLIT_NO_EMPTY);
            $arrOp[$i]=clsOperation::calcMulDiv($arrSubOp);
        }
        $res=clsOperation::calcSumRest($arrOp);
        return $res;
    }

    /**
     * Function to Calculate Sum and Rest from an array
     * Takes care of the sign
     */
    function calcSumRest($arrOp)
    {
        $res = $arrOp[0];
        for ($i = 1; $i < count($arrOp); $i++) {
            switch ($arrOp[$i - 1]) {
                case '+':
                    if ($arrOp[$i] != '-') {
                        $res += ($arrOp[$i]);
                    } //change sign
                    else {
                        $arrOp[$i] = '-';
                    }
                    $i++;
                    break;
                case '-':
                    if ($arrOp[$i] != '-') {
                        $res -= ($arrOp[$i]);
                    } //change sign
                    else {
                        $arrOp[$i] = '+';
                    }
                    $i++;
                    break;

            }
        }
        return $res;
    }
    /**
     * Function to Calculate Mul and Divs from an array
     */
    function calcMulDiv($arrOp){

        $res = $arrOp[0];
        for ($i=1;$i<count($arrOp);$i++) {
            switch ($arrOp[$i-1]) {
                case '*':
                    $res *= $arrOp[$i];
                    $i++;
                    break;
                case '/':
                    try{
                        if($arrOp[$i]==0){throw  new Exception('Division by zero.');}
                        $res /= $arrOp[$i];
                    }catch( Exception $e){
                        echo 'Exception captured: ',  $e->getMessage(), "\n";
                        exit(1);
                    }
                    $i++;
                    break;
            }
        }
        return $res;
    }

}

