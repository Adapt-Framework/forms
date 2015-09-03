<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model_form extends model{
        
        public function __construct($id = null){
            parent::__construct('form', $id);
        }
        
        /* Over-ride the initialiser to auto load children */
        public function initialise(){
            /* We must initialise first! */
            parent::initialise();
            
            /* We need to limit what we auto load */
            $this->_auto_load_only_tables = array(
                'form_page'
            );
            
            /* Switch on auto loading */
            //$this->_auto_load_children = true;
            
            
            
        }
        
        public function load_by_data($data){
            $return = parent::load_by_data($data);
            
            if ($return){
                /* We are going to auto load manually
                * so that we can load the data as fast as
                * possible
                */
                $pages = array();
                $page_objects = array();
                $page_ids = array();
                
                $page_buttons = array();
                $page_button_ids = array();
                
                $page_conditions = array();
                $page_condition_ids = array();
               
                $sections = array();
                $section_objects = array();
                $section_ids = array();
                
                $section_buttons = array();
                $section_conditions = array();
                
                $groups = array();
                $group_objects = array();
                $group_ids = array();
                
                $group_buttons = array();
                $group_conditions = array();
                
                $fields = array();
                $field_objects = array();
                $field_ids = array();
                
                $field_addons = array();
                
                $sql_cache_time = 60 * 60 * 12;
               
                /* Load pages */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page', 'p')
                    ->where(
                        new \frameworks\adapt\sql_and(
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('p.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('p.form_id'),
                                '=',
                                $this->form_id
                            )
                        )
                    )
                    ->order_by('p.priority');
                
                $pages = $sql->execute($sql_cache_time)->results();
                foreach($pages as $page){
                    $o = new model_form_page();
                    $o->load_by_data($page);
                    $page_objects[] = $o;
                    $this->add($o);
                    
                    $page_ids[] = $page['form_page_id'];
                }
                
                /* Load page buttons */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_button', 'b')
                    ->where(
                        new \frameworks\adapt\sql_and(
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('b.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('b.form_page_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $page_ids) . ')')
                            )
                        )
                    )
                    ->order_by('b.form_page_id')
                    ->order_by('b.priority');
                
                $page_buttons = $sql->execute($sql_cache_time)->results();
                foreach($page_buttons as $button){
                    $o = new model_form_page_button();
                    $o->load_by_data($button);
                    foreach($page_objects as $p){
                        if ($p->form_page_id == $o->form_page_id){
                            $p->add($o);
                            break;
                        }
                    }
                    
                    $page_button_ids[] = $button['form_page_button_id'];
                }
                
                /* Load page conditions */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_condition', 'c')
                    ->where(
                        new \frameworks\adapt\sql_and(
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('c.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('c.form_page_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $page_ids) . ')')
                            )
                        )
                    )
                    ->order_by('c.form_page_id');
                
                $page_conditions = $sql->execute($sql_cache_time)->results();
                foreach($page_conditions as $condition){
                    $o = new model_form_page_condition();
                    $o->load_by_data($condition);
                    foreach($page_objects as $p){
                        if ($p->form_page_id == $o->form_page_id){
                            $p->add($o);
                            break;
                        }
                    }
                    
                    $page_condition_ids[] = $condition['form_page_condition_id'];
                }
                
                /* Load sections */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section', 's')
                    ->where(
                        new \frameworks\adapt\sql_and(
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('s.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('s.form_page_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $page_ids) . ')')
                            )
                        )
                    )
                    ->order_by('s.form_page_id')
                    ->order_by('s.priority');
                
                $sections = $sql->execute($sql_cache_time)->results();
                foreach($sections as $section){
                    $o = new model_form_page_section();
                    $o->load_by_data($section);
                    $section_objects[] = $o;
                    foreach($page_objects as $p){
                        if ($p->form_page_id == $o->form_page_id){
                            $p->add($o);
                            break;
                        }
                    }
                    
                    $section_ids[] = $section['form_page_section_id'];
                }
                
                /* Load section buttons */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_button', 'b')
                    ->where(
                        new \frameworks\adapt\sql_and(
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('b.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('b.form_page_section_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $section_ids) . ')')
                            )
                        )
                    )
                    ->order_by('b.form_page_section_id')
                    ->order_by('b.priority');
                
                $section_buttons = $sql->execute($sql_cache_time)->results();
                foreach($section_buttons as $button){
                    $o = new model_form_page_section_button();
                    $o->load_by_data($button);
                    
                    foreach($section_objects as $p){
                        if ($p->form_page_section_id == $o->form_page_section_id){
                            $p->add($o);
                            break;
                        }
                    }
                }
                
                /* Load section conditions */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_condition', 'c')
                    ->where(
                        new \frameworks\adapt\sql_and(
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('c.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('c.form_page_section_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $section_ids) . ')')
                            )
                        )
                    )
                    ->order_by('c.form_page_section_id');
                
                $section_conditions = $sql->execute($sql_cache_time)->results();
                foreach($section_conditions as $condition){
                    $o = new model_form_page_section_condition();
                    $o->load_by_data($condition);
                    
                    foreach($section_objects as $p){
                        if ($p->form_page_section_id == $o->form_page_section_id){
                            $p->add($o);
                            break;
                        }
                    }
                }
                
                /* Load groups */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_group', 'g')
                    ->where(
                        new \frameworks\adapt\sql_and(
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('g.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('g.form_page_section_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $section_ids) . ')')
                            )
                        )
                    )
                    ->order_by('g.form_page_section_id')
                    ->order_by('g.priority');
                
                $groups = $sql->execute($sql_cache_time)->results();
                foreach($groups as $group){
                    $o = new model_form_page_section_group();
                    $o->load_by_data($group);
                    $group_objects[] = $o;
                    
                    foreach($section_objects as $p){
                        if ($p->form_page_section_id == $o->form_page_section_id){
                            $p->add($o);
                            break;
                        }
                    }
                    
                    $group_ids[] = $group['form_page_section_group_id'];
                }
                    
                    
                
                /* Load group buttons */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_group_button', 'b')
                    ->where(
                        new \frameworks\adapt\sql_and(
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('b.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('b.form_page_section_group_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $group_ids) . ')')
                            )
                        )
                    )
                    ->order_by('b.form_page_section_group_id')
                    ->order_by('b.priority');
                
                $group_buttons = $sql->execute($sql_cache_time)->results();
                
                foreach($group_buttons as $button){
                    $o = new model_form_page_section_group_button();
                    $o->load_by_data($button);
                    
                    foreach($group_objects as $p){
                        if ($p->form_page_section_group_id == $o->form_page_section_group_id){
                            $p->add($o);
                            break;
                        }
                    }
                }
                
                /* Load group conditions */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_group_condition', 'c')
                    ->where(
                        new \frameworks\adapt\sql_and(
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('c.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('c.form_page_section_group_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $group_ids) . ')')
                            )
                        )
                    )
                    ->order_by('c.form_page_section_group_id');
                
                $group_conditions = $sql->execute($sql_cache_time)->results();
                foreach($group_conditions as $condition){
                    $o = new model_form_page_section_group_condition();
                    $o->load_by_data($condition);
                    
                    foreach($group_objects as $p){
                        if ($p->form_page_section_group_id == $o->form_page_section_group_id){
                            $p->add($o);
                            break;
                        }
                    }
                }
                //print new html_pre('Group conditions:' . print_r($group_conditions, true));
                
                /* Load fields */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_group_field', 'f')
                    ->where(
                        new \frameworks\adapt\sql_and(
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('f.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('f.form_page_section_group_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $group_ids) . ')')
                            )
                        )
                    )
                    ->order_by('f.form_page_section_group_id')
                    ->order_by('f.priority');
                
                $fields = $sql->execute($sql_cache_time)->results();
                foreach($fields as $field){
                    $o = new model_form_page_section_group_field();
                    $o->load_by_data($field);
                    $field_objects[] = $o;
                    
                    foreach($group_objects as $p){
                        if ($p->form_page_section_group_id == $o->form_page_section_group_id){
                            $p->add($o);
                            break;
                        }
                    }
                    
                    $field_ids[] = $field['form_page_section_group_field_id'];
                }
                
                /* Load field addons */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_group_field_addon', 'a')
                    ->where(
                        new \frameworks\adapt\sql_and(
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('a.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \frameworks\adapt\sql_condition(
                                $this->data_source->sql('a.form_page_section_group_field_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $field_ids) . ')')
                            )
                        )
                    )
                    ->order_by('a.form_page_section_group_field_id')
                    ->order_by('a.priority');
                
                $field_addons = $sql->execute($sql_cache_time)->results();
                
                foreach($field_addons as $addon){
                    $o = new model_form_page_section_group_field_addon();
                    $o->load_by_data($addon);
                    
                    foreach($field_objects as $p){
                        if ($p->form_page_section_group_field_id == $o->form_page_section_group_field_id){
                            $p->add($o);
                            break;
                        }
                    }
                }
            }
            
            return $return;
        }
        
        public function get_view($user_data = array()){
            if ($this->is_loaded){
                
                $errors = array();
                if ($response = $this->response[$this->name]){
                    if (is_array($response) && is_array($response['errors'])){
                        $errors = array_merge($errors, $response['errors']);
                        
                        $response = $this->response['request'];
                        if (is_array($response)){
                            $user_data = array_merge($response, $user_data);
                        }
                        
                        $user_data = array_merge($this->request, $user_data);
                    }
                }
                
                //$actions = split(",", $this->actions);
                //$errors = array();
                //
                //foreach($actions as $action){
                //    $response = $this->response[$action];
                //    if (is_array($response) && is_array($response['errors'])){
                //        $errors = array_merge($errors, $response['errors']);
                //        
                //        $response = $this->response['request'];
                //        if (is_array($response)){
                //            $user_data = array_merge($response, $user_data);
                //        }
                //        
                //        $user_data = array_merge($this->request, $user_data);
                //    }
                //}
                
                $view = null;
                
                if (isset($this->custom_view) && trim($this->custom_view) != ''){
                    $class = $this->custom_view;
                    $view = new $class($this->to_hash(), $user_data);
                }else{
                    $view = new view_form($this->to_hash(), $user_data);
                }
                
                if ($view && $view instanceof \frameworks\adapt\html){
                    
                    for($i = 0; $i < $this->count(); $i++){
                        $child = $this->get($i);
                        if (is_object($child) && $child instanceof \frameworks\adapt\model && $child->table_name == 'form_page'){
                            $view->add($child->get_view($user_data, $errors));
                        }
                    }
                }
                
                return $view;
            }
            
            return null;
        }
    }
    
}

?>