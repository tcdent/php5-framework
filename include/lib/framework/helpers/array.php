<?php

/**
 * mixed array_value(array $array, mixed $key[, mixed $...])
 * 
 * PHP doesn't let you do anything fancy like bar()[0]['baz'] with the return 
 * value of a function if it is an array, so this lets you walk through 
 * without first assigning it. Keys can be passed before or after the array 
 * for legibility.
 *
 *   $array = array('foo' => 'bar');
 *   array_value('foo', $array);
 *   // => "bar"
 *
 *   $array = array('foo' => array('bar' => 'baz'));
 *   array_value('foo', 'bar', $array);
 *   // => "baz"
 * 
 *   array_value($array, 'foo', 'bar');
 *   // => "baz"
 */

function array_value(){
    $args = func_get_args();
    $array = is_array($args[0])? array_shift($args) : array_pop($args);
    
    if(!is_array($array))
        throw new ArgumentException("First or last parameter must be array.");
    
    $value = $array;
    foreach($args as $i => $key){
        if(array_key_exists($key, $value))
            $value = $value[$key];
        else
            throw new KeyException("Key \"$key\" does not exist at level $i.");
    }
    
    return $value;
}

/**
 * mixed array_pluck(mixed $key, array $array)
 * 
 * Fetch the same key from a collection of arrays.
 * 
 *   $array = array(array('foo' => 'bar'), array('foo' => 'baz'));
 *   array_pluck('foo', $array);
 *   // => array('bar', 'baz')
 */

function array_pluck($key, $array){
    if(!is_array($array))
        throw new ArgumentException("Second parameter must be array.");
    
    return array_map('array_value', $array, array_fill(0, count($array), $key));
}

/**
 * array array_fill_keys( array $keys, mixed $value)
 * 
 * Fills an array with the value of the value parameter, using the values of 
 * the keys array as keys.
 * Replicates the built-in function for PHP versions < 5.2
 */
if(!function_exists('array_fill_keys')){
    function array_fill_keys(array $keys, $value=NULL){
        return array_combine($keys, array_fill(0, count($keys), $value));
    }
}

/**
 * mixed array_unset(mixed $key, array $array)
 * 
 * Unset an array value by key and return the value.
 * 
 *   $array = array(foo' => 'bar', 'baz' => 'bat');
 *   array_unset('foo', $array);
 *   // => "bar"
 *   var_dump($array);
 *   // => array('baz' => 'bat')
 */

function array_unset($key, &$array){
    if(!is_array($array))
        throw new ArgumentException("Second parameter must be array.");
    
    $value = array_value($array, $key);
    unset($array[$key]);
    return $value;
}

/**
 * bool array_is_assoc(array $array)
 * 
 * Is the array associative?
 */

function array_is_assoc($array){
    return is_array($array) && !(array_keys($array) === range(0, count($array) -1));
}

/**
 * array array_key_sort(array $array[, int $sort_flags])
 * 
 * Sort an array by key and return a copy.
 */

function array_key_sort($array, $sort_flags=NULL){
    return ksort($array, $sort_flags)? $array : FALSE;
}

/**
 * array array_shuffle(array $array)
 * 
 * Shuffle and array and preserve the keys if it is associative.
 * Note: Returns a copy of the array, unlike the built-in "shuffle".
 */
function array_shuffle($array){
    if(!array_is_assoc($array)){
        shuffle($array);
        return $array;
    }
    
    $return = array();
    $keys = array_keys($array);
    shuffle($keys);
    
    foreach($keys as $key){
        $return[$key] = $array[$key];
    }
    
    return $return;
}

/**
 * array array_to_string(array $query[, string $primary_sep='&'[, string $secondary_sep='=']])
 * 
 * Flatten an array into a string separating key+value pairs with 
 * $primary_sep and keys and values with $secondary_sep.
 */
function array_to_string($array, $primary_sep='&', $secondary_sep='='){
    if(!is_array($array))
        throw new ArgumentException("First parameter must be array.");
    
    if(!array_is_assoc($array))
        return implode($primary_sep, $array);
    
    $return = '';
    $sep = '';
    foreach($array as $key => $value){
        $return .= $sep.$key.$secondary_sep.$value;
        $sep = $primary_sep;
    }
    return $return;
}

/**
 * array array_to_query_string(array $query[, bool $urlencode=TRUE])
 * 
 * Join the array elements together in query string format.
 * urlencode() values by default.
 */
function array_to_query_string($query, $urlencode=TRUE){
    if(!is_array($query))
        throw new ArgumentException("First parameter must be array.");
    
    if($urlencode) $query = array_map('urlencode', $query);
    return '?'.array_to_string($query);
}

/**
 * array join_path(array $array[, string $separator=DIRECTORY_SEPARATOR])
 * 
 * Join the array elements together with the specified separator.
 */
function join_path($array, $separator=DIRECTORY_SEPARATOR){
    if(!is_array($array))
        throw new ArgumentException("First parameter must be array.");
    
    $trim_slashes = create_function('&$v, $k, $s', '$v = trim($v, $s);');
    array_walk($array, $trim_slashes, $separator);
    return implode(array_values($array), $separator);
}

function implode_or($array){
    $string = implode(', ', $array);
    return substr_replace($string, ' or ', strrpos($string, ', '), 2);
}

function implode_and($array){
    $string = implode(', ', $array);
    return substr_replace($string, ' and ', strrpos($string, ', '), 2);
}


