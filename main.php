<?php
/**
 * Author: Joaquin Garavaglia
 *
 */

require_once('clsOperation.php');
require_once('stdio.php');

/*-------------------------------------
Getting the User input
-------------------------------------*/
$input = ReadStdin("Operation : ");

/*-------------------------------
Creating the Word instance
-------------------------------*/

$op = new clsOperation($input);
/*-------------------------------
Querying the DB
-------------------------------*/
    $op->Calculate();
    $op->WriteResult();

?>