<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page_section_group_condition extends view_condition{
        
        public function __construct($form_data){
            parent::__construct($form_data);
            $this->add_class('group-condition');
            
            $this->attr('data-form-page-section-group-condition-id', $form_data['form-page-section-group-condition-id']);
        }
        
    }
}

?>