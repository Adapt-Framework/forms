<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page_condition extends view_condition{
        
        public function __construct($form_data){
            parent::__construct($form_data);
            $this->add_class('page-condition');
            
            $this->attr('data-form-page-condition-id', $form_data['form-page-condition-id']);
        }
        
    }
}

?>