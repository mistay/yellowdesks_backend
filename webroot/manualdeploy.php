<?php


$out="";
//exec("cd /var/www/html/; git pull", $out);
exec("/opt/deploy.sh", $out);
var_dump($out);
?>
