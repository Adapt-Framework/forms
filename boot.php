<?php

namespace extensions\forms;
use \frameworks\adapt as adapt;

/* Prevent direct access */
defined('ADAPT_STARTED') or die;

$adapt = $GLOBALS['adapt'];

/*
 * Include  css & javascript
 */
$adapt->dom->head->add(new adapt\html_link(array('type' => 'text/css', 'rel' => 'stylesheet', 'href' => '/adapt/extensions/forms/static/css/forms.css')));
$adapt->dom->head->add(new adapt\html_script(array('type' => 'text/javascript', 'src' => '/adapt/extensions/forms/static/js/forms.js')));
$adapt->dom->head->add(new adapt\html_script(array('type' => 'text/javascript', 'src' => '/adapt/extensions/forms/static/js/reflow.js')));
//$adapt->dom->head->add(new adapt\html_script(array('type' => 'text/javascript', 'src' => '/_forms/validators')));

/*
 * We are going to extend adapt\model to include
 * a to_form function
 */

adapt\model::extend('to_form', function($_this){
    $html = new view_form();
    $html->add(new html_h1($_this->table_name));
    
    $schema = $_this->schema;
    
    foreach($schema as $field){
        //$html->add(new html_pre(print_r($field, true)));
        
        $data_type = $_this->data_source->get_data_type($field['data_type_id']);
        //$html->add(new html_pre(print_r($data_type, true)));
        
        $field_name = $field['field_name'];
        $values = array(
            'name' => $field['table_name'] . "[" . $field_name . "]",
            'label' => $field['label'],
            'description' => $field['description'],
            'value' => $_this->$field_name,
            'placeholder' => $field['placeholder'],
            'lookup_table' => $field['lookup_table'],
            'allowed_values' => preg_replace("/\'/", "\"", $field['allowed_values']),
            'validator' => $data_type['validator'],
            'formatter' => $data_type['formatter'],
            'unformatter' => $data_type['unformatter'],
            'datetime_format' => $data_type['datetime_format'],
            'mandatory' => $field['nullable'] == 'No' ? true : false
        );
        
        if ($field['primary_key'] == 'Yes' || in_array($field['field_name'], array('date_created', 'date_modified', 'date_deleted'))){
            $html->add(new view_field_static($values));
        }elseif(!is_null($field['lookup_table'])){
            $table_schema = $_this->data_source->get_row_structure($field['lookup_table']);
            if (!is_null($table_schema) && is_array($table_schema)){
                $keys = array();
                $names_and_labels = array(); //Field names or the 'name' and 'label' fields if available
                //TODO: Result count, if more than 50 use select2
                foreach($table_schema as $item){
                    if ($item['primary_key'] == "Yes"){
                        $keys[] = $item['field_name'];
                    }
                    
                    if ($item['field_name'] == 'name' || $item['field_name'] == 'label'){
                        $names_and_labels[] = $item['field_name'];
                    }
                }
                
                if (count($keys) && count($names_and_labels));{
                    $sql = $_this->data_source->sql;
                    
                    $items = array_merge($keys, $names_and_labels);
                    $final = array();
                    for($i = 0; $i < count($items); $i++) $final[$items[$i]] = new \frameworks\adapt\sql($items[$i]);
                    
                    $sql->select($final);
                    
                    $sql->select($items)
                        ->from($item['table_name'])
                        ->where(
                            new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql('date_deleted'), ' is ', new \frameworks\adapt\sql('null'))
                        );
                    
                    $sql->execute();
                    
                    $results = $sql->results();
                    
                    $values['allowed_values'] = \frameworks\adapt\view_select::sql_result_to_assoc($results);
                    $html->add(new view_field_select($values));
                    
                }
                
                
            }
        }elseif(!is_null($field['allowed_values']) && is_json($values['allowed_values'])){
            $values['allowed_values'] = json_decode($values['allowed_values'], true);
            $html->add(new view_field_select($values));
        }elseif($data_type['name'] == 'text'){
            $html->add(new view_field_textarea($values));
        }else{
            $html->add(new view_field_input($values));
        }
    }
    
    //$html->add(new html_p('Hello world'));
    //$html->add(new \extensions\forms\view_field_static(array('name' => 'email', 'label' => 'email address', 'value' => 'mattloves.wales')));
    return $html;
});

/*
 * Lets register a view on the root controller
 * to handle ajax updates
 */

\application\controller_root::extend('view__forms', function($_this){
    return $_this->load_controller('\\extensions\\forms\\controller_forms');
});

?>