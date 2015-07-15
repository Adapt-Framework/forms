<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_group_layout_simple extends view_form_page_section_group{
        
        public function __construct($form_data, $user_data){
            parent::__construct($form_data, $user_data);
        }
        
        public function add($item){
            parent::add($item);
        }
        
    }
    
}

?>