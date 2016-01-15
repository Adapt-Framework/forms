<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_group_layout_simple extends view_form_page_section_group_layout{
        
        public function __construct($layout){
            parent::__construct($layout);
        }
        
        public function add($item){
            parent::add($item);
        }
        
    }
    
}

?>