<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_section_layout_standard extends view_form_page_section{
        
        public function __construct($form_data, $user_data){
            parent::__construct($form_data, $user_data);
        }
        
        public function add($item){
            if ($item instanceof \frameworks\adapt\html){
                $item->add_class('col-xs-12');
            }
            parent::add($item);
        }
        
    }
    
}

?>