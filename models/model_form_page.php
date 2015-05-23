<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model_form_page extends \frameworks\adapt\model{
        
        public function __construct($id = null){
            parent::__construct('form_page', $id);
        }
        
        public function initialise(){
            parent::initialise();
            
            $this->_auto_load_only_tables = array(
                'form_page_section',
                'form_page_button',
                'form_page_condition'
            );
            
            $this->_auto_load_children = true;
        }
        
        public function get_view($form_data = array()){
            $view = new view_form_page($this->title, $this->description, $this->step_title, $this->step_description);
            $view->attr('data-form_page_id', $this->form_page_id);
            $controls = new html_div(array('class' => 'controls'));
            for($i = 0; $i < $this->count(); $i++){
                $child = $this->get($i);
                
                if (is_object($child) && $child instanceof model_form_page_section){
                    $view->add($child->get_view($form_data));
                }elseif(is_object($child) && $child instanceof \model_form_page_button){
                    $button = new html_button();
                    if ($child->label != ""){
                        $button->add($child->label);
                    }
                    if ($child->icon_view && $child->icon_class){
                        if (class_exists($child->icon_view)){
                            $class = $child->icon_view;
                            $icon = new $class($child->icon_class);
                            $button->add($icon);
                        }
                    }
                    
                    $style = new model_form_button_style($child->form_button_style_id);
                    if ($style->is_loaded){
                        $button->attr('class', $style->classes);
                    }
                    
                    switch($child->action){
                        case "Submit":
                            $button->add_class('control submit');
                            break;
                        case "Reset":
                            $button->add_class('control reset');
                            break;
                        case "Next page":
                            $button->add_class('control next');
                            break;
                        case "Previous page":
                            $button->add_class('control previous');
                            break;
                        case "Custom...":
                            $button->add_class('control custom');
                            $button->attr('onclick', $child->custom_action);
                            break;
                    }
                    
                    $controls->add($button);
                }else if(is_object($child) && $child instanceof \model_form_page_condition){
                    /* This is inefficient as f**k! Why would I write this? */
                    
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
            
            $view->add($controls);
            
            return $view;
        }
        
    }
    
}

?>