<?php

require_once (__DIR__."/../NeoPHP2/sources/bootstrap.php");
NeoPHP\ClassLoader::getInstance()->addIncludePath(__DIR__."/app");
NeoGroup\CLIApplication::getInstance()->start();

?>