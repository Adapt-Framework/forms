<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page_section extends view{
        
        protected $_container;
        protected $_controls;
        
        public function __construct($form_data, $user_data){
            /* Add an area for field groups */
            $this->_container = new html_div();
            parent::__construct();
            
            
            $this->add_class('row');
            $this->add_class('section');
            
            if (isset($form_data['form_page_section']['title']) && $form_data['form_page_section']['title'] != ''){
                parent::add(new html_h3($form_data['form_page_section']['title'], array('class' => 'first-only col-xs-12')));
            }
            
            if (isset($form_data['form_page_section']['description']) && $form_data['form_page_section']['description'] != ''){
                parent::add(new html_p($form_data['form_page_section']['description'], array('class' => 'first-only col-xs-12')));
            }
            
            if (strtolower($form_data['form_page_section']['repeatable']) == 'yes'){
                if (isset($form_data['form_page_section']['repeatable_title']) && $form_data['form_page_section']['repeatable_title'] != ''){
                    parent::add(new html_h3($form_data['form_page_section']['repeatable_title'], array('class' => 'only-after-first col-xs-12')));
                }
                
                if (isset($form_data['form_page_section']['repeated_description']) && $form_data['form_page_section']['repeated_description'] != ''){
                    parent::add(new html_p($form_data['form_page_section']['repeated_description'], array('class' => 'only-after-first col-xs-12')));
                }
                
                if (isset($form_data['form_page_section']['min_occurances']) && trim($form_data['form_page_section']['min_occurances']) != ''){
                    $this->attr('data-min-occurances', $form_data['form_page_section']['min_occurances']);
                }
                
                if (isset($form_data['form_page_section']['max_occurances']) && trim($form_data['form_page_section']['max_occurances']) != ''){
                    $this->attr('data-max-occurances', $form_data['form_page_section']['max_occurances']);
                }
                
                if (isset($form_data['form_page_section']['occurs_until']) && trim($form_data['form_page_section']['occurs_until']) != ''){
                    $this->attr('data-occurs-until', $form_data['form_page_section']['occurs_until']);
                }
            }
            
            parent::add($this->_container);
            
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