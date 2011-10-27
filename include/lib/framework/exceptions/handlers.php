<?php

// Route all errors through an ErrorException.
function error_handler($number, $message, $file, $line){
    throw new ErrorException($message, 0, $number, $file, $line);
}

