<?php

namespace extensions\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_hidden extends view{
        
        public function __construct($values = array()){
            parent::__construct();
            $this->add(new html_input(array('type' => 'hidden', 'name' => $values['name'], 'value' => isset($values['value']) ? $values['value'] : '')));
        }
        
    }
    
}

?>