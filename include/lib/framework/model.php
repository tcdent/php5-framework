<?php

abstract class Model {
    
    private $attributes = array();
    
    public function __construct($attributes=array()){
        if($attributes && is_array($attributes))
            $this->update_attributes($attributes);
    }
    
    public function __set($name, $value){
        return $this->attributes[$name] = $value;
    }
    
    public function __isset($name){
        return isset($this->attributes[$name]);
    }
    
    public function __unset($name){
        unset($this->attributes[$name]);
    }
    
    public function __get($name){
        if(preg_match("/.+__cleaned$/", $name))
            return $this->get_cleaned_attribute($name);
        
        if(array_key_exists($name, $this->attributes))
            return $this->attributes[$name];
        
        // Cache the value for subsequent requests. Call the method 
        // directly to force re-execution.
        if(method_exists($this, $name))
            return $this->attributes[$name] = $this->{$name}();
        
        return NULL;
    }
    
    public function get_cleaned_attribute($name){
        global $db;
        
        $name = preg_replace("/__cleaned$/", '', $name);
        $value = $this->__get($name);
        
        // Pass-through a custom cleaning method if it exists.
        if(method_exists($this, "clean_${name}"))
            $value = $this->{"clean_${name}"}($value);
        
        // Pass-through any database-specific string escaping.
        if(method_exists($db, 'escape'))
            $value = $db->escape($value);
        
        return $value;
    }
    
    public function update_attributes($attributes){
        foreach($attributes as $name => $value){
            $this->__set($name, $value);
        }
    }
    
    public function dump(){
        return var_export($this->attributes, TRUE);
    }
    
    public static function extend($class, $items){
        if(!is_array($items)) return NULL;
        if(!count($items)) return array();
        
        // Single item.
        if(array_is_assoc($items))
            return new $class($items);
        
        // Multiple items.
        $extended_items = array();
        foreach($items as $i => $item)
            $extended_items[$i] = new $class($item);
        
        return $extended_items;
    }
}

