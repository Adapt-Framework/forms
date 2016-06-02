<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page_section extends view{
        
        protected $_layout_engine;
        
        public function __construct($form_data, &$user_data){
            $this->_layout_engine = new html_div();
            parent::__construct('div');
            $this->add_class('form-page-section');
            $this->attr('data-form-page-section-id', $form_data['form_page_section_id']);
            
            
            $this->attr('data-repeatable', $form_data['repeatable']);
            $this->attr('data-min-occurances', $form_data['min-occurances']);
            $this->attr('data-max-occurances', $form_data['max-occurances']);
            $this->attr('data-occurs-until', $form_data['occurs-until']);
            $this->attr('data-repeated-title', $form_data['repeated-title']);
            $this->attr('data-repeated-description', $form_data['repeated-description']);
            
            if (isset($form_data['title'])){
                parent::add(new html_h3($form_data['title'], array('class' => 'section-title')));
            }
            
            if (isset($form_data['description'])){
                parent::add(new html_p($form_data['description'], array('class' => 'section-description')));
            }
            
            parent::add(new html_div(array('class' => 'groups')));
            
            
            parent::add(new html_div(array('class' => 'controls')));
        }
        
        public function add_layout_engine($layout_engine){
            $this->find('.groups')->clear();
            $this->_layout_engine = $layout_engine;
            $this->find('.groups')->append($this->_layout_engine);
        }
        
        public function add($item){
            $this->_layout_engine->add($item);
        }
        
        public function add_control($control){
            $this->find('.controls')->append($control);
        }
        
        public function add_condition($condition){
            $children = $this->get();
            $conditions = null;
            
            foreach($children as $child){
                if ($child instanceof \adapt\html && $child->has_class('.conditions')){
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