<?php

function form_var($key, $source='REQUEST'){
    // Return the key $var in the $source group of passed variables if it exists, FALSE otherwise.
    
    try {
        switch($source){
            case 'SESSION': return array_value($key, $_SESSION);
            case 'COOKIE':  return array_value($key, $_COOKIE);
            case 'FILES':   return array_value($key, $_FILES);
            case 'POST':    return array_value($key, $_POST);
            case 'GET':     return array_value($key, $_GET);
            default:        return array_value($key, $_REQUEST);
        }
    } catch(KeyException $e){
        return FALSE;
    }
}

function form_var_or($key, $default, $source){
    $contents = form_var($key, $source);
    if($contents === FALSE || empty($contents))
        return $default;
    
    return $contents;
}

function form_post_var($key, $default=NULL){
    return form_var_or($key, $default, 'POST');
}

function form_get_var($key, $default=NULL){
    return form_var_or($key, $default, 'GET');
}

