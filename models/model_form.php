<?php

namespace adapt\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model_form extends model{
        
        protected $_form_data;
        
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
        
        public function pget_form_data(){
            return $this->_form_data;
        }
        
        public function pset_form_data($form_data){
            $this->_form_data = $form_data;
        }
        
        public function to_xml(){
            $xml = new xml_form();
            
            if ($this->is_loaded){
                //$xml->attr('form-id', $this->form_id);
                
                if ($this->custom_view){
                    $xml->attr('custom-view', $this->custom_view);
                }
                
                if ($this->submission_url){
                    $xml->attr('submission-url', $this->submission_url);
                }
                
                $xml->attr('method', $this->method);
                
                if ($this->actions){
                    $xml->attr('actions', $this->actions);
                }
                
                if ($this->name){
                    $xml->attr('name', $this->name);
                }
                
                if ($this->title){
                    $xml->attr('title', $this->title);
                }
                
                if ($this->description){
                    $xml->attr('description', $this->description);
                }
                
                if ($this->show_steps){
                    $xml->attr('show-steps', $this->show_steps);
                }
                
                if ($this->show_processing_page){
                    $xml->attr('show-processing-page', $this->show_processing_page);
                }
                
                foreach($this->_form_data['form_page'] as $page){
                    $xml_page = new xml_page();
                    
                    //$xml_page->attr('form-page-id', $page['form_page_id']);
                    //$xml_page->attr('form-id', $page['form_id']);
                    
                    if (isset($page['priority'])){
                        $xml_page->attr('priority', $page['priority']);
                    }
                    
                    if (isset($page['custom_view'])){
                        $xml_page->attr('custom-view', $page['custom_view']);
                    }
                    
                    if (isset($page['title'])){
                        $xml_page->attr('title', $page['title']);
                    }
                    
                    if (isset($page['description'])){
                        $xml_page->attr('desctipion', $page['description']);
                    }
                    
                    if (isset($page['step_title'])){
                        $xml_page->attr('step-title', $page['step_title']);
                    }
                    
                    if (isset($page['step_descripion'])){
                        $xml_page->attr('step-description', $page['step_description']);
                    }
                    
                    if (isset($page['step_custom_view'])){
                        $xml_page->attr('step-custom-view', $page['step_custom_view']);
                    }
                    
                    $xml->add($xml_page);

                    /* Add page sections */
                    foreach($this->_form_data['form_page_section'] as $section){
                        if ($page['form_page_id'] == $section['form_page_id']){
                            $xml_section = new xml_section();
                            //$xml_section->attr('form-page-section-id', $section['form_page_section_id']);
                            //$xml_section->attr('form-page-id', $section['form_page_id']);
                            $layout = new model_form_page_section_layout($section['form_page_section_layout_id']);
                            if ($layout->is_loaded){
                                $xml_section->attr('layout', $layout->name);
                            }

                            if (isset($section['custom_view'])){
                                $xml_section->attr('custom-view', $section['custom_view']);
                            }

                            if (isset($section['priority'])){
                                $xml_section->attr('priority', $section['priority']);
                            }

                            if (isset($section['repeatable'])){
                                $xml_section->attr('repeatable', $section['custom_view']);
                            }

                            if (isset($section['min_occurances'])){
                                $xml_section->attr('min-occurances', $section['min_occurances']);
                            }

                            if (isset($section['max_occurances'])){
                                $xml_section->attr('max-occurances', $section['max_occurances']);
                            }

                            if (isset($section['occurs_until'])){
                                $xml_section->attr('occurs-until', $section['occurs_until']);
                            }

                            if (isset($section['title'])){
                                $xml_section->attr('title', $section['title']);
                            }

                            if (isset($section['description'])){
                                $xml_section->attr('description', $section['description']);
                            }

                            if (isset($section['repeated_title'])){
                                $xml_section->attr('repeated-title', $section['repeated_title']);
                            }

                            if (isset($section['repeated_description'])){
                                $xml_section->attr('repeated-description', $section['repeated_description']);
                            }

                            $xml_page->add($xml_section);
                            //$xml->find('page[form-page-id="' . $section['form_page_id'] . '"]')->append($xml_section);

                            /* Add section buttons */
                            foreach($this->_form_data['form_page_section_button'] as $button){
                                if ($section['form_page_section_id'] == $button['form_page_section_id']){
                                    $xml_button = new xml_button();
                                    //$xml_button->attr('form-page-section-button-id', $button['form_page_section_button_id']);
                                    //$xml_button->attr('form-page-section-id', $button['form_page_section_id']);

                                    if (isset($button['custom_view'])){
                                        $xml_button->attr('custom-view', $button['custom_view']);
                                    }

                                    if (isset($button['prioriy'])){
                                        $xml_button->attr('priority', $button['priority']);
                                    }

                                    if (isset($button['form_page_button_style_id'])){
                                        $style = new model_form_button_style($button['form_page_button_style_id']);
                                        if ($style->is_loaded){
                                            $xml_button->attr('style', $style->name);
                                        }
                                    }

                                    if (isset($button['label'])){
                                        $xml_button->attr('label', $button['label']);
                                    }

                                    if (isset($button['icon_name'])){
                                        $xml_button->attr('icon-name', $button['icon_name']);
                                    }

                                    if (isset($button['icon_class'])){
                                        $xml_button->attr('icon-class', $button['icon_class']);
                                    }

                                    if (isset($button['action'])){
                                        $xml_button->attr('action', $button['action']);
                                    }

                                    if (isset($button['custom_action'])){
                                        $xml_button->attr('custom-action', $button['custom_action']);
                                    }

                                    $xml_section->add($xml_button);
                                }
                            }

                            /* Add page conditions */
                            foreach($this->_form_data['form_page_section_condition'] as $condition){
                                if ($section['form_page_section_id'] == $condition['form_page_section_id']){
                                    $xml_cond = new xml_condition();
                                    //$xml_cond->attr('form-page-section-condition-id', $condition['form_page_section_condition_id']);
                                    //$xml_cond->attr('form-page-section-id', $condition['form_page_section_id']);

                                    $field = new model_form_page_section_group_field($condition['depends_on_form_page_section_group_field_id']);
                                    if ($field->is_loaded){
                                        $xml_cond->attr('where-field', $field->name);
                                    }
                                    
                                    
                                    $xml_cond->attr('using-operator', $condition['operator']);
                                    $xml_cond->attr('has-value', $condition['value']);
                                    $xml_section->add($xml_cond);
                                }
                            }

                            /* Add the groups */
                            foreach($this->_form_data['form_page_section_group'] as $group){
                                if ($section['form_page_section_id'] == $group['form_page_section_id']){
                                    $xml_group = new xml_group();
                                    //$xml_group->attr('form-page-section-group_id', $group['form_page_section_group_id']);
                                    //$xml_group->attr('form-page-section-id', $group['form_page_section_id']);

                                    $layout = new model_form_page_section_group_layout($group['form_page_section_group_layout_id']);
                                    if ($layout->is_loaded){
                                        $xml_group->attr('layout', $layout->name);
                                    }

                                    if (isset($group['bundle_name'])){
                                        $xml_group->attr('bundle_name', $group['bundle_name']);
                                    }

                                    if (isset($group['custom_view'])){
                                        $xml_group->attr('custom_view', $group['custom_view']);
                                    }

                                    if (isset($group['priority'])){
                                        $xml_group->attr('priority', $group['priority']);
                                    }

                                    if (isset($group['label'])){
                                        $xml_group->attr('label', $group['label']);
                                    }

                                    if (isset($group['description'])){
                                        $xml_group->attr('description', $group['description']);
                                    }

                                    $xml_section->add($xml_group);
                                    
                                    /* Add group conditions */
                                    foreach($this->_form_data['form_page_section_group_condition'] as $condition){
                                        if ($group['form_page_section_group_id'] == $condition['form_page_section_group_id']){
                                            $xml_cond = new xml_condition();
                                            //$xml_cond->attr('form-page-condition_id',$condition['form_page_condition_id']);
                                            //$xml_cond->attr('form-page-id', $condition['form_page_id']);

                                            $field = new model_form_page_section_group_field($condition['depends_on_form_page_section_group_field_id']);
                                            if ($field->is_loaded){
                                                $xml_cond->attr('where-field', $field->name);
                                            }

                                            $xml_cond->attr('using-operator', $condition['operator']);
                                            $xml_cond->attr('has-value', $condition['value']);
                                            $xml_page->add($xml_cond);
                                        }
                                    }
                                    
                                    /* Add the fields */
                                    foreach($this->_form_data['form_page_section_group_field'] as $field){
                                        if ($group['form_page_section_group_id'] == $field['form_page_section_group_id']){
                                            $xml_field = new xml_field();
                                            //$xml_field->attr('form-page-section-group-field-id', $field['form_page_section_group_field_id']);
                                            //$xml_field->attr('form-page-section-group-id', $field['form_page_section_group_id']);
                                            
                                            if (isset($field['bundle_name'])){
                                                $xml_field->attr('bundle-name', $field['bundle_name']);
                                            }
                                            
                                            if (isset($field['custom_view'])){
                                                $xml_field->attr('custom-view', $field['custom_view']);
                                            }
                                            
                                            if (isset($field['priority'])){
                                                $xml_field->attr('priority', $field['priority']);
                                            }
                                            
                                            $type = new model_form_field_type($field['form_field_type_id']);
                                            if ($type->is_loaded){
                                                $xml_field->attr('type', $type->name);
                                            }
                                            
                                            $data_type = new model_data_type($field['data_type_id']);
                                            if ($data_type->is_loaded){
                                                $xml_field->attr('data-type', $data_type->name);
                                            }
                                            
                                            if (isset($field['name'])){
                                                $xml_field->attr('name', $field['name']);
                                            }
                                            
                                            if (isset($field['label'])){
                                                $xml_field->attr('label', $field['label']);
                                            }
                                            
                                            if (isset($field['description'])){
                                                $xml_field->attr('description', $field['description']);
                                            }
                                            
                                            if (isset($field['placeholder_label'])){
                                                $xml_field->attr('palceholder-label', $field['placeholder_label']);
                                            }
                                            
                                            if (isset($field['default_value'])){
                                                $xml_field->attr('default-value', $field['default_value']);
                                            }
                                            
                                            if (isset($field['lookup_table'])){
                                                $xml_field->attr('lookup-table', $field['lookup_table']);
                                            }
                                            
                                            if (isset($field['lookup_sql_statement'])){
                                                $xml_field->attr('lookup-sql-statement', $field['lookup_sql_statement']);
                                            }
                                            
                                            if (isset($field['lookup_method'])){
                                                $xml_field->attr('lookup-method', $field['lookup_method']);
                                            }
                                            
                                            if (isset($field['allowed_values'])){
                                                $xml_values = new xml_allowed_values();
                                                $values = json_decode($field['allowed_values'], true);
                                                if (is_assoc($values)){
                                                    foreach($values as $key => $value){
                                                        if (is_array($value)){
                                                            $xml_cat = new xml_category(['label' => $key]);
                                                            if (is_assoc($value)){
                                                                foreach($value as $k => $v){
                                                                    $xml_cat->add(new xml_value(['label' => $k, 'value' => $v]));
                                                                }
                                                                $xml_values->add($xml_cat);
                                                            }else{
                                                                foreach($value as $v){
                                                                    $xml_cat->add(new xml_value(['value' => $v]));
                                                                }
                                                                $xml_values->add($xml_cat);
                                                            }
                                                        }else{
                                                            $xml_values->add(new xml_value($value, ['label' => $key]));
                                                        }
                                                    }
                                                }else{
                                                    foreach($values as $value){
                                                        $xml_values->add(new xml_value($value));
                                                    }
                                                }
                                                $xml_field->add($xml_values);
                                            }
                                            
                                            if (isset($field['max_length'])){
                                                $xml_field->attr('max-length', $field['max_length']);
                                            }
                                            
                                            if (isset($field['mandatory'])){
                                                $xml_field->attr('mandatory', $field['mandatory']);
                                            }
                                            
                                            if (isset($field['mandatory_group'])){
                                                $xml_field->attr('mandatory-group', $field['mandatory_group']);
                                            }
                                            
                                            $xml_group->add($xml_field);
                                            
                                            /* Add field addons */
                                            foreach($this->_form_data['form_page_section_group_field_addon'] as $addon){
                                                if ($addon['form_page_section_group_field_id'] == $field['form_page_section_group_field_id']){
                                                    $xml_addon = new xml_addon();
                                                    //$xml_addon->attr('form-page-section-group-field-addon-id', $addon['form_page_section_group_field_addon_id']);
                                                    //$xml_addon->attr('form-page-section-group-field-id', $addon['form_page_section_group_field_id']);
                                                    
                                                    if (isset($addon['type'])){
                                                        $xml_addon->attr('type', $addon['type']);
                                                    }
                                                    
                                                    if (isset($addon['position'])){
                                                        $xml_addon->attr('position', $addon['position']);
                                                    }
                                                    
                                                    if (isset($addon['label'])){
                                                        $xml_addon->attr('label', $addon['label']);
                                                    }
                                                    
                                                    if (isset($addon['name'])){
                                                        $xml_addon->attr('name', $addon['name']);
                                                    }
                                                    
                                                    if (isset($addon['default_value'])){
                                                        $xml_addon->attr('default-value', $addon['default_value']);
                                                    }
                                                    
                                                    if (isset($addon['lookup_table'])){
                                                        $xml_addon->attr('lookup-table', $addon['lookup_table']);
                                                    }
                                                    
                                                    if (isset($addon['allowed_values'])){
                                                        $xml_values = new xml_allowed_values();
                                                        $values = json_decode($addon['allowed_values'], true);
                                                        if (is_assoc($values)){
                                                            foreach($values as $key => $value){
                                                                $xml_values->add(new xml_value($value, ['label' => $key]));
                                                            }
                                                            $xml_addon->add($xml_values);
                                                        }elseif(is_array($values)){
                                                            foreach($values as $value){
                                                                $xml_values->add(new xml_value($value));
                                                            }
                                                            $xml_addon->add($xml_values);
                                                        }
                                                    }
                                                    
                                                    if (isset($addon['icon_name'])){
                                                        $xml_addon->attr('icon-name', $addon['icon_name']);
                                                    }
                                                    
                                                    if (isset($addon['icon_class'])){
                                                        $xml_addon->attr('icon-class', $addon['icon_class']);
                                                    }
                                                    
                                                    $xml_field->add($xml_addon);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    /* Add page conditions */
                    foreach($this->_form_data['form_page_condition'] as $condition){
                        if ($page['form_page_id'] == $condition['form_page_id']){
                            $xml_cond = new xml_condition();
                            //$xml_cond->attr('form-page-condition_id',$condition['form_page_condition_id']);
                            //$xml_cond->attr('form-page-id', $condition['form_page_id']);

                            $field = new model_form_page_section_group_field($condition['depends_on_form_page_section_group_field_id']);
                            if ($field->is_loaded){
                                $xml_cond->attr('where-field', $field->name);
                            }

                            $xml_cond->attr('using-operator', $condition['operator']);
                            $xml_cond->attr('has-value', $condition['value']);
                            $xml_page->add($xml_cond);
                        }
                    }
                    
                    /* Add page buttons */
                    foreach($this->_form_data['form_page_button'] as $button){
                        if ($page['form_page_id'] == $button['form_page_id']){
                        
                            $xml_page_button = new xml_button();
                            //$xml_page_button->attr('form-page-button-id', $button['form_page_button_id']);
                            //$xml_page_button->attr('form-page-id', $button['form_page_id']);

                            if (isset($button['custom_view'])){
                                $xml_page_button->attr('custom-view', $button['custom_view']);
                            }

                            if (isset($button['prioriy'])){
                                $xml_page_button->attr('priority', $button['priority']);
                            }

                            if (isset($button['form_page_button_style_id'])){
                                $style = new model_form_button_style($button['form_page_button_style_id']);
                                if ($style->is_loaded){
                                    $xml_page_button->attr('style', $style->name);
                                }
                            }

                            if (isset($button['label'])){
                                $xml_page_button->attr('label', $button['label']);
                            }

                            if (isset($button['icon_name'])){
                                $xml_page_button->attr('icon-name', $button['icon_name']);
                            }

                            if (isset($button['icon_class'])){
                                $xml_page_button->attr('icon-class', $button['icon_class']);
                            }

                            if (isset($button['action'])){
                                $xml_page_button->attr('action', $button['action']);
                            }

                            if (isset($button['custom_action'])){
                                $xml_page_button->attr('custom-action', $button['custom_action']);
                            }

                            $xml_page->add($xml_page_button);
                        }
                    }
                }
            }
            
            return $xml;
        }
        
        public function load_by_data($data){
            $return = parent::load_by_data($data);
            
            if ($return){
                $form_data = $this->to_hash();
                
                $sql_cache_time = 0;
                
                /* Load pages */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page', 'p')
                    ->where(
                        new \adapt\sql_and(
                            new \adapt\sql_condition(
                                $this->data_source->sql('p.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \adapt\sql_condition(
                                $this->data_source->sql('p.form_id'),
                                '=',
                                $this->form_id
                            )
                        )
                    )
                    ->order_by('p.priority');
                
                $pages = $sql->execute($sql_cache_time)->results();
                
                foreach($pages as $page){
                    $form_data['form_page_id'][] = $page['form_page_id'];
                    $form_data['form_page'][] = $page;
                }
                
                /* Load page buttons */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_button', 'b')
                    ->where(
                        new \adapt\sql_and(
                            new \adapt\sql_condition(
                                $this->data_source->sql('b.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \adapt\sql_condition(
                                $this->data_source->sql('b.form_page_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $form_data['form_page_id']) . ')')
                            )
                        )
                    )
                    ->order_by('b.form_page_id')
                    ->order_by('b.priority');
                
                $page_buttons = $sql->execute($sql_cache_time)->results();
                foreach($page_buttons as $button){
                    $form_data['form_page_button'][] = $button;
                    $form_data['form_page_button_id'][] = $button['form_page_button_id'];
                }
                
                /* Load page conditions */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_condition', 'c')
                    ->where(
                        new \adapt\sql_and(
                            new \adapt\sql_condition(
                                $this->data_source->sql('c.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \adapt\sql_condition(
                                $this->data_source->sql('c.form_page_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $form_data['form_page_id']) . ')')
                            )
                        )
                    )
                    ->order_by('c.form_page_id');
                
                $page_conditions = $sql->execute($sql_cache_time)->results();
                foreach($page_conditions as $condition){
                    $form_data['form_page_condition'][] = $condition;
                    $form_data['form_page_condition_id'][] = $condition['form_page_condition_id'];
                }
                
                /* Load sections */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section', 's')
                    ->where(
                        new \adapt\sql_and(
                            new \adapt\sql_condition(
                                $this->data_source->sql('s.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \adapt\sql_condition(
                                $this->data_source->sql('s.form_page_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $form_data['form_page_id']) . ')')
                            )
                        )
                    )
                    ->order_by('s.form_page_id')
                    ->order_by('s.priority');
                
                $sections = $sql->execute($sql_cache_time)->results();
                foreach($sections as $section){
                    $form_data['form_page_section'][] = $section;
                    $form_data['form_page_section_id'][] = $section['form_page_section_id'];
                }
                
                /* Load section buttons */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_button', 'b')
                    ->where(
                        new \adapt\sql_and(
                            new \adapt\sql_condition(
                                $this->data_source->sql('b.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \adapt\sql_condition(
                                $this->data_source->sql('b.form_page_section_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $form_data['form_page_section_id']) . ')')
                            )
                        )
                    )
                    ->order_by('b.form_page_section_id')
                    ->order_by('b.priority');
                
                $section_buttons = $sql->execute($sql_cache_time)->results();
                foreach($section_buttons as $button){
                    $form_data['form_page_section_button'][] = $button;
                }
                
                /* Load section conditions */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_condition', 'c')
                    ->where(
                        new \adapt\sql_and(
                            new \adapt\sql_condition(
                                $this->data_source->sql('c.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \adapt\sql_condition(
                                $this->data_source->sql('c.form_page_section_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $form_data['form_page_section_id']) . ')')
                            )
                        )
                    )
                    ->order_by('c.form_page_section_id');
                
                $section_conditions = $sql->execute($sql_cache_time)->results();
                foreach($section_conditions as $condition){
                    $form_data['form_page_section_condition'][] = $condition;
                }
                
                
                /* Load groups */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_group', 'g')
                    ->where(
                        new \adapt\sql_and(
                            new \adapt\sql_condition(
                                $this->data_source->sql('g.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \adapt\sql_condition(
                                $this->data_source->sql('g.form_page_section_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $form_data['form_page_section_id']) . ')')
                            )
                        )
                    )
                    ->order_by('g.form_page_section_id')
                    ->order_by('g.priority');
                
                $groups = $sql->execute($sql_cache_time)->results();
                
                foreach($groups as $group){
                    $form_data['form_page_section_group'][] = $group;
                    $form_data['form_page_section_group_id'][] = $group['form_page_section_group_id'];
                }
                
                
                /* Load group buttons */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_group_button', 'b')
                    ->where(
                        new \adapt\sql_and(
                            new \adapt\sql_condition(
                                $this->data_source->sql('b.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \adapt\sql_condition(
                                $this->data_source->sql('b.form_page_section_group_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $form_data['form_page_section_group_id']) . ')')
                            )
                        )
                    )
                    ->order_by('b.form_page_section_group_id')
                    ->order_by('b.priority');
                
                $group_buttons = $sql->execute($sql_cache_time)->results();
                
                foreach($group_buttons as $button){
                    $form_data['from_page_section_group_button'][] = $button;
                }
                
                /* Load group conditions */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_group_condition', 'c')
                    ->where(
                        new \adapt\sql_and(
                            new \adapt\sql_condition(
                                $this->data_source->sql('c.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \adapt\sql_condition(
                                $this->data_source->sql('c.form_page_section_group_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $form_data['form_page_section_group_id']) . ')')
                            )
                        )
                    )
                    ->order_by('c.form_page_section_group_id');
                
                $group_conditions = $sql->execute($sql_cache_time)->results();
                foreach($group_conditions as $condition){
                    $form_data['form_page_section_group_condition'][] = $condition;
                }
                
                /* Load fields */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_group_field', 'f')
                    ->where(
                        new \adapt\sql_and(
                            new \adapt\sql_condition(
                                $this->data_source->sql('f.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \adapt\sql_condition(
                                $this->data_source->sql('f.form_page_section_group_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $form_data['form_page_section_group_id']) . ')')
                            )
                        )
                    )
                    ->order_by('f.form_page_section_group_id')
                    ->order_by('f.priority');
                
                $fields = $sql->execute($sql_cache_time)->results();
                foreach($fields as $field){
                    $form_data['form_page_section_group_field'][] = $field;
                    $form_data['form_page_section_group_field_id'][] = $field['form_page_section_group_field_id'];
                }
                
                
                /* Load field addons */
                $sql = $this->data_source->sql;
                $sql->select('*')
                    ->from('form_page_section_group_field_addon', 'a')
                    ->where(
                        new \adapt\sql_and(
                            new \adapt\sql_condition(
                                $this->data_source->sql('a.date_deleted'),
                                'is',
                                $this->data_source->sql('null')
                            ),
                            new \adapt\sql_condition(
                                $this->data_source->sql('a.form_page_section_group_field_id'),
                                'in',
                                $this->data_source->sql('(' . implode(",", $form_data['form_page_section_group_field_id']) . ')')
                            )
                        )
                    )
                    ->order_by('a.form_page_section_group_field_id');
                $field_addons = $sql->execute($sql_cache_time)->results();
                
                foreach($field_addons as $addon){
                    $form_data['form_page_section_group_field_addon'][] = $addon;
                }
                
                
                $this->_form_data = $form_data;
            }
            
            return $return;
        }
        
        public function get_view($user_data = array()){
            //$user_data = $this->convert_user_data($user_data);
            //return new html_pre(print_r($this->response, true));
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
                
                //TODO: Handle custom view
                
                /* Load button styles */
                $button_styles = $this->data_source->sql
                    ->select('*')
                    ->from('form_button_style')
                    ->where(
                        new \adapt\sql_condition(
                            new \adapt\sql('date_deleted'),
                            'is',
                            new \adapt\sql('null')
                        )
                    )
                    ->execute(60 * 60 * 12)
                    ->results();
                
                /* Load section layouts */
                $section_layouts = $this->data_source->sql
                    ->select('*')
                    ->from('form_page_section_layout')
                    ->where(
                        new \adapt\sql_condition(
                            new \adapt\sql('date_deleted'),
                            'is',
                            new \adapt\sql('null')
                        )
                    )
                    ->execute(60 * 60 * 12)
                    ->results();
                    
                /* Load group layouts */
                $group_layouts = $this->data_source->sql
                    ->select('*')
                    ->from('form_page_section_group_layout')
                    ->where(
                        new \adapt\sql_condition(
                            new \adapt\sql('date_deleted'),
                            'is',
                            new \adapt\sql('null')
                        )
                    )
                    ->execute(60 * 60 * 12)
                    ->results();
                
                /* Load field types */
                $field_types = $this->data_source->sql
                    ->select('*')
                    ->from('form_field_type')
                    ->where(
                        new \adapt\sql_condition(
                            new \adapt\sql('date_deleted'),
                            'is',
                            new \adapt\sql('null')
                        )
                    )
                    ->execute(60 * 60 * 12)
                    ->results();
                
                /* Create a form view */
                $view = null;
                
                if (isset($this->custom_view) && trim($this->custom_view) != ''){
                    $class = $this->custom_view;
                    $view = new $class($this->form_data, $user_data);
                }else{
                    $view = new view_form($this->_form_data['form'], $user_data);
                }
                
                /* Add the pages */
                foreach($this->_form_data['form_page'] as $page){
                    //print new html_pre(print_r($user_data, true));
                    if ($page['custom_view']) {
                        $custom_view = $page['custom_view'];
                        $view->add(new $custom_view($page, $user_data, $errors));
                    } else {
                        $view->add(new view_form_page($page, $user_data, $errors));
                    }
                }
                
                /* Add the buttons to the page */
                foreach($this->_form_data['form_page_button'] as $button){
                    //$view->add(new html_pre(print_r($button, true)));
                    $page = $view->find("[data-form-page-id='{$button['form_page_id']}']");
                    
                    if ($page->size() > 0){
                        $page = $page->get(0);
                        
                        $button_view = null;
                        $style = null;
                        
                        /* Get the style */
                        foreach($button_styles as $button_style){
                            if ($button_style['form_button_style_id'] == $button['form_button_style_id']){
                                $style = $button_style;
                                break;
                            }
                        }
                        
                        
                        
                        if (isset($button['custom_view']) && trim($button['custom_view']) != ""){
                            $class = $button['custom_view'];
                            if (class_exists($class)){
                                $button_view = new $class($button, $style);
                            }
                        }else{
                            $button_view = new view_form_page_button($button, $style);
                        }
                            
                        $page->add_control($button_view);
                    }
                }
                
                /* Add page conditions */
                foreach($this->_form_data['form_page_condition'] as $condition){
                    $page = $view->find("[data-form-page-id='{$condition['form_page_id']}']");
                    if ($page->size() > 0){
                        $page = $page->get(0);
                        $page->add_condition(new view_form_page_condition($condition, $user_data));
                    }
                }
                
                /* Add sections */
                foreach($this->_form_data['form_page_section'] as $section){
                    $page = $view->find("[data-form-page-id='{$section['form_page_id']}']");
                    
                    if ($page->size() > 0){
                        $class = $section['custom_view'];
                        $section_view = null;
                        
                        if (class_exists($class)){
                            $section_view = new $class($section, $user_data);
                        }else{
                            $section_view = new view_form_page_section($section, $user_data);
                        }
                        
                        $page = $page->get(0);
                        $page->add($section_view);
                        
                        /* Add layout engine */
                        foreach($section_layouts as $layout){
                            if ($layout['form_page_section_layout_id'] == $section['form_page_section_layout_id']){
                                $class = $layout['custom_view'];
                                
                                if (class_exists($class)){
                                    $section_view->add_layout_engine(new $class($layout));
                                }
                                
                                break;
                            }
                        }
                    }
                }
                /* Add section controls */
                foreach($this->_form_data['form_page_section_button'] as $button){
                    $section = $view->find("[data-form-page-section-id='{$button['form_page_section_id']}']");
                    
                    if ($section->size() > 0){
                        $section = $section->get(0);
                        
                        $button_view = null;
                        $style = null;
                        
                        /* Get the style */
                        foreach($button_styles as $button_style){
                            if ($button_style['form_button_style_id'] == $button['form_button_style_id']){
                                $style = $button_style;
                                break;
                            }
                        }
                        
                        
                        
                        if (isset($button['custom_view']) && trim($button['custom_view']) != ""){
                            $class = $button['custom_view'];
                            if (class_exists($class)){
                                $button_view = new $class($button, $style);
                            }
                        }else{
                            $button_view = new view_form_page_section_button($button, $style);
                        }
                            
                        $section->add_control($button_view);
                    }
                }
                
                /* Add section conditions */
                foreach($this->_form_data['form_page_section_condition'] as $condition){
                    $section = $view->find("[data-form-page-section-id='{$condition['form_page_section_id']}']");
                    $section->add(new html_pre(print_r($condition, true)));
                    if ($section->size() > 0){
                        $section = $section->get(0);
                        $section->add_condition(new view_form_page_section_condition($condition, $user_data));
                    }
                }
                
                /* Build groups */
                $group_container = new html_div(); //Temp container to hold the group until it's fully built
                foreach($this->_form_data['form_page_section_group'] as $group){
                    $group_view = new view_form_page_section_group($group, $user_data);
                    if ($group['custom_view']) {
                        $class = $group['custom_view'];
                        if (class_exists($class)){
                            $group_view = new $class($group, $user_data);
                        }
                    }
                    
                    
                    /* Add the layout engine */
                    if ($group_view instanceof view_form_page_section_group){
                        foreach($group_layouts as $layout){
                            if ($layout['form_page_section_group_layout_id'] == $group['form_page_section_group_layout_id']){
                                $class = $layout['custom_view'];

                                if (class_exists($class)){
                                    $group_view->add_layout_engine(new $class($layout));
                                }

                                break;
                            }
                        }
                    }
                    $group_container->add($group_view);
                }
                
                /* Add group controls */
                foreach($this->_form_data['form_page_section_group_button'] as $button){
                    $group = $group_container->find("[data-form-page-section-group-id='{$button['form_page_section_group_id']}']");
                    
                    if ($group->size() > 0){
                        $group = $group->get(0);
                        
                        $button_view = null;
                        $style = null;
                        
                        /* Get the style */
                        foreach($button_styles as $button_style){
                            if ($button_style['form_button_style_id'] == $button['form_button_style_id']){
                                $style = $button_style;
                                break;
                            }
                        }
                        
                        
                        
                        if (isset($button['custom_view']) && trim($button['custom_view']) != ""){
                            $class = $button['custom_view'];
                            if (class_exists($class)){
                                $button_view = new $class($button, $style);
                            }
                        }else{
                            $button_view = new view_form_page_section_group_button($button, $style);
                        }
                            
                        $group->add_control($button_view);
                    }
                }
                
                /* Add group conditions */
                foreach($this->_form_data['form_page_section_group_condition'] as $condition){
                    //print new html_pre(print_r($condition, true));
                    $group = $group_container->find("[data-form-page-section-group-id='{$condition['form_page_section_group_id']}']");
                    
                    if ($group->size() > 0){
                        $group = $group->get(0);
                        $group->add_condition(new view_form_page_section_group_condition($condition, $user_data));
                    }
                }
                
                /* Add fields */
                foreach($this->_form_data['form_page_section_group_field'] as $field){
                    $group = $group_container->find("[data-form-page-section-group-id='{$field['form_page_section_group_id']}']");
                    
                    if ($group->size() > 0){
                        $group = $group->get(0);
                        
                        $field_view = null;
                        $data_type = $this->data_source->get_data_type($field['data_type_id']);
                        
                        if ($field['allowed_values'] && trim($field['allowed_values']) != ""){
                            $field['allowed_values'] = json_decode($field['allowed_values'], true);
                        }elseif($field['lookup_table'] && trim($field['lookup_table']) != ""){
                            
                            /* Get the schema for the lookup table */
                            $struct = $this->data_source->get_row_structure($field['lookup_table']);
                            
                            if (count($struct)){
                                /* Do we have a label, name / date deleted field? */
                                $has_date_deleted = false;
                                $has_label = false;
                                $has_name = false;
                                $id_field = null;
                                $label_field = null;
                                
                                foreach($struct as $f){
                                    if ($f['field_name'] == 'date_deleted') $has_date_deleted = true;
                                    if ($f['field_name'] == 'label') $has_label = true;
                                    if ($f['field_name'] == 'name') $has_name = true;
                                    if ($f['primary_key'] == 'Yes') $id_field = $f['field_name'];
                                }
                                
                                if (!is_null($id_field) && ($has_label || $has_name)){
                                    if ($has_label){
                                        $label_field = 'label';
                                    }else{
                                        $label_field = 'name';
                                    }
                                    
                                    /* Build the query */
                                    $sql = $this->data_source->sql;
                                    
                                    $sql->select(array(
                                        'lookup_id' => $this->data_source->sql($id_field),
                                        'label' => $this->data_source->sql($label_field)
                                    ))
                                    ->from($field['lookup_table']);
                                    
                                    if ($has_date_deleted){
                                        $sql->where(
                                            new \adapt\sql_condition(
                                                $this->data_source->sql('date_deleted'),
                                                'is',
                                                $this->data_source->sql('null')
                                            )
                                        );
                                    }
                                    
                                    if ($label_field == 'label'){
                                        $sql->order_by('label');
                                    }
                                    
                                    $field['allowed_values'] = \adapt\view_select::sql_result_to_assoc($sql->execute()->results());
                                }
                                
                                
                                
                                //$group->add(new html_pre(print_r($struct, true)));
                            }
                        }elseif ($field['lookup_sql_statement']){
                            $statement_handle = $this->data_source->read($field['lookup_sql_statement']);
                            $results = null;

                            if ($statement_handle){
                                $results = $this->data_source->fetch($statement_handle, \adapt\data_source_sql::FETCH_ALL_ASSOC);
                            }
                            
                            if (is_array($results) && count($results) && isset($results[0]['id'])){
                                $allowed_values = [];

                                foreach($results as $result){
                                    $label_field = 'name';
                                    if (isset($result['label'])){
                                        $label_field = 'label';
                                    }

                                    if (!isset($result['permission_id']) || $result['permission_id'] == '' || is_null($result['permission_id']) || $this->session->user->has_permission($result['permission_id'])){
                                        $last_cat = null;
                                        if (isset($result['category'])){
                                            if ($last_cat != $result['category']){
                                                $last_cat = $result['category'];
                                                $allowed_values[$last_cat] = [];
                                            }
                                        }
                                        if (is_null($last_cat)){
                                            $allowed_values[$result['id']] = $result[$label_field];
                                        }else{
                                            $allowed_values[$last_cat][$result['id']] = $result[$label_field];
                                        }
                                    }
                                }

                                $field['allowed_values'] = $allowed_values;
                            }
                        }elseif(isset($field['lookup_class_name']) && isset($field['lookup_method'])){
                            $allowed_values = [];
                            $class_name = $field['lookup_class_name'];
                            $method = $field['lookup_method'];
                            if (class_exists($class_name)){
                                $class = new $class_name();
                                if (method_exists($class, $method)){
                                    if ($class instanceof \adapt\controller){
                                        $permission_method = "permission_{$method}";
                                        if (!method_exists($class, $permission_method) || $class->$permission_method()){
                                            $field['allowed_values'] = $class->$method();
                                        }
                                    }
                                }
                            }
                        }
                        
                        if (isset($field['custom_view'])){
                            $class = $field['custom_view'];
                            if (class_exists($class)){
                                $field_view = new $class($field, $data_type, $user_data);
                            }
                        }
                        
                        if (is_null($field_view)){
                            /* Get the field type */
                            $field_type = null;
                            
                            foreach($field_types as $type){
                                if ($type['form_field_type_id'] == $field['form_field_type_id']){
                                    $field_type = $type;
                                    break;
                                }
                            }
                            
                            if (isset($field_type) && isset($field_type['view'])){
                                $class = $field_type['view'];
                                //print "{$class}||";
                                if (class_exists($class)){
                                    $field_view = new $class($field, $data_type, $user_data);
                                }
                            }
                        }
                        
                        if ($field_view) $group->add($field_view);
                    }
                }
                
                
                /* Add add-ons to fields */
                foreach($this->_form_data['form_page_section_group_field_addon'] as $addon){
                    $field = $group_container->find("[data-form-page-section-group-field-id='{$addon['form_page_section_group_field_id']}']");
                    
                    if ($field->size()){
                        $field = $field->get(0);
                        
                        switch($addon['type']){
                        case 'Icon':
                            if (isset($addon['icon_class']) && isset($addon['icon_name'])){
                                $class = $addon['icon_class'];
                                if (class_exists($class)){
                                    $icon = new $class($addon['icon_name']);
                                    if ($icon instanceof \adapt\html){
                                        $field->add_addon(new html_span($icon, array('class' => 'input-group-addon', 'data-form-page-section-group-field-addon-id' => $addon['form_page_section_group_field_addon_id'])), $addon['position'] == 'Before' ? true : false);
                                    }
                                }
                            }
                            break;
                        case "Text":
                            if (isset($addon['label'])){
                                $field->add_addon(new html_span($addon['label'], array('class' => 'input-group-addon', 'data-form-page-section-group-field-addon-id' => $addon['form_page_section_group_field_addon_id'])), $addon['position'] == 'Before' ? true : false);
                            }
                            break;
                        case "Button":
                            $button = new html_button(array('class' => 'btn btn-default ' . $addon['name']));
                            if (isset($addon['icon_class']) && isset($addon['icon_name'])){
                                $class = $addon['icon_class'];
                                if (class_exists($class)){
                                    $icon = new $class($addon['icon_name']);
                                    if ($icon instanceof \adapt\html){
                                        $button->add($icon);
                                        $button->add(' ');
                                    }
                                }
                            }
                            
                            if (isset($addon['label'])){
                                $button->add($addon['label']);
                            }
                            
                            $field->add_addon(new html_span($button, array('class' => 'input-group-btn', 'data-form-page-section-group-field-addon-id' => $addon['form_page_section_group_field_addon_id'])), $addon['position'] == 'Before' ? true : false);
                            break;
                        case "Radio":
                            $radio = new html_input(array('type' => 'radio', 'name' => $addon['name'], 'value' => $addon['default_value']));
                            $field->add_addon(new html_span($radio, array('class' => 'input-group-addon', 'data-form-page-section-group-field-addon-id' => $addon['form_page_section_group_field_addon_id'])), $addon['position'] == 'Before' ? true : false);
                            break;
                        case "Checkbox":
                            $checkbox = new html_input(array('type' => 'checkbox', 'name' => $addon['name'], 'value' => $addon['default_value']));
                            $field->add_addon(new html_span($checkbox, array('class' => 'input-group-addon', 'data-form-page-section-group-field-addon-id' => $addon['form_page_section_group_field_addon_id'])), $addon['position'] == 'Before' ? true : false);
                            break;
                        case "Select":
                            if ($addon['allowed_values'] && trim($addon['allowed_values']) != ""){
                                $addon['allowed_values'] = json_decode($addon['allowed_values'], true);
                                
                            }elseif($addon['lookup_table'] && trim($addon['lookup_table']) != ""){
                                /* Get the schema for the lookup table */
                                $struct = $this->data_source->get_row_structure($addon['lookup_table']);
                                
                                if (count($struct)){
                                    /* Do we have a label, name / date deleted field? */
                                    $has_date_deleted = false;
                                    $has_label = false;
                                    $has_name = false;
                                    $id_field = null;
                                    $label_field = null;
                                    
                                    foreach($struct as $f){
                                        if ($f['field_name'] == 'date_deleted') $has_date_deleted = true;
                                        if ($f['field_name'] == 'label') $has_label = true;
                                        if ($f['field_name'] == 'name') $has_name = true;
                                        if ($f['primary_key'] == 'Yes') $id_field = $f['field_name'];
                                    }
                                    
                                    if (!is_null($id_field) && ($has_label || $has_name)){
                                        if ($has_label){
                                            $label_field = 'label';
                                        }else{
                                            $label_field = 'name';
                                        }
                                        
                                        /* Build the query */
                                        $sql = $this->data_source->sql;
                                        
                                        $sql->select(array(
                                            'lookup_id' => $this->data_source->sql($id_field),
                                            'label' => $this->data_source->sql($label_field)
                                        ))
                                        ->from($addon['lookup_table']);
                                        
                                        if ($has_date_deleted){
                                            $sql->where(
                                                new sql_cond(
                                                    'date_deleted',
                                                    sql::IS,
                                                    new sql_null()
                                                )
                                            );
                                        }
                                        
                                        if ($label_field == 'label'){
                                            $sql->order_by('label');
                                        }
                                        
                                        $addon['allowed_values'] = \adapt\view_select::sql_result_to_assoc($sql->execute()->results());
                                    }
                                    
                                    $value = $addon['default_value'];
                                    $key = $addon['name'];
                                    if ($user_data[$key]){
                                        $value = $user_data[$key];
                                    }
                                    if (!$value){
                                        $keys = array_keys($addon['allowed_values']);
                                        if (is_array($keys) && count($keys)){
                                            $value = $keys[0];
                                        }
                                    }
                                    
                                    $select = new view_dropdown_select($addon['name'], $addon['allowed_values'], $value);
                                    $select->remove_class('dropdown');
                                    $select->add_class('input-group-btn');
                                    $select->attr('ata-form-page-section-group-field-addon-id', $addon['form_page_section_group_field_addon_id']);
                                    $field->add_addon($select, $addon['position'] == 'Before' ? true : false);
                                    //$group->add(new html_pre(print_r($struct, true)));
                                }
                            }
                            break;
                        }
                    }
                }
                
                /* Add groups to sections */
                $groups = $group_container->get();
                foreach($groups as $group){
                    $section = $view->find(".form-page-section[data-form-page-section-id='" . $group->attr('data-form-page-section-id') . "']");
                    
                    if ($section->size() > 0){
                        $section = $section->get(0);
                        
                        $section->add($group);
                    }
                }
                
                
                
                //$view = new html_pre(print_r($this->_form_data, true));
                return $view;
                
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
                    $view = new $class($this->form_data, $user_data);
                }else{
                    $view = new view_form($this->form_data, $user_data);
                }
                
                //if ($view && $view instanceof \frameworks\adapt\html){
                //    
                //    for($i = 0; $i < $this->count(); $i++){
                //        $child = $this->get($i);
                //        if (is_object($child) && $child instanceof \frameworks\adapt\model && $child->table_name == 'form_page'){
                //            $view->add($child->get_view($user_data, $errors));
                //        }
                //    }
                //}
                
                return $view;
            }
            
            return null;
        }
        
        public function convert_user_data($user_data){
            $output = array();
            
            foreach($user_data as $name => $values){
                $key = $name;
                if (is_array($values) && is_assoc($values)){
                    foreach($values as $field => $value){
                        if (is_array($value)){
                            foreach($value as $v){
                                $key = "{$name}[$field][]";
                                $output[] = array('key' => $key, 'value' => $v, 'used' => false);
                            }
                        }else{
                            $key = "{$name}[$field]";
                            $output[] = array('key' => $key, 'value' => $value, 'used' => false);
                        }
                    }
                }else{
                    $output[] = array('key' => $key, 'value' => $values, 'used' => false);
                }
            }
            
            return $output;
        }
        
        public static function from_xml($xml){
            if ($xml instanceof \adapt\xml && $xml->tag == "form"){
                /* Get an instance of adapt base */
                $adapt = $GLOBALS['adapt'];
                
                /* We need to wrap the form in a <forms> tag */
                $xml = new xml_forms($xml);
                
                /* We need to load the forms bundle */
                $bundle = $adapt->bundles->load_bundle('forms');
                if (!$bundle instanceof \adapt\bundle || !$bundle->is_loaded){
                    return false;
                }
                
                /* Lets process the XML */
                $bundle->process_form_tag($bundle, $xml);
                
                /* Install the forms */
                $ids = $bundle->install_forms($bundle);
                
                /* Check if we succeeded */
                if ($ids === false || !is_array($ids) || count($ids) != 1){
                    return false;
                }
                
                /* Create the model */
                $form = new model_form($ids[0]);
                
                /* Check it loaded correctly */
                if (!$form->is_loaded){
                    return false;
                }
                
                /* Return the model */
                return $form;
            }

            return new model_form();
        }

    }
    
}

?>