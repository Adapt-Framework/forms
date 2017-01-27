<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page extends view{
        
        public function __construct($form_data, &$user_data, $errors = array()){
            parent::__construct();
            
            /* Add the error panel */
            $error_panel = new html_div(array('class' => 'error-panel'));
            parent::add($error_panel);
            
            foreach($errors as $error) $error_panel->add(new html_p(new html_strong($error)));
            
            $this->attr('data-form-page-id', $form_data['form_page_id']);
            
            $this->add_class('form-page');
            
            if (isset($form_data['step_title']) && trim($form_data['step_title']) != ""){
                $this->attr('data-step-label', $form_data['step_title']);
            }
            
            if (isset($form_data['step_description']) && trim($form_data['step_description']) != ""){
                $this->attr('data-step-description', $form_data['step_description']);
            }
            
            if (isset($form_data['title']) && trim($form_data['title']) != ""){
                parent::add(new html_h2($this->get_string($form_data['title']), array('class' => 'page-title')));
            }
            
            if (isset($form_data['description']) && trim($form_data['description']) != ""){
                parent::add($this->get_string(new html_p($form_data['description']), array('class' => 'page-description')));
            }
            
            /* Add the id */
            $this->set_id();
            
            
            /* Add a container for sections */
            parent::add(new html_div(array('class' => 'sections')));
            
            
            /* Add a place for controls */
            parent::add(new html_div(array('class' => 'controls text-right')));
        }
        
        public function add($item){
            $this->find('.sections')->append($item);
        }
        
        public function add_control($control){
            //print $control;
            $this->find('.controls')->last()->append($control);
        }
        
        public function add_condition($condition){
            $children = $this->get();
            $conditions = null;
            
            foreach($children as $child){
                if ($child instanceof \adapt\html && $child->has_class('page-condition')){
                    $conditions = $child;
                    break;
                }
            }
            
            if (is_null($conditions)){
                $conditions = new html_div(array('class' => 'conditions hidden'));
                $a = new \adapt\aquery($this);
                $a->prepend($conditions);
            }
            
            $conditions->add($condition);
        }
        
    }
    
}

?>