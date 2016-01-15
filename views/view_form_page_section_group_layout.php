<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page_section_group_layout extends view{
        
        public function __construct($layout){
            parent::__construct('div');
            
            $this->attr('data-form-page-section-group-layout-id', $layout['form_page_section_group_layout_id']);
        }
        
    }
    
}

?>