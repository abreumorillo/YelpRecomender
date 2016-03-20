<?php

require_once('vendor/autoload.php');

    function exception_handler($exception)
    {
        http_response_code(500);
        echo  "Uncaught exception: " , $exception->getMessage(), "\n";
    }
    set_exception_handler('exception_handler');