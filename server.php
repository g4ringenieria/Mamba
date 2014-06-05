<?php

if (php_sapi_name() == "cli")
{
    require_once ("../NeoPHP2/sources/bootstrap.php");
    $app = new NeoGroup\ServerApplication();
    $app->start();
}

?>