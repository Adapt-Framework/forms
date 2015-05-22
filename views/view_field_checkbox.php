<?php

namespace extensions\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_checkbox extends view{
        
        public function __construct($values = array()){
            parent::__construct();
            $value = $values['allowed_values'][0];
            $control = new \extensions\bootstrap_views\view_input_radio(new \extensions\bootstrap_views\view_input("checkbox", $values['name'], $value), $values['label']);
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
            
            $this->add($control);
            
            $description = isset($values['description']) ? $values['description'] : null;
            
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