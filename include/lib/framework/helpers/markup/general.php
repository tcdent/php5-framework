<?php

define('RE_URL_SEARCH', "@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@");


/**
 * string cdata(string $string[, string $default_value])
 * 
 * Wrap a string in CDATA tags unless it is empty.
 */
function cdata($string, $default='null'){
    return empty($string)? $default : sprintf("<![CDATA[%s]]>", $string);
}

/**
 * string link_urls(string $string[, array $args])
 *
 * Convert valid URLs in a string into hyperlinks.
 */
function link_urls($string, $args=array()){
    $tag = tag('a', "$1", array_merge(array('href' => "$1"), $args));
    return preg_replace(RE_URL_SEARCH, $tag, $string);
}

/**
 * string tag(string $tag[, string $content[, array $args]])
 *
 * Build an HTML tag. 
 * 
 * tag('a', "Click", array('href' => "http://foo.com", 'target' => '_blank'));
 * // => "<a href="http://foo.com" target="_blank">Click</a>"
 */
function tag($tag, $content=NULL, $args=array()){
    $return = "<${tag}";
    foreach($args as $key => $value){
        $return .= " ${key}=\"${value}\"";
    }
    return $return .= ($content === NULL)? ">\n" : ">${content}</${tag}>\n";
}

function link_tag($link, $text=NULL, $query_vars=array(), $args=array()){
    if(count($query_vars)) $link .= '?'.http_build_query($query_vars);
    return tag('a', ($text? $text : $link), array_merge(array('href' => $link), $args));
}

function javascript_import_tag($script){
    return tag('script', '', array(
        'src' => "${script}.js", 
        'type' => "text/javascript", 
        'charset' => "utf-8"
    ));
}

function stylesheet_link_tag($stylesheet, $media='screen'){
    return tag('link', NULL, array(
        'href' => "${stylesheet}.css", 
        'rel' => "stylesheet", 
        'media' => $media, 
        'type' => "text/css", 
        'charset' => "utf-8"
    ));
}
