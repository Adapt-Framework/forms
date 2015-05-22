<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model_form_page_section extends \frameworks\adapt\model{
        
        public function __construct($id = null){
            parent::__construct('form_page_section', $id);
        }
        
        public function initialise(){
            parent::initialise();
            
            $this->_auto_load_only_tables = array(
                'form_page_section_field'
            );
            
            $this->_auto_load_children = true;
        }
        
        public function get_view($form_data = array()){
            $view = new view_form_page_section($this->title, $this->description, $this->repeatable == 'Yes' ? true : false, $this->min_occurances, $this->max_occurances, $this->repeated_title, $this->repeated_description);
            
            $children = $this->get();
            foreach($children as $child){
                if (is_object($child) && $child instanceof model_form_page_section_field){
                    $view->add($child->get_view($form_data));
                }
            }
            
            return $view;
        }
        
    }
    
}

?>