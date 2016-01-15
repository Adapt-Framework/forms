<?php

namespace adapt\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_static extends view_form_page_section_group_field {
    //class view_field_static extends \extensions\bootstrap_views\view_form_group{
        
        public function __construct($form_data, $data_type, &$user_data){
        //public function __construct($values = array()){
            
            parent::__construct($form_data, $data_type, $user_data);
            
            $control = new \bootstrap\views\view_input_static($this->user_value);
            $label = isset($values['label']) ? $values['label'] : null;
            $description = isset($values['description']) ? $values['description'] : null;
            
            //parent::__construct($control, $label, $description);
            
            $form_group = new \bootstrap\view\from_group($control, $label, $description);
            $this->add($form_group);
            
            if ($values['mandatory'] == true){
                $control->attr('data-mandatory', 'Yes');
                $form_group->find('label')->append(new html_sup('*'));
            }else{
                $control->attr('data-mandatory', 'No');
            }
            $form_group->add_class('forms form-group');
        }
        
    }
    
}

?>