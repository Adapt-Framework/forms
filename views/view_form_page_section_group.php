<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form_page_section_group extends view{
        
        protected $_layout_engine;
        
        public function __construct($form_data, &$user_data){
            $this->_layout_engine = new html_div();
            parent::__construct('div');
            $this->add_class('form-page-section-group');
            $this->attr('data-form-page-section-group-id', $form_data['form_page_section_group_id']);
            $this->attr('data-form-page-section-id', $form_data['form_page_section_id']);
            
            if (isset($form_data['label'])){
                parent::add(new html_label($form_data['label'], array('class' => 'group-label')));
            }
            
            parent::add(new html_div(array('class' => 'fields')));
            
            if (isset($form_data['description'])){
                parent::add(new html_p($form_data['description'], array('class' => 'group-description help-block')));
            }
            
            parent::add(new html_div(array('class' => 'controls')));
        }
        
        public function add_layout_engine($layout_engine){
            $this->find('.fields')->clear();
            $this->_layout_engine = $layout_engine;
            $this->find('.fields')->append($this->_layout_engine);
        }
        
        public function add($item){
            $this->_layout_engine->add($item);
            
            $has_visable_fields = false;
            
            $total_children = $this->_layout_engine->count();
            $visable_children = 0;
            for($i = 0; $i < $total_children; $i++){
                $child = $this->_layout_engine->get($i);
                if ($child instanceof \adapt\html){
                    if (!$child->has_class('hidden')){
                        $visable_children++;
                    }
                }
            }
            
            if ($visable_children == 0){
                $this->add_class('hidden');
            }else{
                $this->remove_class('hidden');
            }
        }
        
        public function add_native($item){
            parent::add($item);
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