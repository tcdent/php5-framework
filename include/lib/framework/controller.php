<?php

abstract class Controller {
    
    public $layout = 'default';
    public $locals = array();
    
    public function __construct($action=FALSE){
        if(!$action) $action = $this->get_action();
        $request_method = $this->get_request_method();
        $this->locals['controller'] = $this;
        $this->include_helpers();
        
        try {
            if($this->__before($action) === FALSE)
                return;
            
            if($this->method_is_requestable($action))
                $this->{$action}();
            elseif($this->method_is_requestable($request_method))
                $this->{$request_method}($action);
            else
                $this->__default($action);
            
            $this->__after($action);
        } catch(Exception $e){
            $this->exception($e);
        }
    }
    
    public function __call($action, $attrs){
        return $this->__default($action, $attrs);
    }
    
    protected function __default($action, $locals=array()){
        if(($path_name = $this->get_path_name()) != 'index')
            $action = "${path_name}/${action}";
        
        return $this->render($action, $locals);
    }
    
    protected function __before($action){ return; }
    protected function __after($action){ return; }
    
    protected function get_action(){
        return trim(form_get_var('_action', 'index'), '/_');
    }
    
    protected function get_request_method(){
        $method = form_post_var('_method', $_SERVER['REQUEST_METHOD']);
        if(!in_array($method, array('GET', 'POST', 'PUT', 'DELETE')))
            $method = 'GET';
        return $method;
    }
    
    /**
     * Determine if the method is suitable to be called from a request. 
     * Meaning: the method exists and is not protected.
     */
    protected function method_is_requestable($method){
        if(!method_exists($this, $method))
            return FALSE;
        $reflection = new ReflectionMethod($this, $method);
        return $reflection->isPublic();
    }
    
    protected function get_path_name(){
        $name = preg_replace("/Controller$/", "", get_class($this));
        $name = str_replace('_', '/', $name);
        return strtolower(preg_replace("/(?<=[a-z])([A-Z])/", '_$1', $name));
    }
    
    protected function get_root_path_name(){
        $path_name = $this->get_path_name();
        if(strpos($path_name, '/') === FALSE)
            return "";
        return preg_replace("/\/.+$/", "", $path_name);
    }
    
    protected function include_helpers(){
        try {
            import('helpers/'.$this->get_root_path_name().'/common');
        } catch(Exception $e){}
        
        try {
            import('helpers/'.$this->get_path_name());
        } catch(Exception $e){}
    }
    
    protected function render($name, $locals=array()){
        return render($name, array_merge($this->locals, $locals), $this->layout);
    }
    
    protected function exception($exception){
        if(DEBUG){
            $this->layout = FALSE;
            return $this->render('exception', 
                array('exception' => $exception));
        }
        elseif($exception instanceof FileNotFoundException)
            return $this->__404($exception);
        else
            return $this->__500($exception);
    }
    
    protected function __404($exception=NULL){
        header("HTTP/1.0 404 Not Found");
        $this->render('404', array('exception' => $exception));
    }
    
    protected function __500($exception=NULL){
        header("HTTP/1.0 500 Internal Server Error");
        $this->render('500', array('exception' => $exception));
    }
}

