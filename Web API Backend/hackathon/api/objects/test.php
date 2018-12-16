<?php
require("account.php");

$acc = Account::byEmail("fhenneman@comcast.net");
var_dump($acc);
echo("<br/><br/><br/>");
echo($acc->authenticate("123456"));
?>
