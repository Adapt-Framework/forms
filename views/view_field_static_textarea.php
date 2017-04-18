<?php

namespace adapt\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_static_textarea extends view_form_page_section_group_field{
        
        public function __construct($form_data, $data_type, &$user_data){
            parent::__construct($form_data, $data_type, $user_data);
            $this->add_class('field static-textarea');
            
            $this->add(\adapt\xml::unescape($form_data['description']));
        }
        
    }
    
}

?>
