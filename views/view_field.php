<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_field extends view{
        
        public function __construct($form_data, $user_data){
            parent::__construct();
            //parent::add(new html_pre(print_r($user_data, true)));
        }
        
        public function add_condition($name, $operator, $value){
            //Not supported by fields so overridden 
        }
        
        public function add_on($before = true, $item){
            if ($this->find(".input-group")->size() == 0 && $this->find("input[type='text']")->size() > 0){
                /* Lets create an input group */
                $group = new html_div(array('class' => 'input-group'));
                $this->find("input[type='text']")->before($group);
                $control = $this->find("input[type='text']")->detach();
                $group->add($control->get(0));
            }
            
            if ($before){
                $this->find("input[type='text']")->before($item);
            }else{
                $this->find("input[type='text']")->after($item);
            }
        }
    }
    
}

?>