<?php

namespace adapt\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_radio extends view_form_page_section_group_field{
        
        //public function __construct($values = array()){
        public function __construct($form_data, $data_type, &$user_data){
            //parent::__construct();
            parent::__construct($form_data, $data_type, $user_data);
            
            $controls = array();
            
            foreach($form_data['allowed_values'] as $value){
                $control = new \bootstrap\views\view_input_radio(new \bootstrap\views\view_input("radio", $values['name'], $value), $value, true);
                $control->find('.form-control')->remove_class('form-control');
                if (isset($this->user_value)){
                    if ($this->user_value == $value){
                        $control->find('input')->attr('checked', 'checked');
                    }
                }elseif (isset($form_data['default_value'])){
                    if ($form_data['default_value'] == $value){
                        $control->find('input')->attr('checked', 'checked');
                    }
                }
                $controls[] = $control;
            }
            $this->add(new html_label($form_data['label']));
            $this->add($controls);
            //return;
            
            
            //$control = new \extensions\bootstrap_views\view_input("radio", $values['name'], $values['value'], $values['placeholder_label']);
            //$label = isset($values['label']) ? $values['label'] : null;
            $description = isset($form_data['description']) ? $form_data['description'] : null;
            //if (isset($values['validator']) && $values['validator'] != ''){
            //    $control->attr('data-validator', $values['validator']);
            //}
            //if (isset($values['formatter']) && $values['formatter'] != ''){
            //    $control->attr('data-formatter', $values['formatter']);
            //}
            //if (isset($values['unformatter']) && $values['unformatter'] != ''){
            //    $control->attr('data-unformatter', $values['unformatter']);
            //}
            //if (isset($values['datetime_format']) && $values['datetime_format'] != ''){
            //    $control->attr('data-datetime-format', $values['datetime_format']);
            //}
            //if (isset($values['max_length']) && $values['max_length'] != ''){
            //    $control->attr('data-max-length', $values['max_length']);
            //}
            //parent::__construct($control, $label, $description);
            
            if ($form_data['mandatory'] == true){
                if (isset($form_data['mandatory_group'])){
                    $this->attr('data-mandatory', 'Group');
                    $this->attr('data-mandatory-group', $form_data['mandatory_group']);
                }else{
                    $this->attr('data-mandatory', 'Yes');
                }
                $this->find('label')->first()->append(new html_sup('*'));
            }else{
                $this->attr('data-mandatory', 'No');
            }
            
            if (isset($description)){
                $this->add(new html_span($description, array('class' => 'help-block')));
            }

        }
        
    }
    
}

?>