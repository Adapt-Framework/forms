<?php

namespace adapt\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_checkbox extends view_form_page_section_group_field{
        
        public function __construct($form_data, $data_type, &$user_data){
            parent::__construct($form_data, $data_type, $user_data);
            $this->add_class('field input checkbox');
            
            /* Create the control */
            $control = new html_input(array('type' => 'checkbox', 'name' => $form_data['name'], 'value' => $form_data['allowed_values'][0]));
            $control->set_id();

            if ($user_data[$form_data['name']] == $form_data['allowed_values'][0]
                || $form_data['default_value'] == $form_data['allowed_values'][0]
            ){
                $control->attr('checked', 'checked');
            }
            
            /* Add the label */
            if (isset($form_data['label']) && trim($form_data['label']) != ''){
                $label = new html_label($control);
                $label->add($this->get_string($form_data['label']));
                $this->add($label);
            }
            
            /* Add the control */
            //$label->add($control);
            
            /* Add the decription */
            if (isset($form_data['description']) && trim($form_data['description']) != ''){
                $this->add(new html_p($this->get_string($form_data['description']), array('class' => 'help-block')));
            }
            
            /* Do we have a placeholder label? */
            //if (isset($form_data['placeholder_label']) && trim($form_data['placeholder_label']) != ''){
            //    $control->attr('placeholder', $form_data['placeholder_label']);
            //}
            
            /* Load the data type for this field */
            //$data_type = $this->data_source->get_data_type($form_data['data_type_id']);
            
            //print new html_pre(print_r($data_type, true));
            
            /* Do we have a validator? */
            //if (isset($data_type['validator']) && trim($data_type['validator']) != ''){
            //    $control->attr('data-validator', $data_type['validator']);
            //}
            
            /* Do we have a formatter? */
            //if (isset($data_type['formatter']) && trim($data_type['formatter']) != ''){
            //    $control->attr('data-formatter', $data_type['formatter']);
            //}
            
            /* Do we have a unformatter? */
            //if (isset($data_type['unformatter']) && trim($data_type['unformatter']) != ''){
            //    $control->attr('data-unformatter', $data_type['unformatter']);
            //}
            
            /* Does the field or data type have a max length? */
            //if (isset($form_data['max_length']) && trim($form_data['max_length']) != ""){
            //    $control->attr('data-max-length', $form_data['max_length']);
            //}elseif (isset($data_type['max_length']) && trim($data_type['max_length']) != ''){
            //    $control->attr('data-max-length', $data_type['max_length']);
            //}
            
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
                    $this->attr('data-mandatory', 'group');
                    $this->attr('data-mandatory-group', $form_data['mandatory_group']);
                }else{
                    $this->attr('data-mandatory', 'Yes');
                }
            }
            
            /* Do we have a datetime format? */
            //if (isset($form_data['datetime_format']) && trim($form_data['datetime_format']) != ''){
            //    $control->attr('data-datetime-format', $form_data['datetime_format']);
            //}
            
        }
        
    }
    
}

?>