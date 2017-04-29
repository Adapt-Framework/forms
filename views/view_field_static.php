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
            $this->add_class('form-group field static');
            $key = $form_data['name'];
            $control = new \bootstrap\views\view_input_static($user_data[$key]);
            $label = isset($form_data['label']) ? $form_data['label'] : null;
            $description = isset($form_data['description']) ? $form_data['description'] : null;
            
            //parent::__construct($control, $label, $description);
            
            //$form_group = new \bootstrap\views\view_form_group($control, $label, $description);
            //$this->add($form_group);
            
            $this->add(new html_label($this->get_string($label)), $control);
            
            if ($form_data['mandatory'] == true){
                $control->attr('data-mandatory', 'Yes');
                $this->find('label')->append(new html_sup('*'));
            }else{
                $control->attr('data-mandatory', 'No');
            }
            
            
            if ($description){
                $this->add(new html_p($this->get_string($description), array('class' => 'help-block field-description')));
            }
        }
        
    }
    
}

?>