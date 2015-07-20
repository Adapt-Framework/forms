<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model extends \frameworks\adapt\model{
        
        public static function get_form_page_section_layout($id){
            $adapt = $GLOBALS['adapt'];
            $layouts = $adapt->store('forms.form_page_section_layouts');
            
            if (!is_array($layouts)){
                $sql = $adapt->data_source->sql;
                
                $sql->select('*')
                    ->from('form_page_section_layout')
                    ->where(
                        new \frameworks\adapt\sql_condition(
                            $adapt->data_source->sql('date_deleted'),
                            'is',
                            $adapt->data_source->sql('null')
                        )
                    );
                
                $layouts = $sql->execute()->results();
                $adapt->store('forms.form_page_section_layouts', $layouts);
            }
            
            if (is_array($layouts)){
                foreach($layouts as $layout){
                    if ($layout['form_page_section_layout_id'] == $id){
                        $model = new model_form_page_section_layout();
                        $model->load_by_data($layout);
                        
                        return $model;
                    }
                }
            }
            
            return null;
        }
        
        public static function get_form_page_section_group_layout($id){
            $adapt = $GLOBALS['adapt'];
            $layouts = $adapt->store('forms.form_page_section_group_layouts');
            
            if (!is_array($layouts)){
                $sql = $adapt->data_source->sql;
                
                $sql->select('*')
                    ->from('form_page_section_group_layout')
                    ->where(
                        new \frameworks\adapt\sql_condition(
                            $adapt->data_source->sql('date_deleted'),
                            'is',
                            $adapt->data_source->sql('null')
                        )
                    );
                
                $layouts = $sql->execute()->results();
                $adapt->store('forms.form_page_section_group_layouts', $layouts);
            }
            
            if (is_array($layouts)){
                foreach($layouts as $layout){
                    if ($layout['form_page_section_group_layout_id'] == $id){
                        $model = new model_form_page_section_group_layout();
                        $model->load_by_data($layout);
                        
                        return $model;
                    }
                }
            }
            
            return null;
        }
        
        public static function get_form_field_type($id){
            $adapt = $GLOBALS['adapt'];
            $layouts = $adapt->store('forms.form_field_types');
            
            if (!is_array($layouts)){
                $sql = $adapt->data_source->sql;
                
                $sql->select('*')
                    ->from('form_field_type')
                    ->where(
                        new \frameworks\adapt\sql_condition(
                            $adapt->data_source->sql('date_deleted'),
                            'is',
                            $adapt->data_source->sql('null')
                        )
                    );
                
                $layouts = $sql->execute()->results();
                $adapt->store('forms.form_field_types', $layouts);
            }
            
            if (is_array($layouts)){
                foreach($layouts as $layout){
                    if ($layout['form_field_type_id'] == $id){
                        $model = new model_form_field_type();
                        $model->load_by_data($layout);
                        
                        return $model;
                    }
                }
            }
            
            return null;
        }
        
    }
    
}

?>