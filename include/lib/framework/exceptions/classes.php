<?php

class ApplicationException extends Exception {
    public function __construct($message, $code=0){
        return parent::__construct($this->build_message($message), $code);
    }
    
    // Overwrite to build a custom error message.
    public function build_message($value){ return $value; }
}


// Raise when a method is given an inapropriate argument.
class ArgumentException extends ApplicationException {}

// Raise when an array key is not found.
class KeyException extends ApplicationException {}

// Raise when a method has not been implemented.
class NotImplementedException extends ApplicationException {}

// Raise when a file is not found.
class FileNotFoundException extends ApplicationException {
    public function build_message($filename){
        return "File \"${filename}\" does not exist.";
    }
}

