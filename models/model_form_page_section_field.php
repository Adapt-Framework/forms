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
            
            $this->_auto_load_only_tables = array(
                'form_page_section_field_condition'
            );
            
            $this->_auto_load_children = true;
        }
        
        public function get_view($form_data = array()){
            if ($this->is_loaded){
                $type = new model_form_field_type($this->form_field_type_id);
                if ($type->is_loaded){
                    $model = $type->view;
                    $hash = $this->to_hash();
                    $hash = $hash[$this->table_name];
                    
                    $hash = array_merge($this->data_source->get_data_type($this->data_type_id), $hash);
                    
                    foreach($form_data as $table_name => $items){
                        
                        foreach($items as $field_name => $value){
                            $key = "{$table_name}[{$field_name}]";
                            if ($key == $hash['name']){
                                $hash['value'] = $value;
                            }
                        }
                    }
                    
                    if (isset($hash['allowed_values']) && is_json($hash['allowed_values'])){
                        $hash['allowed_values'] = json_decode($hash['allowed_values'], true);
                    }elseif(isset($hash['lookup_table'])){
                        /* We need to get a list of values from a table */
                        $structure = $this->data_source->get_row_structure($hash['lookup_table']);
                        if (isset($structure) && is_array($structure)){
                            $key = '';
                            $label = '';
                            $date_deleted = '';
                            $permission_id = '';
                            $values = array();
                            
                            foreach($structure as $field){
                                if ($field['primary_key'] == 'Yes'){
                                    $key = $field['field_name'];
                                }elseif($field['field_name'] == 'label'){
                                    $label = 'label';
                                }elseif($field['field_name'] == 'name' && $label == ''){
                                    $label = 'name';
                                }elseif($field['field_name'] == 'date_deleted'){
                                    $date_deleted = $field['field_name'];
                                }elseif($field['field_name'] == 'permission_id'){
                                    $permission_id = $field['field_name'];
                                }
                            }
                            
                            if (isset($key) && isset($label)){
                                $select_fields = array(
                                    'id' => $key,
                                    'name' => $label
                                );
                                if (isset($permission_id) && $permission_id != ''){
                                    $select_fields[$permission_id] = $permission_id;
                                }
                                
                                $sql = $this->data_source->sql;
                                
                                $sql->select($select_fields)
                                    ->from($hash['lookup_table']);
                                
                                if (isset($date_deleted) && $date_deleted != ''){
                                    $sql->where(new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql('date_deleted'), 'is', new \frameworks\adapt\sql('null')));
                                }
                                
                                $results = $sql->execute()->results();
                                $allowed_values = array();
                                
                                foreach($results as $result){
                                    if (!isset($permission_id) || $permission_id == '' || is_null($result[$permission_id]) || $this->session->user->has_permission($result[$permission_id])){
                                        $allowed_values[$result['id']] = $result['name'];
                                    }
                                }
                                
                                $hash['allowed_values'] = $allowed_values;
                            }
                        }
                        //print new html_pre(print_r($this->data_source->get_row_structure($hash['lookup_table']), true));
                    }
                    
                    $hash['mandatory'] = $hash['mandatory'] == 'Yes' ? true : false;
                    
                    $view = new $model($hash);
                    $view->add_class('form-page-section-field');
                    
                    $children = $this->get();
                    
                    foreach($children as $child){
                        if(is_object($child) && $child instanceof \model_form_page_section_field_condition){
                            
                            $field = new model_form_page_section_field($child->depends_on_form_page_section_field_id);
                            if ($field->is_loaded){
                                $operator = '=';
                                $value = $child->value;
                                
                                switch($child->operator){
                                case 'Equal to':
                                    $operator = '=';
                                    break;
                                case 'Less than':
                                    $operator = '<';
                                    break;
                                case 'Less than or equal to':
                                    $operator = '<=';
                                    break;
                                case 'Greater than':
                                    $operator = '>';
                                    break;
                                case 'Greater than or equal to':
                                    $operator = '>=';
                                    break;
                                case 'One of':
                                    $operator = 'in';
                                    $value = preg_replace("/\"/", "'", $value);
                                    break;
                                case 'Javascript function':
                                    $operator = 'function';
                                    break;
                                }
                                
                                $input = new html_input(array('type' => 'hidden', 'name' => 'depends_on', 'value' => $field->name, 'data-operator' => $operator, 'data-values' => $value));
                                $view->add($input);
                            }
                        }
                    }
                    
                    return $view;
                }
            }
            
            return null;
        }
        
    }
    
}

?>