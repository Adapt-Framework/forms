<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page_section_group extends view{
        
        protected $_container;
        protected $_controls;
        
        public function __construct($form_data, $user_data){
            $this->_container = new html_div();
            parent::__construct();
            $this->add_class('field-group');
            
            if (isset($form_data['form_page_section_group']['label']) && $form_data['form_page_section_group']['label'] != ''){
                parent::add(new html_label($form_data['form_page_section_group']['label'], array('class' => 'control-label')));
            }
            
            
            /* Add an area for field groups */
            parent::add($this->_container);
            
            if (isset($form_data['form_page_section_group']['description']) && $form_data['form_page_section_group']['description'] != ''){
                parent::add(new html_p($form_data['form_page_section_group']['description'], array('class' => 'help-block')));
            }
        }
        
        
        public function add($item){
            $this->_container->add($item);
        }
        
        public function add_control($control){
            if (is_null($this->_controls)){
                $this->_controls = new html_div(array('class' => 'controls'));
                parent::add($this->_controls);
            }
            
            $this->_controls->add($control);
        }
        
    }
    
}

?>