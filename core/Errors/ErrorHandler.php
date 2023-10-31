<?php

use Core\Exceptions\ExceptionWithHTTPStatus;

ob_start();

function exceptionHandler($e)
{
    ob_end_clean();

    if ($e instanceof ExceptionWithHTTPStatus) {
        header('HTTP/1.1 ' . $e->getCode());
    } else {
        header('HTTP/1.1 500 Internal Server Error');
    }

    echo '<h1>' . $e->getMessage() . '</h1>';
    echo 'Uncaught exception class: ' . get_class($e) . '<br>';
    echo 'Message: <strong>' . $e->getMessage() . '</strong><br>';
    echo 'File: ' . $e->getFile() . '<br>';
    echo 'Line: ' . $e->getLine() . '<br>';
    echo 'Stack Trace: <br>';
    echo '<pre>';
    echo $e->getTraceAsString();
    echo '</pre>';
}

set_exception_handler('exceptionHandler');


function errorHandler($errorNumber, $errorStr, $file, $line)
{
    ob_end_clean();
    header('HTTP/1.1 500 Internal Server Error');

    switch ($errorNumber) {
        case E_USER_ERROR:
            echo "<b>ERROR</b> [$errorNumber] $errorStr<br />\n";
            echo "  Fatal error on line $line in file $file";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            echo "Aborting...<br />\n";
            exit(1);
        case E_USER_WARNING:
            echo "<b>WARNING</b> [$errorStr] $errorStr<br />\n";
            break;
        case E_USER_NOTICE:
            echo "<b>NOTICE</b> [$errorNumber] $errorStr<br />\n";
            break;
    }

    echo '<h1>' . $errorStr . '</h1>';
    echo 'File: ' . $file . '<br>';
    echo 'Line: ' . $line . '<br>';
    echo 'Stack Trace: <br>';
    echo '<pre>';
    debug_print_backtrace();
    echo '</pre>';
    exit();
}

set_error_handler('errorHandler');
