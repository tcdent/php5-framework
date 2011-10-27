<?php

define_if_undefined('TEMPLATE_EXTENSION', 'html.php');


/**
 * string render_partial(string $_filename[, array $_locals])
 * 
 * Render a file to a string. Variables passed as locals will be extracted
 * and named using the array keys.
 */

function render_partial($_filename, $_locals=array()){
    ob_start();
    if(is_array($_locals)) extract($_locals, EXTR_SKIP);
    include(template_filename($_filename));
    $partial = ob_get_contents();
    ob_end_clean();

    return "${partial}\n";
}

/**
 * string template_filename(string $name)
 * 
 * Look for a template and return the full filename. If a direct file by the
 * name passed is not present, an index file in the directory by it's name 
 * will be tried. Templates in the APPLICATION_PATH take precedence over those
 * in the FRAMEWORK_PATH.
 */

function template_filename($name){
    $paths = array();
    if(defined('APPLICATION_PATH')) $paths[] = APPLICATION_PATH.'/templates';
    if(defined('FRAMEWORK_PATH')) $paths[] = FRAMEWORK_PATH.'/templates';

    if(!count($paths)) throw new ApplicationException(
        "No suitable include paths found for template \"${name}\"");

    foreach($paths as $path){
        foreach(array($name, "${name}/index") as $file){
            $filename = sprintf("%s/%s.%s", $path, $file, TEMPLATE_EXTENSION);
            if(file_exists($filename))
                return $filename;
        }
    }

    throw new FileNotFoundException($name);
}


/**
 * bool render_snippet(string $name[, mixed $...])
 * 
 * Render a snippet to a string. Template variables are extracted as $_n,
 * where 'n' is the position the variable was passed.
 */

function render_snippet($name){
    $locals = array();
    for($i = 1; $i < func_num_args(); $i++){
        $locals["_$i"] = func_get_arg($i);
    }

    return render_partial("snippets/${name}", $locals);
}


/**
 * void render(string $name[[, array $locals], string $layout])
 * 
 * Render a template into the layout.
 */

function render($name, $locals=array(), $layout='default'){
    $locals['yield'] = render_partial($name, $locals);
    if($layout)
        print render_partial("layouts/${layout}", $locals);
    else
        print $locals['yield'];
}

