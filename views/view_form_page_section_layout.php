<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page_section_layout extends view{
        
        public function __construct($layout){
            parent::__construct('div');
            $this->add_class('form-page-section-layout');
            $this->attr('data-form-page-section-layout-id', $layout['form_page_section_layout_id']);
        }
        
    }
    
}

?>