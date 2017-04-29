<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page_button extends view{
        
        public function __construct($form_data, $button_style){
            parent::__construct('button');
            $this->add_class('page-control control');
            
            /* Add the buttons id */
            $this->attr('data-form-page-button-id', $form_data['form_page_button_id']);
            
            /* Set the style id */
            $this->attr('data-form-button-style-id', $form_data['form_button_style_id']);
            
            /* Add the classes */
            if (isset($button_style['classes'])){
                $this->add_class($button_style['classes']);
            }
            
            /* Add the icon if available */
            if (isset($form_data['icon_class']) && isset($form_data['icon_name'])){
                $class = $form_data['icon_class'];
                if (class_exists($class)){
                    $icon = new $class($form_data['icon_name']);
                    $icon->attr('data-icon-class', $class);
                    $icon->attr('data-icon-name', $form_data['icon_name']);
                    $this->add($icon);
                }
            }
            
            /* Add the label */
            if (isset($form_data['label'])){
                $this->add(new html_span($this->get_string($form_data['label']), array('class' => 'button-label')));
            }
            
            /* Add the action */
            $this->attr('data-action', $form_data['action']);
            
            switch($form_data['action']){
            case "Submit":
                $this->add_class('submit');
                break;
            case "Reset":
                $this->add_class('reset');
                break;
            case "Next page":
                $this->add_class('next');
                break;
            case "Previous page":
                $this->add_class('previous');
                break;
            case "Custom...":
                $this->attr('onclick', $form_data['custom_action']);
                break;
            }
            
        }
        
    }
    
}

?>