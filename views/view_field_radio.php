<?php

namespace extensions\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_radio extends view{
        
        public function __construct($values = array()){
            parent::__construct();
            $controls = array();
            
            foreach($values['allowed_values'] as $value){
                $control = new \extensions\bootstrap_views\view_input_radio(new \extensions\bootstrap_views\view_input("radio", $values['name'], $value), $value, true);
                $control->find('.form-control')->remove_class('form-control');
                if (isset($values['value'])){
                    if ($values['value'] == $value){
                        $control->find('input')->attr('checked', 'checked');
                    }
                }elseif (isset($values['default_value'])){
                    if ($values['default_value'] == $value){
                        $control->find('input')->attr('checked', 'checked');
                    }
                }
                $controls[] = $control;
            }
            $this->add(new html_label($values['label']));
            $this->add($controls);
            //return;
            
            
            //$control = new \extensions\bootstrap_views\view_input("radio", $values['name'], $values['value'], $values['placeholder_label']);
            //$label = isset($values['label']) ? $values['label'] : null;
            $description = isset($values['description']) ? $values['description'] : null;
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
            
            if ($values['mandatory'] == true){
                if (isset($values['mandatory_group'])){
                    $this->attr('data-mandatory', 'Group');
                    $this->attr('data-mandatory-group', $values['mandatory_group']);
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