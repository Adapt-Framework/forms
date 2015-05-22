<?php

namespace extensions\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_textarea extends \extensions\bootstrap_views\view_form_group{
        
        public function __construct($values = array()){
            $control = new \extensions\bootstrap_views\view_textarea($values['name'], $values['value'], 3);
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
            if (isset($values['max_length']) && $values['max_length'] != ''){
                $control->attr('data-max-length', $values['max_length']);
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