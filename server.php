<?php

if (php_sapi_name() == "cli")
{
    require_once ("../NeoPHP2/sources/bootstrap.php");
    NeoGroup\ServerApplication::getInstance()->start();
}

?>