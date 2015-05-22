<?php

namespace extensions\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_select extends \extensions\bootstrap_views\view_form_group{
        
        public function __construct($values = array()){
            $control = new \extensions\bootstrap_views\view_select($values['name'], $values['allowed_values'], $values['value']);
            $label = isset($values['label']) ? $values['label'] : null;
            $description = isset($values['description']) ? $values['description'] : null;
            if (isset($values['validator']) && $values['validator'] != ''){
                $control->attr('data-validator', $values['validator']);
            }
            if (isset($values['formatter']) && $values['formatter'] != ''){
                $control->attr('data-formatter', $values['formatter']);
            }
            if (isset($values['unformatter']) && $values['unformatter'] != ''){
                $control->attr('data-unformatter', $values['unformatter']);
            }
            if (isset($values['datetime_format']) && $values['datetime_format'] != ''){
                $control->attr('data-datetime-format', $values['datetime_format']);
            }
            parent::__construct($control, $label, $description);
            if ($values['mandatory'] == true){
                if (isset($values['mandatory_group'])){
                    $control->attr('data-mandatory', 'Group');
                    $control->attr('data-mandatory-group', $values['mandatory_group']);
                }else{
                    $control->attr('data-mandatory', 'Yes');
                }
                $this->find('label')->append(new html_sup('*'));
            }else{
                $control->attr('data-mandatory', 'No');
            }
            $this->add_class('forms form-group');
        }
        
    }
    
}

?>