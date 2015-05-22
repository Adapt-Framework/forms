<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model_form_page_section_field extends \frameworks\adapt\model{
        
        public function __construct($id = null){
            parent::__construct('form_page_section_field', $id);
        }
        
        public function initialise(){
            parent::initialise();
            
            //$this->_auto_load_only_tables = array(
            //    'form_page_section_field'
            //);
            
            //$this->_auto_load_children = true;
        }
        
        public function get_view($form_data = array()){
            if ($this->is_loaded){
                $type = new model_form_field_type($this->form_field_type_id);
                if ($type->is_loaded){
                    $model = $type->view;
                    $hash = $this->to_hash();
                    $hash = $hash[$this->table_name][0];
                    $hash = array_merge($this->data_source->get_data_type($this->data_type_id), $hash);
                    
                    if (isset($form_data[$this->name])){
                        $hash['value'] = $form_data[$this->name];
                    }else{
                        $hash['value'] = null;
                    }
                    
                    if (isset($hash['allowed_values']) && is_json($hash['allowed_values'])){
                        $hash['allowed_values'] = json_decode($hash['allowed_values'], true);
                    }
                    
                    $hash['mandatory'] = $hash['mandatory'] == 'Yes' ? true : false;
                    
                    $view = new $model($hash);
                    return $view;
                }
            }
            
            return null;
        }
        
    }
    
}

?>