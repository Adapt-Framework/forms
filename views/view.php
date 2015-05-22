<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view extends \frameworks\adapt\view{
        
        public function __construct($tag = 'div', $data = null, $attributes = array()){
            parent::__construct($tag, $data, $attributes);
            $this->add_class('forms');
        }
        
    }

}

?>