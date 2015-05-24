<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model_form_page_section extends \frameworks\adapt\model{
        
        public function __construct($id = null){
            parent::__construct('form_page_section', $id);
        }
        
        public function initialise(){
            parent::initialise();
            
            $this->_auto_load_only_tables = array(
                'form_page_section_field',
                'form_page_section_condition'
            );
            
            $this->_auto_load_children = true;
        }
        
        public function get_view($form_data = array()){
            $view = new view_form_page_section($this->title, $this->description, $this->repeatable == 'Yes' ? true : false, $this->min_occurances, $this->max_occurances, $this->repeated_title, $this->repeated_description);
            
            $children = $this->get();
            foreach($children as $child){
                
                if (is_object($child) && $child instanceof model_form_page_section_field){
                    $view->add($child->get_view($form_data));
                }elseif(is_object($child) && $child instanceof \model_form_page_section_condition){
                    
                    $field = new model_form_page_section_field($child->depends_on_form_page_section_field_id);
                    if ($field->is_loaded){
                        $operator = '=';
                        
                        switch($child->operator){
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
                            break;
                        case 'Javascript function':
                            $operator = ' function ';
                            break;
                        }
                        
                        $input = new html_input(array('type' => 'hidden', 'name' => 'depends_on', 'value' => $field->name . $operator . $child->value));
                        $view->add($input);
                    }
                }
            }
            
            return $view;
        }
        
    }
    
}

?>