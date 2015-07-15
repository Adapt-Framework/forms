<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model_form extends \frameworks\adapt\model{
        
        public function __construct($id = null){
            parent::__construct('form', $id);
        }
        
        /* Over-ride the initialiser to auto load children */
        public function initialise(){
            /* We must initialise first! */
            parent::initialise();
            
            /* We need to limit what we auto load */
            $this->_auto_load_only_tables = array(
                'form_page'
            );
            
            /* Switch on auto loading */
            $this->_auto_load_children = true;
        }
        
        public function get_view($user_data = array()){
            if ($this->is_loaded){
                
                $view = null;
                
                if (isset($this->custom_view) && trim($this->custom_view) != ''){
                    $class = $this->custom_view;
                    $view = new $class($this->to_hash(), $user_data);
                }else{
                    $view = new view_form($this->to_hash(), $user_data);
                }
                
                if ($view && $view instanceof \frameworks\adapt\html){
                    for($i = 0; $i < $this->count(); $i++){
                        $child = $this->get($i);
                        if (is_object($child) && $child instanceof \frameworks\adapt\model && $child->table_name == 'form_page'){
                            $view->add($child->get_view($user_data));
                        }
                    }
                }
                
                return $view;
            }
            
            return null;
        }
    }
    
}

?>