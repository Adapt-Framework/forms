<?php

//namespace extensions\forms{
//    
//    /*
//     * Prevent direct access
//     */
//    defined('ADAPT_STARTED') or die;
//    
//    class view_field_checkbox extends view{
//        
//        public function __construct($values = array()){
//            parent::__construct();
//            $value = $values['allowed_values'][0];
//            $control = new \extensions\bootstrap_views\view_input_radio(new \extensions\bootstrap_views\view_input("checkbox", $values['name'], $value), $values['label']);
//            $control->find('.form-control')->remove_class('form-control');
//            
//            if (isset($values['value'])){
//                if ($values['value'] == $value){
//                    $control->find('input')->attr('checked', 'checked');
//                }
//            }elseif (isset($values['default_value'])){
//                if ($values['default_value'] == $value){
//                    $control->find('input')->attr('checked', 'checked');
//                }
//            }
//            
//            $this->add($control);
//            
//            $description = isset($values['description']) ? $values['description'] : null;
//            
//            if ($values['mandatory'] == true){
//                if (isset($values['mandatory_group'])){
//                    $this->attr('data-mandatory', 'Group');
//                    $this->attr('data-mandatory-group', $values['mandatory_group']);
//                }else{
//                    $this->attr('data-mandatory', 'Yes');
//                }
//                $this->find('label')->first()->append(new html_sup('*'));
//            }else{
//                $this->attr('data-mandatory', 'No');
//            }
//            
//            if (isset($description)){
//                $this->add(new html_span($description, array('class' => 'help-block')));
//            }
//
//        }
//        
//    }
//    
//}

?>
<?php

namespace extensions\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_checkbox extends view_field{
        
        public function __construct($form_data, $user_data){
            parent::__construct($form_data, $user_data);
            $this->add_class('field input checkbox');
            //$this->add(new html_pre(print_r($form_data, true)));
            /* Create the control */
            $control = new html_input(array('type' => 'checkbox', 'name' => $form_data['form_page_section_group_field']['name'], 'value' => $form_data['form_page_section_group_field']['allowed_values'][0]));
            $control->set_id();
            
            if ($form_data['form_page_section_group_field']['value'] == $form_data['form_page_section_group_field']['allowed_values'][0]
                || $form_data['form_page_section_group_field']['default_value'] == $form_data['form_page_section_group_field']['allowed_values'][0]
            ){
                $control->attr('checked', 'checked');
            }
            
            /* Add the label */
            if (isset($form_data['form_page_section_group_field']['label']) && trim($form_data['form_page_section_group_field']['label']) != ''){
                $label = new html_label($control);
                $label->add($form_data['form_page_section_group_field']['label']);
                $this->add($label);
                //$this->add(new html_label(array($control, $form_data['form_page_section_group_field']['label'])));
            }
            
            /* Add the control */
            //$label->add($control);
            
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
            if (isset($form_data['form_page_section_group_field']['datetime_format']) && trim($form_data['form_page_section_group_field']['datetime_format']) != ''){
                $control->attr('data-datetime-format', $form_data['form_page_section_group_field']['datetime_format']);
            }
            
        }
        
    }
    
}

?>