<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form extends view{
        
        //protected $_form;
        //protected $_steps;
        //protected $_page_count = 0;
        
        public function __construct($form_data, &$user_data){
            parent::__construct('form');
            
            ///* Add the attributes */
            $this->attr('action', $form_data['submission_url']);
            $this->attr('method', $form_data['method']);
            $this->attr('data-form-id', $form_data['form_id']);
            $this->set_id();
            
            //$this->add(new html_pre(print_r($form_data, true)));
            
            /* Add the actions */
            parent::add(new html_input(array('type' => 'hidden', 'name' => 'actions', 'value' => $form_data['actions'], 'class' => 'form-actions')));
            
            ///* Add the current url */
            list($path, $params) = explode('?', $_SERVER['REQUEST_URI'], 2);
            parent::add(new html_input(array('type' => 'hidden', 'name' => 'current_url', 'value' => $path)));
            //
            /* Add the title if required */
            if (isset($form_data['title']) && trim($form_data['title']) != ""){
                parent::add(new html_h1($this->get_string($form_data['title']), array('class' => 'form-title')));
            }
            
            /* Add a description if required */
            if (isset($form_data['description']) && trim($form_data['description']) != ""){
                parent::add(new html_p($this->get_string($form_data['description']), array('class' => 'lead form-description')));
            }
            //
            /* Add the steps if required */
            if (isset($form_data['show_steps']) && strtolower($form_data['show_steps']) == "yes"){
                parent::add(new html_div(array('class' => 'steps hidden-xs hidden-sm')));
            }
            
            /* Add the processing screen if required */
            if (isset($form_data['show_processing_page']) && strtolower($form_data['show_processing_page']) == "yes"){
               parent::add(new html_div(new html_span(array('class' => 'fa fa-circle-o-notch fa-spin fa-5x')), array('class' => 'processing text-center hidden')));
            }
            
            /* Add the page container */
            parent::add(new html_div(array('class' => 'pages')));
        }
        
        
        public function add($items){
            $new_id = 'page-' . ($this->_page_count + 1);
            
            if (is_object($items) && $items instanceof \adapt\html){
                //if ($items->has_class('form-page')){
                //    $items->set_id($new_id);
                //    $this->_page_count++;
                //}
                if ($items->attr('data-step-label') && $items->attr('data-step-description')){
                    $step = new view_form_step($items->attr('data-step-label'), $items->attr('data-step-description'));
                    $step->attr('data-form-page-id', $items->attr('id'));
                    
                    if ($this->find('.steps')->children()->size() == 0){
                        $step->add_class('selected');
                    }else{
                        $items->add_class('hidden');
                    }

                    $this->find('.steps')->append($step);

                }
            }
            $this->find('.pages')->append($items);
        }
        
    }
    
}

?>