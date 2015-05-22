<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page extends view{
        
        public function __construct($title = null, $description = null, $step_label = null, $step_description = null){
            parent::__construct();
            if (!is_null($title)) $this->add(new \extensions\bootstrap_views\view_h2($title));
            if (!is_null($description)) $this->add(new \extensions\bootstrap_views\view_p($description));
            if (!is_null($step_label)) $this->attr('data-step-label', $step_label);
            if (!is_null($step_description)) $this->attr('data-step-description', $step_description);
            
            $this->set_id();
            
            $this->add(new html_div(array('class' => 'error-panel')));
        }
        
    }
    
}

?>