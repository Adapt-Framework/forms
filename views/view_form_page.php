<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page extends view{
        
        protected $_controls;
        protected $_container;
        
        public function __construct($form_data, $user_data){
            
            $this->_container = new html_div();
            parent::__construct();
            
            //$this->add(new html_pre(print_r($form_data, true)));
            /* Add the error panel */
            parent::add(new html_div(array('class' => 'error-panel')));
            
            
            
            /* Add a container for sections */
            parent::add($this->_container);
            
            
            if (isset($form_data['form_page']['step_title']) && trim($form_data['form_page']['step_title']) != ""){
                $this->add(new html_h2($form_data['form_page']['step_title']));
            }
            
            if (isset($form_data['form_page']['step_description']) && trim($form_data['form_page']['step_description']) != ""){
                $this->add(new html_h2($form_data['form_page']['step_description']));
            }
            
            if (isset($form_data['form_page']['title']) && trim($form_data['form_page']['title']) != ""){
                $this->add(new html_h2($form_data['form_page']['title']));
            }
            
            if (isset($form_data['form_page']['description']) && trim($form_data['form_page']['description']) != ""){
                $this->add(new html_h2($form_data['form_page']['description']));
            }
            
            /* Add the id */
            $this->set_id();
            
            
            
            /* Add a place for controls */
            $this->_controls = new html_div(array('class' => 'controls text-right'));
            parent::add($this->_controls);
        }
        
        public function add($item){
            $this->_container->add($item);
        }
        
        public function add_control($control){
            $this->_controls->add($control);
        }
        
        
        
    }
    
}

?>