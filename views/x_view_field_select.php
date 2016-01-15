<?php

namespace extensions\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_select extends view_field{
        
        public function __construct($form_data, $user_data){
            parent::__construct($form_data, $user_data);
            $this->add_class('form-group field input select');
            
            /* Create the control */
            $allowed_values = $form_data['allowed_values'];
            $value = $form_data['value'] ? $form_data['value'] : $form_data['default_value'];
            $control = new \extensions\bootstrap_views\view_select($form_data['name'], $allowed_values, $value);
            //$control = new html_input(array('type' => 'text', 'name' => $form_data['name'], 'value' => $form_data['value'] ? $form_data['value'] : $form_data['default_value'], 'class' => 'form-control'));
            $control->set_id();
            
            /* Add the label */
            if (isset($form_data['label']) && trim($form_data['label']) != ''){
                $this->add(new html_label($form_data['label'], array('for' => $control->attr('id'))));
            }
            
            /* Add the control */
            $this->add($control);
            
            /* Add the decription */
            if (isset($form_data['description']) && trim($form_data['description']) != ''){
                $this->add(new html_p($form_data['description'], array('class' => 'help-block')));
            }
            
            /* Do we have a placeholder label? */
            if (isset($form_data['placeholder_label']) && trim($form_data['placeholder_label']) != ''){
                $control->attr('placeholder', $form_data['placeholder_label']);
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
