<?php

namespace extensions\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_input extends view_field{
        
        public function __construct($form_data, $user_data){
            parent::__construct($form_data, $user_data);
            
            $this->add_class('form-group field input text');
            
            /* Create the control */
            $control = new html_input(array('type' => 'text', 'name' => $form_data['form_page_section_group_field']['name'], 'value' => $form_data['form_page_section_group_field']['value'] ? $form_data['form_page_section_group_field']['value'] : $form_data['form_page_section_group_field']['default_value'], 'class' => 'form-control'));
            $control->set_id();
            
            /* Add the label */
            if (isset($form_data['form_page_section_group_field']['label']) && trim($form_data['form_page_section_group_field']['label']) != ''){
                $this->add(new html_label($form_data['form_page_section_group_field']['label'], array('for' => $control->attr('id'), 'class' => 'control-label')));
            }
            
            /* Add the control */
            $this->add($control);
            
            /* Add the decription */
            if (isset($form_data['form_page_section_group_field']['description']) && trim($form_data['form_page_section_group_field']['description']) != ''){
                $this->add(new html_p($form_data['form_page_section_group_field']['description'], array('class' => 'help-block')));
            }
            
            /* Do we have a placeholder label? */
            if (isset($form_data['form_page_section_group_field']['placeholder_label']) && trim($form_data['form_page_section_group_field']['placeholder_label']) != ''){
                $control->attr('placeholder', $form_data['form_page_section_group_field']['placeholder_label']);
            }
            
            /* Load the data type for this field */
            $data_type = $this->data_source->get_data_type($form_data['form_page_section_group_field']['data_type_id']);
            
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
            if (isset($form_data['form_page_section_group_field']['max_length']) && trim($form_data['form_page_section_group_field']['max_length']) != ""){
                $control->attr('data-max-length', $form_data['form_page_section_group_field']['max_length']);
            }elseif (isset($data_type['max_length']) && trim($data_type['max_length']) != ''){
                $control->attr('data-max-length', $data_type['max_length']);
            }
            
            /* Is the field mandatory? */
            if (isset($form_data['form_page_section_group_field']['mandatory']) && strtolower($form_data['form_page_section_group_field']['mandatory']) == "yes"){
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
                if (isset($form_data['form_page_section_group_field']['mandatory_group']) && trim($form_data['form_page_section_group_field']['mandatory_group']) != ""){
                    $control->attr('data-mandatory', 'group');
                    $control->attr('data-mandatory-group', $form_data['form_page_section_group_field']['mandatory_group']);
                }else{
                    $control->attr('data-mandatory', 'Yes');
                }
            }
            
            /* Do we have a datetime format? */
            if (isset($data_type['datetime_format']) && trim($data_type['datetime_format']) != ''){
                $control->attr('data-datetime-format', $data_type['datetime_format']);
            }
            
        }
        
    }
    
}

?>