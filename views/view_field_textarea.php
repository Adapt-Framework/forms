<?php

namespace adapt\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_textarea extends view_form_page_section_group_field{
        
        public function __construct($form_data, $data_type, &$user_data){
            parent::__construct($form_data, $data_type, $user_data);
            $this->add_class('form-group field input textarea');
            
            /* Create the control */
            $control = new \bootstrap\views\view_textarea($form_data['name'], $this->user_value ? $this->user_value : $form_data['default_value'], 3);
            $control->set_id();
            
            /* Add the label */
            if (isset($form_data['label']) && trim($form_data['label']) != ''){
                $this->add(new html_label($form_data['label'], array('for' => $control->attr('id'), 'class' => 'field-label')));
            }
            
            /* Add the control */
            $this->add($control);
            
            /* Add the decription */
            if (isset($form_data['description']) && trim($form_data['description']) != ''){
                $this->add(new html_p($form_data['description'], array('class' => 'help-block field-description')));
            }
            
            /* Do we have a placeholder label? */
            if (isset($form_data['placeholder_label']) && trim($form_data['placeholder_label']) != ''){
                $control->attr('placeholder', $form_data['placeholder_label']);
            }
            
            /* Load the data type for this field */
            //$data_type = $this->data_source->get_data_type($form_data['data_type_id']);
            
            //print new html_pre(print_r($data_type, true));
            
            /* Do we have a validator? */
            if (isset($data_type['validator']) && trim($data_type['validator']) != ''){
                $control->attr('data-validator', $data_type['validator']);
            }
            
            /* Do we have a formatter? */
            if (isset($data_type['formatter']) && trim($data_type['formatter']) != ''){
                $control->attr('data-formatter', $data_type['formatter']);
            }
            
            /* Do we have a unformatter? */
            if (isset($data_type['unformatter']) && trim($data_type['unformatter']) != ''){
                $control->attr('data-unformatter', $data_type['unformatter']);
            }
            
            /* Does the field or data type have a max length? */
            if (isset($form_data['max_length']) && trim($form_data['max_length']) != ""){
                $control->attr('data-max-length', $form_data['max_length']);
            }elseif (isset($data_type['max_length']) && trim($data_type['max_length']) != ''){
                $control->attr('data-max-length', $data_type['max_length']);
            }
            
            /* Is the field mandatory? */
            if (isset($form_data['mandatory']) && strtolower($form_data['mandatory']) == "yes"){
                /* Mark the label */
                $this->find('label')->append(
                    new html_sup(
                        array(
                            '*',
                            new html_span(' (This field is required)', array('class' => 'sr-only'))
                        )
                    )
                );
                
                /* Is it a mandatory group? */
                if (isset($form_data['mandatory_group']) && trim($form_data['mandatory_group']) != ""){
                    $control->attr('data-mandatory', 'group');
                    $control->attr('data-mandatory-group', $form_data['mandatory_group']);
                }else{
                    $control->attr('data-mandatory', 'Yes');
                }
            }
            
        }
        
    }
    
}

?>
