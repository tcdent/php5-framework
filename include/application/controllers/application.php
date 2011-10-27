<?php

abstract class ApplicationController extends Controller {
    
    protected function server_error($message="There was an error processing your request."){
        return $this->error("500 Internal Server Error", $message);
    }
    
    protected function not_implemented($action){
        return $this->error("501 Not Implemented", "Method '$action' not implemented.");
    }
    
    protected function not_found($message="The requested item does not exist."){
        return $this->error("404 Not Found", $message);
    }
    
    protected function bad_request($message="Bad request."){
        return $this->error("400 Bad Request", $message);
    }
}

