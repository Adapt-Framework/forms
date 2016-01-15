<?php
namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_group_layout extends view{
        
        protected $_field_container;
        
        public function __construct($group_data = array()){
            parent::__construct();
            
            /* Add the group id */
            $this->attr('data-from-page-section-group-id', $group_data['form_page_section_group_id']);
            
            /* Add the label */
            if (isset($group_data['label']) && trim($group_data['label']) != ""){
                parent::add(new html_label($group_data['label'], array('class' => 'group-label')));
            }
            
            /* Add the description */
            if (isset($group_data['description']) && trim($group_data['description']) != ""){
                parent::add(new html_p($group_data['description'], array('class' => 'group-description')));
            }
            
            /* Add the field container */
            $this->_field_container = new html_div(array('class' => 'field-container form-group-layout', 'data-form-page-section-group-layout-id' => $group_data['form_page_section_group_layout_id']));
            parent::add($$this->_field_container);
        }
        
        public function pget_field_container(){
            return $this->_field_container;
        }
        
        public function add_control($control){
            if ($this->find('.controls')->size()){
                $this->find('controls')->append($control);
            }else{
                $this->add(new html_div($control, array('class' => 'controls')));
            }
        }
        
        public function add_condition($condition){
            $a = new \frameworks\adapt\aquery($this);
            $a->prepend($condition);
        }
        
        public function add($item){
            $this->field_container->add($item);
        }
        
        
    }
}

?>