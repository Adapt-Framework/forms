<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model_form_page_section_group extends \frameworks\adapt\model{
        
        public function __construct($id = null){
            parent::__construct('form_page_section_group', $id);
        }
        
        public function initialise(){
            parent::initialise();
            
            $this->_auto_load_only_tables = array(
                'form_page_section_group_field',
                'form_page_section_group_condition',
                'form_page_section_group_button'
            );
            
            $this->_auto_load_children = true;
        }
        
        public function get_view($user_data = array()){
            if ($this->is_loaded){
                $view = null;
                
                if (isset($this->custom_view) && trim($this->custom_view) != ''){
                    $class = $this->custom_view;
                    $view = new $class($this->to_hash(), $user_data);
                }else{
                    /* Load the layout */
                    $layout = new model_form_page_section_group_layout($this->form_page_section_group_layout_id);
                    if ($layout->is_loaded){
                        $class = $layout->custom_view;
                        $view = new $class($this->to_hash(), $user_data);
                    }
                }
                
                if ($view){
                    
                    /* Do we have any children? */
                    $children = $this->get();
                    
                    foreach($children as $child){
                        if ($child instanceof \frameworks\adapt\model){
                            switch($child->table_name){
                            case "form_page_section_group_field":
                                $child_view = $child->get_view($user_data);
                                if ($child_view && $child_view instanceof \frameworks\adapt\html){
                                    $view->add($child_view);
                                    if ($child_view->has_class('hidden')) $view->add_class('hidden');
                                }
                                break;
                            case "form_page_section_group_button":
                                $button = new html_button();
                                if ($child->label != '' && $child->icon_name != '' && $child->icon_class != ''){
                                    $class = $child->icon_class;
                                    if (class_exists($class)){
                                        $icon = new $class($child->icon_name);
                                        $button->add(array($icon, ' ', $child->label));
                                    }else{
                                        $button->add(array($child->label));
                                    }
                                }elseif ($child->icon_name != '' && $child->icon_class != ''){
                                    $class = $child->icon_class;
                                    if (class_exists($class)){
                                        $icon = new $class($child->icon_name);
                                        $button->add(array($icon));
                                    }
                                }else{
                                    $button->add(array($child->label));
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
                                
                                /* Add the button */
                                $view->add_control($button);
                                
                                break;
                            case "form_page_section_group_condition":
                                $field = new model_form_page_section_group_field($child->depends_on_form_page_section_group_field_id);
                                if ($field->is_loaded){
                                    $view->add_condition($field->name, $child->operator, $child->value);
                                }
                                break;
                            }
                        }
                    }
                    
                    return $view;
                    
                }
                
            }
            
            return null;
        
            /* * * * * * * */
            
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