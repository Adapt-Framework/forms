<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page_section extends view{
        
        public function __construct($title = null, $description = null, $repeatable = false, $min_occurances = null, $max_occurances = null, $repeat_title = null, $repeat_description = null){
            parent::__construct();
            
            if (!is_null($title)){
                $this->add(new \extensions\bootstrap_views\view_h3($title));
            }
            
            if (!is_null($description)){
                $this->add(new \extensions\bootstrap_views\view_p($description));
            }
            
            if ($repeatable){
                $this->attr('data-min-occurances', $min_occurances);
                $this->attr('data-max-occurances', $max_occurances);
                $this->attr('data-repeat-title', $repeat_title);
                $this->attr('data-repeat-description', $repeat_description);
            }
            
        }
        
    }
    
}

?>