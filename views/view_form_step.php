<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_step extends view{
        
        public function __construct($title, $description){
            parent::__construct();
            $this->add(new html_label($title));
            $this->add(new html_p($description));
        }
        
    }
    
}

?>