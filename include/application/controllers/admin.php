<?php

abstract class AdminController extends Controller {
    
    public $layout = 'admin';
    
    protected function __before($action){
        $this->require_authentication();
    }
    
    protected function send_authentication_request(){
        header(sprintf("WWW-Authenticate: Basic realm=\"%s Administration\"", $_ENV['CONFIG']['admin']['title']));
        header('HTTP/1.1 401 Unauthorized');
    }
    
    protected function require_authentication(){
        if(!isset($_SERVER['PHP_AUTH_USER'])){
            $this->send_authentication_request();
            die();
        }
        else {
            $userpw = sprintf("%s:%s", $_SERVER['PHP_AUTH_USER'], sha1($_SERVER['PHP_AUTH_PW']));
            if(!in_array($userpw, $_ENV['CONFIG']['admin']['users'])){
                $this->send_authentication_request();
                die();
            }
        }
    }
    
    protected function paginate($entries){
        $entry_count = count($entries);
        $current_page = form_get_var('page', 1);
        $items_shown = $_ENV['CONFIG']['admin']['items_per_page'];
        $starting_entry = ($current_page * $items_shown) - $items_shown;
        
        $this->locals['page'] = $current_page;
        $this->locals['pages'] = ceil($entry_count / $items_shown);
        
        if($entry_count <= $items_shown)
            return $entries;
        
        return array_slice($entries, $starting_entry, $items_shown);
    }
    
    protected function render($name, $locals=array()){
        if(array_key_exists('form_errors', $_SESSION)){
            $this->locals['errors'] = $_SESSION['form_errors'];
            unset($_SESSION['form_errors']);
        }
        if(array_key_exists('form_messages', $_SESSION)){
            $this->locals['messages'] = $_SESSION['form_messages'];
            unset($_SESSION['form_messages']);
        }
        
        return parent::render($name, $locals);
    }
    
    protected function has_errors(){
        return array_key_exists('form_errors', $_SESSION) 
            && count($_SESSION['form_errors']);
    }
    
    protected function add_form_error($message){
        if(!$this->has_errors())
            $_SESSION['form_errors'] = array();
        
        $_SESSION['form_errors'][] = $message;
    }
    
    protected function add_form_message($message){
        if(!array_key_exists('form_messages', $_SESSION))
            $_SESSION['form_messages'] = array();
        
        $_SESSION['form_messages'][] = $message;
    }
}

