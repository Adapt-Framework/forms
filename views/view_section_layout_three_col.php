<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_section_layout_three_col extends view_form_page_section_layout{
        
        public function __construct($layout){
            parent::__construct($layout);
            parent::add(new html_div(array('class' => 'row')));
            $this->attr('data-can-reflow', 'Yes');
            $this->attr('data-items-per-row', '3');
        }
        
        public function add($item){
            if ($item instanceof \adapt\html){
                if (!$item->has_class('hidden')){
                    $item->add_class('col-xs-12 col-md-4');
                }
                
            }
            
            $children = $this->find('.row')->last()->get(0);
            if ($children instanceof \adapt\html){
                $children = $children->get();
            }
            
            $visable = 0;
            
            foreach($children as $child){
                if (!$child instanceof \adapt\html || !$child->has_class('hidden')) $visable++;
            }
            
            if ($visable >= 3){
                $row = new html_div($item, array('class' => 'row'));
                parent::add($row);
            }else{
                $this->find('.row')->last()->append($item);
            }
        }
        
    }
    
}

?>