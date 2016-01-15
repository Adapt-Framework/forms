<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_group_layout_split extends view_form_page_section_group{
        
        protected $_cell1;
        protected $_cell2;
        
        public function __construct($form_data, $user_data){
            $this->_cell1 = new html_div(array('class' => 'col-sm-6'));
            $this->_cell2 = new html_div(array('class' => 'col-sm-6'));
            parent::__construct($form_data, $user_data);
            $this->_container->add_class('row');
            parent::add(array($this->_cell1, $this->_cell2));
        }
        
        public function add($item){
            if ($this->_cell1->count() == 0){
                $this->_cell1->add($item);
            }else{
                $this->_cell2->add($item);
            }
        }
        
    }
    
}

?>