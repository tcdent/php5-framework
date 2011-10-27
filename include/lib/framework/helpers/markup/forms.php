<?php

function form_options_tag($name, $options, $selected_name=NULL, $size=NULL){
    // Writes <select> tag and <option>s inside using supplied array.
    
    $string = "<select name=\"$name\" id=\"$name\"";
    if($size) $string .= " multiple size=\"$size\"";
    $string .= ">\n";

    foreach($options as $key => $val){
        if($key == $selected_name || (is_array($selected_name) 
            && in_array($key, array_keys($selected_name)))){
            $string .= tag('option', $val, array(
                'value' => $key, 
                'selected' => 'selected'));
        }
        else {
            $string .= tag('option', $val, array('value' => $key));
        }
    }

    return $string."</select>";
}

function form_input_tag($name, $value='', $type='text'){
    // Create a form <input> tag.
    return tag('input', NULL, array(
        'id' => $name, 
        'name' => $name, 
        'type' => $type, 
        'value' => $value));
}
