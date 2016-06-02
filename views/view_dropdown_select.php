<?php

namespace adapt\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_dropdown_select extends \bootstrap\views\view_dropdown{
        
        public function __construct($name = null, $options = array(), $selected_value = null){
            parent::__construct(new html_span(array('class' => 'selected-label')));
            $this->add_class('dropdown forms');
            $a = new \adapt\aquery($this);
            $a->append(new html_input(array('class' => 'selected-value', 'type' => 'hidden', 'name' => $name, 'value' => $selected_value)));
            $this->add($options);
        }
        
        public function add(){
            $params = func_get_args();
            
            if (count($params) > 1){
                foreach($params as $param) $this->add($param);
            }elseif(count($params) == 1){
                if (is_array($params[0])){
                    if (is_assoc($params[0])){
                        foreach($params[0] as $key => $value){
                            parent::add(new view_dropdown_select_item($value, $key));
                        }
                    }else{
                        foreach($params[0] as $value){
                            parent::add(new view_dropdown_select_item($value));
                        }
                    }
                }elseif($params[0] instanceof html){
                    parent::add($params[0]);
                }elseif(is_string($params[0])){
                    parent::add(new view_dropdown_menu_item($params[0]));
                }
            }
            
            if ($this->find('.selected-value')->attr('value')){
                $label = $this->find("[data-value='" . $this->find('.selected-value')->attr('value') . "'] ")->text();
                $this->find('.selected-label')->append($label . " ");
            }
        }
        
    }
    
}
?>