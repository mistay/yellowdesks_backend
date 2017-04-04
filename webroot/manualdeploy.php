<?php

$out="";
exec("cd /var/www/html/; git pull", $out);
var_dump($out);
?>
