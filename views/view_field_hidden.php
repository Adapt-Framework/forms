<?php

namespace adapt\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_hidden extends view_form_page_section_group_field{
        
        public function __construct($form_data, $data_type, &$user_data){
            parent::__construct($form_data, $data_type, $user_data);
            $this->add_class('field input hidden');
            
            $key = $form_data['name'];
            $value = isset($user_data[$key]) ? $user_data[$key] : $form['default_value'];
            
            $this->add(new html_input(array('type' => 'hidden', 'name' => $form_data['field_name'], 'value' => $value)));
        }
        
    }
    
}

?>