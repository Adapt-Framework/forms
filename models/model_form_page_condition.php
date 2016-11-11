<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model_form_page_condition extends model{
        
        protected $_depends_on_page_name;
        protected $_form_name;
        
        public function __construct($id = null){
            parent::__construct('form_page_condition', $id);
        }
        
        public function initialise(){
            parent::initialise();
            
            $this->_auto_load_only_tables = array(
                /*'form_page_section_group_field',*/
                /*'form_page_section_group_condition',
                'form_page_section_group_button'*/
            );
            
            //$this->_auto_load_children = true;
        }
        
        public function mget_depends_on_page_name(){
            return $this->_depends_on_page_name;
        }
        
        public function pset_depends_on_page_name($page_name){
            $this->_depends_on_page_name = $page_name;
        }
        
        public function mget_form_name(){
            return $this->_form_name;
        }
        
        public function pset_form_name($name) {
            $this->_form_name = $name;
        }
        
        public function save() {
            if (!$this->is_loaded) {
                // We need to resolve the depends_on_field_name
                $sql = $this->data_source->sql;
                $sql->select('form_page_section_group_field_id')
                    ->from('form', 'f')
                    ->join(
                        'form_page', 'p',
                        new sql_and(
                            new sql_cond('p.date_deleted', sql::IS, new sql_null()),
                            new sql_cond('p.form_id', sql::EQUALS, 'f.form_id')
                        )
                    )
                    ->where(
                        new sql_and(
                            new sql_cond('f.name', sql::EQUALS, sql::q($this->_form_name)),
                            new sql_cond('f.date_deleted', sql::IS, new sql_null()),
                            new sql_cond('d.name', sql::EQUALS, sql::q($this->_depends_on_field_name))
                        )
                    );
                
                $results = $sql->execute()->results();
                
                if (count($results) == 1) {
                    $this->depends_on_form_page_id = $results[0]['form_page_id'];
                } else {
                    $this->error("Unable to find the ID of field '{$this->_field_name}'");
                    return false;
                }
            }
            
            return parent::save();
        }
    }
}