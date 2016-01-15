<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page_section_group_field extends view{
        
        protected $_form_data;
        protected $_user_data;
        protected $_user_value;
        
        public function __construct($form_data, $data_type, &$user_data){
            parent::__construct('div');
            $this->add_class('form-page-section-group-field');
            $this->attr('data-form-page-section-group-field-id', $form_data['form_page_section_group_field_id']);
            $this->attr('data-data-type-id', $data_type['data_type_id']);
            $this->attr('data-form-field-type-id', $form_data['form_field_type_id']);
            $this->_user_data = $user_data;
            $this->_form_data = $form_data;
            
            $name = $form_data['name'];
            $keys = array($name);
            
            if (substr($name, strlen($name) - 2) == "[]"){
                $keys[] = substr($name, 0, strlen($name) - 2);
                
            }
            
            for($i = 0; $i < count($user_data); $i++){
                if ($user_data[$i]['used'] == false && in_array($user_data[$i]['key'], $keys)){
                    $this->_user_value = $user_data[$i]['value'];
                    $user_data[$i]['used'] = true;
                    break;
                }
            }
        }
        
        public function pget_user_value(){
            return $this->_user_value;
        }
        
        
        public function add_addon($item, $before = true){
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