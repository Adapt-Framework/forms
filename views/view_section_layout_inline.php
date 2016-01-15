<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_section_layout_inline extends view_form_page_section_layout{
        
        public function __construct($layout){
            parent::__construct($layout);
            parent::add(new html_div(array('class' => 'form-inline')));
        }
        
        public function add($item){
            if ($item instanceof \adapt\html){
                $description = $item->find('.help-block')->text();
                $item->find('.help-block')->detach();
                if (trim($description) != ""){
                    $item->find('.form-control')->attr('title', $description);
                    $item->find('.form-control')->attr('data-toggle', 'tooltip');
                }
            }
            
            $this->find('.form-inline')->append($item);
        }
        
    }
    
}

?>