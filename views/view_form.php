<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form extends view{
        
        protected $_form;
        protected $_steps;
        //protected $_page_count = 0;
        
        public function __construct($submission_url, $actions, $method = 'post', $title = null, $description = null, $style = \extensions\bootstrap_views\view_form::NORMAL, $show_steps = true, $show_processing_screen = true){
            $this->_form = new \extensions\bootstrap_views\view_form($submission_url, $method, $style);
            $this->_steps = new html_div(array('class' => 'steps'));
            parent::__construct('div');
            parent::add($this->_form);
            
            $this->add(new view_field_hidden(array('name' => 'actions', 'value' => $actions)));
            list($path, $params) = explode('?', $_SERVER['REQUEST_URI'], 2);
            $this->add(new view_field_hidden(array('name' => 'current_url', 'value' => $path)));
            
            if (!is_null($title)){
                $this->add(new \extensions\bootstrap_views\view_h1($title));
            }
            
            if (!is_null($description)){
                $this->add(new \extensions\bootstrap_views\view_p($description, true));
            }
            
            if ($show_steps){
                $this->add($this->_steps);
            }
        }
        
        public function add($items){
            //$new_id = 'page-' . ($this->_page_count + 1);
            
            if (is_object($items) && $items instanceof \frameworks\adapt\html){
                //if ($items->has_class('form-page')){
                //    $items->set_id($new_id);
                //    $this->_page_count++;
                //}
                if ($items->attr('data-step-label') && $items->attr('data-step-description')){
                    $step = new view_form_step($items->attr('data-step-label'), $items->attr('data-step-description'));
                    $step->attr('data-form-page-id', $items->attr($new_id));
                    if ($this->_steps->count() == 0){
                        $step->add_class('selected');
                    }else{
                        $items->add_class('hidden');
                    }
                    $this->_steps->add($step);
                }
            }
            $this->_form->add($items);
        }
        
    }
    
}

?>