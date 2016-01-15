<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_section_layout_standard extends view_form_page_section_layout{
        
        public function __construct($layout){
            parent::__construct($layout);
            
            parent::add(new html_div(array('class' => 'row')));
        }
        
        public function add($item){
            if ($item instanceof \adapt\html){
                $item->add_class('col-xs-12');
            }
            $this->find('.row')->append($item);
        }
        
    }
    
}

?>