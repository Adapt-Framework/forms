<?php

namespace adapt\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_dropdown_select_item extends \bootstrap\views\view_dropdown_menu_item{
        
        public function __construct($name, $value = null){
            parent::__construct($name);
            if (is_null($value)) $value = $name;
            $this->attr('data-value', $value);
            $this->add_class('dropdown-menu-item');
        }
        
    }
    
}


?>