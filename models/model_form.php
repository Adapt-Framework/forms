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
                $page_ids = array();
                
                $page_buttons = array();
                $page_button_ids = array();
                
                $page_conditions = array();
                $page_condition_ids = array();
               
                $sections = array();
                $section_ids = array();
                
                $section_buttons = array();
                $section_conditions = array();
                
                $groups = array();
                $group_ids = array();
                
                $group_buttons = array();
                $group_conditions = array();
                
                $fields = array();
                $field_ids = array();
                
                $field_addons = array();
               
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
                
                $pages = $sql->execute()->results();
                foreach($pages as $page){
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
                
                $page_buttons = $sql->execute()->results();
                foreach($page_buttons as $button){
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
                    ->order_by('c.form_page_id')
                    ->order_by('c.priority');
                
                $page_conditions = $sql->execute()->results();
                foreach($page_conditions as $condition){
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
                
                $sections = $sql->execute()->results();
                foreach($sections as $section){
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
                
                $section_buttons = $sql->execute()->results();
                
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
                    ->order_by('c.form_page_section_id')
                    ->order_by('c.priority');
                
                $section_conditions = $sql->execute()->results();
                
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
                
                $groups = $sql->execute()->results();
                foreach($groups as $group){
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
                
                $group_buttons = $sql->execute()->results();
                
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
                    ->order_by('c.form_page_section_group_id')
                    ->order_by('c.priority');
                
                $group_conditions = $sql->execute()->results();
                
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
                
                $fields = $sql->execute()->results();
                foreach($fields as $field){
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
                
                $field_addons = $sql->execute()->results();
                
                
                foreach($pages as $page){
                    $model = new model_form_page();
                    $model->load_by_data($page);
                    $this->add($model);
                }
                
                foreach($page_buttons as $button){
                    $model = new model_form_page_button();
                    $model->load_by_data($button);
                    
                    foreach($this->get() as $child){
                        if ($child->table_name == 'form_page' && $child->form_page_id == $button['form_page_id']){
                            $child->add($model);
                        }
                    }
                }
                
                foreach($page_conditions as $condition){
                    $model = new model_form_page_condition();
                    $model->load_by_data($condition);
                    
                    foreach($this->get() as $child){
                        if ($child->table_name == 'form_page' && $child->form_page_id == $condition['form_page_id']){
                            $child->add($model);
                        }
                    }
                }
                
                foreach($sections as $section){
                    $model = new model_form_page_section();
                    $model->load_by_data($section);
                    
                    foreach($this->get() as $child){
                        if ($child->table_name == 'form_page' && $child->form_page_id == $section['form_page_id']){
                            $child->add($model);
                        }
                    }
                }
                
                foreach($section_buttons as $button){
                    $model = new model_form_page_section_button();
                    $model->load_by_data($button);
                    
                    foreach($this->get() as $page){
                        foreach($page->get() as $section){
                            if ($section->table_name == 'form_page_section' && $section->form_page_section_id == $button['form_page_section_id']){
                                $section->add($model);
                            }
                        }
                    }
                }
                
                foreach($section_conditions as $condition){
                    $model = new model_form_page_section_conditon();
                    $model->load_by_data($condition);
                    
                    foreach($this->get() as $page){
                        foreach($page->get() as $section){
                            if ($section->table_name == 'form_page_section' && $section->form_page_section_id == $condition['form_page_section_id']){
                                $section->add($model);
                            }
                        }
                    }
                }
                
                foreach($groups as $group){
                    $model = new model_form_page_section_group();
                    $model->load_by_data($group);
                    
                    foreach($this->get() as $page){
                        foreach($page->get() as $section){
                            if ($section->table_name == 'form_page_section' && $section->form_page_section_id == $group['form_page_section_id']){
                                $section->add($model);
                            }
                        }
                    }
                }
                
                foreach($group_buttons as $button){
                    $model = new model_form_page_section_group_button();
                    $model->load_by_data($button);
                    
                    foreach($this->get() as $page){
                        foreach($page->get() as $section){
                            foreach($section->get() as $group){
                                if ($group->table_name == 'form_page_section_group' && $group->form_page_section_group_id == $button['form_page_section_group_id']){
                                    $group->add($model);
                                }
                            }
                        }
                    }
                }
                
                foreach($group_conditions as $condition){
                    $model = new model_form_page_section_group_condition();
                    $model->load_by_data($condition);
                    
                    foreach($this->get() as $page){
                        foreach($page->get() as $section){
                            foreach($section->get() as $group){
                                if ($group->table_name == 'form_page_section_group' && $group->form_page_section_group_id == $condition['form_page_section_group_id']){
                                    $group->add($model);
                                }
                            }
                        }
                    }
                }
                
                foreach($fields as $field){
                    $model = new model_form_page_section_group_field();
                    $model->load_by_data($field);
                    
                    foreach($this->get() as $page){
                        foreach($page->get() as $section){
                            foreach($section->get() as $group){
                                if ($group->table_name == 'form_page_section_group' && $group->form_page_section_group_id == $field['form_page_section_group_id']){
                                    $group->add($model);
                                }
                            }
                        }
                    }
                }
                
                foreach($field_addons as $addon){
                    $model = new model_form_page_section_group_field_addon();
                    $model->load_by_data($addon);
                    
                    foreach($this->get() as $page){
                        foreach($page->get() as $section){
                            foreach($section->get() as $group){
                                foreach($group->get() as $field){
                                    if ($field->table_name == 'form_page_section_group_field' && $field->form_page_section_group_field_id == $addon['form_page_section_group_field_id']){
                                        $field->add($model);
                                    }
                                }
                            }
                        }
                    }
                }
                
            }
            
            return $return;
        }
        
        public function get_view($user_data = array()){
            if ($this->is_loaded){
                
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
                            $view->add($child->get_view($user_data));
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