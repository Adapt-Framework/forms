<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view extends \frameworks\adapt\view{
        
        protected $_conditions;
        
        public function __construct($tag = 'div', $data = null, $attributes = array()){
            parent::__construct($tag, $data, $attributes);
            $this->add_class('forms');
        }
        
        public function add_condition($field_name, $operator, $value){
            if (is_null($this->_condition)){
                $this->_conditions = new html_div(array('class' => 'conditions hidden'));
                parent::add($this->_conditions);
            }
            
            switch($operator){
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
            
            $input = new html_input(array('class' => 'condition', 'type' => 'hidden', 'name' => 'depends_on', 'data-field-name' => $field_name, 'data-operator' => $operator, 'data-value' => $value));
            $this->_conditions->add($input);
        }
        
    }

}

?>