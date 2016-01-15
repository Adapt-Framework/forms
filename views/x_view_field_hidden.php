<?php

namespace extensions\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_hidden extends view_field{
        
        public function __construct($form_data, $user_data){
            parent::__construct($form_data, $user_data);
            $this->add_class('field input hidden');         
            $this->add(new html_input(array('type' => 'hidden', 'name' => $form_data['name'], 'value' => isset($form_data['value']) ? $form_data['value'] : $form_data['default_value'])));
        }
        
    }
    
}

?>