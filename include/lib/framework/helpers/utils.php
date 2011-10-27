<?php

/**
 * bool define_if_undefined(string $name, mixed $value[, bool $case_insensitive])
 * 
 * Define a constant if it has not already been defined.
 */
function define_if_undefined($name, $value, $case_insensitive=FALSE){
    if(!defined($name)) return define($name, $value, $case_insensitive);
}


/**
 * mixed config_value(string $name)
 * 
 * Return the value for the configuration entry with a key of $name.
 * If the configuration entry is an array, it will be searched for a key that
 * corresponds to the current action and fall-back to '_default' if none is 
 * found.
 */
function config_value($name){
    if(!array_key_exists($name, $_ENV['CONFIG']))
        return;
    
    $data = $_ENV['CONFIG'][$name];
    if(is_array($data)){
        $action = form_get_var('_action', 'index');
        if(array_key_exists($action, $data))
            return $data[$action];
        elseif(array_key_exists('_default', $data))
            return $data['_default'];
    }
    return $data;
}


/**
 * void redirect(string $location)
 * 
 * Redirect to a URL when no headers have been sent.
 */
function redirect($location, $query_vars=array()){
    if(count($query_vars)) $location .= '?'.http_build_query($query_vars);
    header("Location: ${location}");
}


/**
 * string truncate(string $str, int $len[, string $after])
 * 
 * Truncate the string to specified length and append $after.
 */
function truncate($str, $len, $after="..."){
    return (strlen($str) > $len)? substr($str, 0, $len).$after : $str;
}


/**
 * string pluralize(string $word, int $quantity)
 * 
 * Append 's' to the end of $word if quantity is greater than 1.
 */
function pluralize($word, $quantity){
    return sprintf("%d %s%s", $quantity, $word, ($quantity > 1)? 's' : '');
}


/**
 * string capfirst(string $string)
 * 
 * Capitalize the first character.
 */
function capfirst($string){
    return strtoupper(substr($string, 0, 1)).substr($string, 1);
}


/**
 * string humanize_date(string $timestamp)
 */
function humanize_date($timestamp){
    if(60 > $secs = abs(time() - strtotime($timestamp))){
        return 'just now';
    }
    elseif(60 > $minutes = round($secs / 60)){
        return sprintf("%s %s ago", $minutes, pluralize('minute', $minutes));
    }
    elseif(24 > $hours = round($minutes / 60)){
        return sprintf("%s %s ago", $hours, pluralize('hour', $hours));
    }
    elseif(30 > $days = round($hours / 24)){
        return sprintf("%s %s ago", $days, pluralize('day', $days));
    }
    elseif(12 > $months = round($days / 30)){
        return sprintf("%s %s ago", $months, pluralize('month', $months));
    }
    elseif(1 > $years = round($months / 30)){
        return sprintf("%s %s ago", $years, pluralize('year', $years));
    }
    
    return $timestamp;
}
