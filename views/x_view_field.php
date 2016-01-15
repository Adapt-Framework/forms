<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_field extends view{
        
        public function __construct($form_data, $user_data){
            parent::__construct();
            $this->add_class('form-page-section-group-field');
            $this->attr('data-form-page-section-group-field-id', $form_data['form_page_section_group_field_id']);
            
            $data_type = $this->data_source->get_data_type($form_data['data_type_id']);
            
            if (isset($data_type['validator'])){
                $this->attr('data-validator', $data_type['validator']);
            }
            
            if (isset($data_type['formatter'])){
                $this->attr('data-formatter', $data_type['formatter']);
            }
            
            if (isset($data_type['unformatter'])){
                $this->attr('data-unformatter', $data_type['unformatter']);
            }
            
            if (isset($data_type['max_length'])){
                $this->attr('data-max-length', $data_type['max_length']);
            }
            
            //$this->add(new html_pre(print_r($data_type, true)));
            
        }
        
        //public function add_condition($name, $operator, $value){
        //    //Not supported by fields so overridden 
        //}
        
        //public function add_on($before = true, $item){
        //    if ($this->find(".input-group")->size() == 0 && $this->find("input[type='text']")->size() > 0){
        //        /* Lets create an input group */
        //        $group = new html_div(array('class' => 'input-group'));
        //        $this->find("input[type='text']")->before($group);
        //        $control = $this->find("input[type='text']")->detach();
        //        $group->add($control->get(0));
        //    }
        //    
        //    if ($before){
        //        $this->find("input[type='text']")->before($item);
        //    }else{
        //        $this->find("input[type='text']")->after($item);
        //    }
        //}
    }
    
}

?>