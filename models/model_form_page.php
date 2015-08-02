<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model_form_page extends model{
        
        public function __construct($id = null){
            parent::__construct('form_page', $id);
        }
        
        public function initialise(){
            parent::initialise();
            
            $this->_auto_load_only_tables = array(
                /*'form_page_section',*/
                /*'form_page_button',*/
                /*'form_page_condition'*/
            );
            
            //$this->_auto_load_children = true;
        }
        
        public function get_view($user_data = array(), $errors = array()){
            if ($this->is_loaded){
                $view = null;
                
                if (isset($this->custom_view) && trim($this->custom_view) != ''){
                    $class = $this->custom_view;
                    $view = new $class($this->to_hash(), $user_data);
                }else{
                    $view = new view_form_page($this->to_hash(), $user_data, $errors);
                }
                
                /* Do we have any childre? */
                $children = $this->get();
                
                foreach($children as $child){
                    if ($child instanceof \frameworks\adapt\model){
                        switch($child->table_name){
                        case "form_page_section":
                            $view->add($child->get_view($user_data));
                            break;
                        case "form_page_button":
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
                        case "form_page_condition":
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
            
            return null;
        }
        
    }
    
}

?>