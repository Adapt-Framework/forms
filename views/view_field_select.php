<?php

namespace adapt\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_select extends view_form_page_section_group_field{
        
        public function __construct($form_data, $data_type, &$user_data){
            parent::__construct($form_data, $data_type, $user_data);
            $this->add_class('form-group field input select');
            
            /* Create the control */
            $allowed_values = $form_data['allowed_values'];
            if (is_assoc($allowed_values)){
                $new_values = ['__NOT_SET__' => '--- ' . $this->get_string('Choose one') . ' ---'];
                foreach($allowed_values as $key => $val){
                    if (is_assoc($val)){
                        $new_values[$key] = [];
                        foreach($val as $label => $item_val){
                            $new_values[$key][$label] = $item_val;
                        }
                    }else{
                        $new_values[$key] = $val;
                    }
                }
                $allowed_values = $new_values;
            }
            $key = $form_data['field_name'];
            $value = $user_data[$key];
            
            if (!$value){
                $value = $form_data['default_value'];
            }
            
            $control = new \bootstrap\views\view_select($form_data['field_name'], $allowed_values, $value);
            $control->attr('data-default-value', $value);
            
            //$control = new html_input(array('type' => 'text', 'name' => $form_data['name'], 'value' => $form_data['value'] ? $form_data['value'] : $form_data['default_value'], 'class' => 'form-control'));
            $control->set_id();
            
            /* Add the label */
            if (isset($form_data['label']) && trim($form_data['label']) != ''){
                $this->add(new html_label($this->get_string($form_data['label']), array('class' => 'control-label', 'for' => $control->attr('id'))));
            }
            
            /* Add the control */
            $this->add($control);
            
            /* Add the decription */
            if (isset($form_data['description']) && trim($form_data['description']) != ''){
                $this->add(new html_p($this->get_string($form_data['description']), array('class' => 'help-block')));
            }
            
            /* Do we have a placeholder label? */
            if (isset($form_data['placeholder_label']) && trim($form_data['placeholder_label']) != ''){
                $control->attr('placeholder', $this->get_string($form_data['placeholder_label']));
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
