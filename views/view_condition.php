<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_condition extends view{
        
        public function __construct($form_data){
            parent::__construct('div');
            $this->add_class('condition hidden');
            
            $this->attr('data-target-form-page-section-group-field-id', $form_data['depends_on_form_page_section_group_field_id']);
            
            $value = $form_data['value'];
            
            switch($form_data['operator']){
            case 'Equal to':
                $operator = '=';
                break;
            case 'Less than':
                $operator = '<';
                break;
            case 'Less than or equal to':
                $operator = '<=';
                break;
            case 'Greater than':
                $operator = '>';
                break;
            case 'Greater than or equal to':
                $operator = '>=';
                break;
            case 'One of':
                $operator = ' in ';
                $value = preg_replace("/\"/", "'", $value);
                break;
            case 'Javascript function':
                $operator = ' function ';
                break;
            }
            
            $this->attr('data-operator', $operator);
            $this->attr('data-value', $value);
        }
        
    }
}

?>