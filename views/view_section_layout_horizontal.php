<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_section_layout_horizontal extends view_form_page_section_layout{
        
        public function __construct($layout){
            parent::__construct($layout);
            parent::add(new html_div(array('class' => 'form-horizontal')));
        }
        
        public function add($item){
            if ($item instanceof \adapt\html){
                $item->find('.control-label')->add_class('col-sm-3');
                $item->find('.form-control,.checkbox')->wrap(new html_div(array('class' => 'col-sm-9')));
                $item->find('.help-block,.controls')->add_class('col-sm-offset-3 col-sm-9');
            }
            
            $this->find('.form-horizontal')->append($item);
        }
        
    }
    
}

?>