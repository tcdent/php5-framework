<?php

/**
 * Available Attributes
 *   name:      The original name of the file on the client machine.
 *   tmp_name:  The temporary filename of the file in which the uploaded file 
 *              was stored on the server.
 *   extension: The original filename's extension.
 *   size:      The size, in bytes, of the uploaded file.
 *   type:      The mime type of the file, if the browser provided this information. 
 *   error:     The error code associated with this file upload.
 *              **Does not take into account restricted file extensions.**
 *   error_description: A human error message.
 */

class Upload {
    
    const EXTENSIONS_IMAGE = 'jpg, jpeg, gif, png';
    
    public $form_name;
    public $form_data = array();
    public $allowed_extensions = array();
    
    public function __construct($form_name){
        $this->form_name = $form_name;
        if(array_key_exists($this->form_name, $_FILES))
            $this->form_data = $_FILES[$this->form_name];
    }
    
    public function was_uploaded(){
        return $this->error !== UPLOAD_ERR_NO_FILE;
    }
    
    public function allow_extensions(){
        $extensions = func_get_args();
        if(count($extensions) == 1)
            $extensions = $this->parse_extensions(array_shift($extensions));
        $this->allowed_extensions = $extensions;
    }
    
    protected function parse_extensions($extensions){
        if(is_array($extensions))
            return $extensions;
        return array_map('trim', explode(',', $extensions))
    }
    
    public function is_allowed_extension(){
        if(!count($this->allowed_extensions))
            return TRUE;
        return in_array($this->extension(), $this->allowed_extensions);
    }
    
    public function is_image(){
        if(!in_array($this->extension, $this->parse_extensions(self::EXTENSIONS_IMAGE)))
            return FALSE;
        
        $content = file_get_contents($this->tmp_name);
        $info = new finfo(FILEINFO_MIME);
        return strncmp($info->buffer($content), 'image', strlen('image'));
    }
    
    public function move_to($destination){
        return move_uploaded_file($this->tmp_name, $destination);
    }
    
    public function __get($name){
        if(method_exists($this, "get_${name}"))
            return $this->{"get_${name}"}();
        
        if(array_key_exists($name, $this->form_data))
            return $this->form_data[$name];
        
        return NULL;
    }
    
    protected function get_extension(){
        return strtolower(substr($this->name, (strpos($this->name, '.') + 1)));
    }
    
    protected function get_size(){
        if(!$this->was_uploaded())
            return NULL;
        
        if(empty($this->form_data['size']))
            return filesize($this->tmp_name);
        
        return $this->form_data['size'];
    }
    
    protected function get_error_description(){
        if(!$this->is_allowed_filetype()){
            return sprintf("%s is not an allowed file extension. File must be %s.", 
                $this->extension(), implode_or($this->allowed_filetypes));
        }
        
        switch($this->error){
            case UPLOAD_ERR_OK:
                return FALSE;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return "The uploaded file exceeds the maximum allowed file size.";
            case UPLOAD_ERR_PARTIAL:
                return "The uploaded file was only partially uploaded.";
            case UPLOAD_ERR_NO_FILE:
                return "No file was uploaded.";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "No temporary directory is available.";
            case UPLOAD_ERR_CANT_WRITE:
                return "Failed to write file to disk.";
            case UPLOAD_ERR_EXTENSION:
                return "A PHP extension stopped the file upload.";
        }
        
        return FALSE;
    }
}

