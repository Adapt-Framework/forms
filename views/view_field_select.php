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
            $allowed_values = $form_data['form_page_section_group_field']['allowed_values'];
            $value = $form_data['form_page_section_group_field']['value'] ? $form_data['form_page_section_group_field']['value'] : $form_data['form_page_section_group_field']['default_value'];
            $control = new \extensions\bootstrap_views\view_select($form_data['form_page_section_group_field']['name'], $allowed_values, $value);
            //$control = new html_input(array('type' => 'text', 'name' => $form_data['form_page_section_group_field']['name'], 'value' => $form_data['form_page_section_group_field']['value'] ? $form_data['form_page_section_group_field']['value'] : $form_data['form_page_section_group_field']['default_value'], 'class' => 'form-control'));
            $control->set_id();
            
            /* Add the label */
            if (isset($form_data['form_page_section_group_field']['label']) && trim($form_data['form_page_section_group_field']['label']) != ''){
                $this->add(new html_label($form_data['form_page_section_group_field']['label'], array('for' => $control->attr('id'))));
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
            
        }
        
    }
    
}

?>
