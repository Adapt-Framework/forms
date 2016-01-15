<?php

namespace extensions\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_static extends \extensions\bootstrap_views\view_form_group{
        
        public function __construct($values = array()){
            $control = new \extensions\bootstrap_views\view_input_static($values['value']);
            $label = isset($values['label']) ? $values['label'] : null;
            $description = isset($values['description']) ? $values['description'] : null;
            parent::__construct($control, $label, $description);
            if ($values['mandatory'] == true){
                $control->attr('data-mandatory', 'Yes');
                $this->find('label')->append(new html_sup('*'));
            }else{
                $control->attr('data-mandatory', 'No');
            }
            $this->add_class('forms form-group');
        }
        
    }
    
}

?>