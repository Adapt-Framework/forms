<?php

namespace adapt\forms{
    
    /* Prevent Direct Access */
    defined('ADAPT_STARTED') or die;
    
    class bundle_forms extends \adapt\bundle{
        
        protected $_forms;
        
        public function __construct($data){
            parent::__construct('forms', $data);
            
            $this->_forms = array();
            
            $this->register_config_handler('forms', 'forms', 'process_form_tag');
        }
        
        public function boot(){
            if (parent::boot()){
                
                $this->dom->head->add(new html_link(array('type' => 'text/css', 'rel' => 'stylesheet', 'href' => "/adapt/forms/forms-{$this->version}/static/css/forms.css")));
                $this->dom->head->add(new html_script(array('type' => 'text/javascript', 'src' => "/adapt/forms/forms-{$this->version}/static/js/forms.js")));
                $this->dom->head->add(new html_script(array('type' => 'text/javascript', 'src' => "/adapt/forms/forms-{$this->version}/static/js/reflow.js")));
                
                /* Extend model and add a form view */
                \adapt\model::extend('to_form', function($_this, $title, $submission_url, $actions){
                    $structure = $this->data_source->get_row_structure($_this->table_name);
                    
                    $user_data = array_merge($_this->request, $_this->to_hash_string());
                    
                    $data = array(
                        'submission_url' => $submission_url,
                        'method' => 'post',
                        'actions' => $actions,
                        'show_steps' => 'No',
                        'show_processing_page' => 'Yes'
                    );
                    $form_view = new \adapt\forms\view_form($data, $user_data);
                    
                    $data = array();
                    $form_page = new \adapt\forms\view_form_page($data, $user_data);
                    $form_view->add($form_page);
                    
                    $form_page->add_control(new html_button("Save changes", array('class' => 'btn btn-primary control next')));
                    
                    $data = array(
                        'repeatable' => 'No'
                    );
                    $form_section = new \adapt\forms\view_form_page_section($data, $user_data);
                    $form_page->add($form_section);
                    
                    $layout_engine = new \adapt\forms\view_section_layout_standard(array());
                    $form_section->add_layout_engine($layout_engine);
                    
                    foreach($structure as $field){
                        /*if ($field['primary_key'] == "Yes"){
                            $form_group = new \adapt\forms\view_form_page_section_group(array(), $user_data);
                            $form_section->add($form_group);
                            
                            $layout_engine = new \adapt\forms\view_group_layout_simple(array());
                            $form_group->add_layout_engine($layout_engine);
                            
                            $data = array(
                                'name' => $field['table_name'] . "[" . $field['field_name'] . "]"
                            );
                            
                            $field_view = new \adapt\forms\view_field_hidden($data, $this->data_source->get_data_type($field['data_type_id']), $user_data);
                            $form_group->add($field_view);
                            
                        }else*/if($field['primary_key'] == "Yes" || $field['field_name'] == "date_created" || $field['field_name'] == "date_modified"){
                            
                            $form_group = new \adapt\forms\view_form_page_section_group(array(), $user_data);
                            $form_section->add($form_group);
                            
                            $layout_engine = new \adapt\forms\view_group_layout_simple(array());
                            $form_group->add_layout_engine($layout_engine);
                            
                            $data = array(
                                'name' => $field['table_name'] . "[" . $field['field_name'] . "]",
                                'label' => $field['label'],
                                'description' => $field['description']
                            );
                            
                            $field_view = new \adapt\forms\view_field_static($data, $this->data_source->get_data_type($field['data_type_id']), $user_data);
                            $form_group->add($field_view);
                            
                        }elseif ($field['field_name'] != "date_deleted" /*&& (!$field['referenced_table_name'] || $field['allowed_values'] || $field['lookup_table'])*/){
                            $form_group = new \adapt\forms\view_form_page_section_group(array(), $user_data);
                            $form_section->add($form_group);
                            
                            $layout_engine = new \adapt\forms\view_group_layout_simple(array());
                            $form_group->add_layout_engine($layout_engine);
                            
                            $data = array(
                                'name' => $field['table_name'] . "[" . $field['field_name'] . "]",
                                'label' => $field['label'],
                                'description' => $field['description'],
                                'placeholder_label' => $field['placeholder_label'],
                                'default_value' => $field['default_value'],
                                'mandatory' => $field['nullable'] == "No" ? "Yes" : "No"
                            );
                            
                            //$form_group->add(new html_pre(print_r($data, true)));
                            if ($field['allowed_values']){
                                $data['allowed_values'] = json_decode($field['allowed_values'], true);
                                $field_view = new \adapt\forms\view_field_select($data, $this->data_source->get_data_type($field['data_type_id']), $user_data);
                                $form_group->add($field_view);
                            }elseif($field['lookup_table'] && trim($field['lookup_table']) != ""){
                                
                                /* Get the schema for the lookup table */
                                $struct = $_this->data_source->get_row_structure($field['lookup_table']);
                                
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
                                        $sql = $_this->data_source->sql;
                                        
                                        $sql->select(array(
                                            'lookup_id' => $_this->data_source->sql($id_field),
                                            'label' => $_this->data_source->sql($label_field)
                                        ))
                                        ->from($field['lookup_table']);
                                        
                                        if ($has_date_deleted){
                                            $sql->where(
                                                new \adapt\sql_condition(
                                                    $_this->data_source->sql('date_deleted'),
                                                    'is',
                                                    $_this->data_source->sql('null')
                                                )
                                            );
                                        }
                                        
                                        if ($label_field == 'label'){
                                            $sql->order_by('label');
                                        }
                                        
                                        $data['allowed_values'] = \adapt\view_select::sql_result_to_assoc($sql->execute()->results());
                                    }
                                    
                                    $field_view = new \adapt\forms\view_field_select($data, $this->data_source->get_data_type($field['data_type_id']), $user_data);
                                    $form_group->add($field_view);
                                    
                                    //$group->add(new html_pre(print_r($struct, true)));
                                }
                            }else{
                                
                                $field_view = new \adapt\forms\view_field_input($data, $this->data_source->get_data_type($field['data_type_id']), $user_data);
                                $form_group->add($field_view);
                            }
                        }
                    }
                    
                    
                    
                    //$form_view->add(new html_pre(print_r($structure, true)));
                    
                    return $form_view;
                });
                
                return true;
            }
            
            return false;
        }
        
        public function install_forms($bundle){
            if ($bundle instanceof \adapt\bundle){
                foreach($this->_forms as $form){
                    if ($form['bundle_name'] == $bundle->name){
                        $model_form = new model_form();
                        $model_form->bundle_name = $form['bundle_name'];
                        $model_form->custom_view = $form['custom_view'];
                        $model_form->submission_url = $form['submission_url'];
                        $model_form->method = $form['method'];
                        $model_form->actions = $form['actions'];
                        $model_form->name = $form['name'];
                        $model_form->title = $form['title'];
                        $model_form->description = $form['description'];
                        $model_form->show_steps = $form['show_steps'];
                        $model_form->show_processing_page = $form['show_processing_page'];
                        
                        foreach($form['pages'] as $page){
                            $model_page = new model_form_page();
                            $model_page->bundle_name = $page['bundle_name'];
                            $model_page->priority = $page['priority'];
                            $model_page->custom_view = $page['custom_view'];
                            $model_page->title = $page['title'];
                            $model_page->description = $page['description'];
                            $model_page->step_title = $page['step_title'];
                            $model_page->step_description = $page['step_description'];
                            $model_page->step_custom_view = $page['step_custom_view'];
                            
                            foreach($page['buttons'] as $button){
                                $style = new model_form_button_style();
                                if ($style->load_by_name($button['form_button_style_id'])){
                                    $model_button = new model_form_page_button();
                                    $model_button->bundle_name = $button['bundle_name'];
                                    $model_button->custom_view = $button['custom_view'];
                                    $model_button->priority = $button['priority'];
                                    $model_button->form_button_style_id = $style->form_button_style_id;
                                    $model_button->label = $button['label'];
                                    $model_button->icon_name = $button['icon_name'];
                                    $model_button->icon_class = $button['icon_class'];
                                    $model_button->action = $button['action'];
                                    $model_button->custom_action = $button['custom_action'];
                                    
                                    $model_page->add($model_button);
                                }
                            }
                            
                            //Conditions will have to be done after save else we don't know the field name
                            //foreach($page['conditions'] as $condition){
                            //    $model_condition = new model_page_condition();
                            //    $model_condition->bundle_name = $condition['bundle_name'];
                            //    $model_condition->bundle_name = $condition['bundle_name'];
                            //}
                            
                            foreach($page['sections'] as $section){
                                
                                $layout = new model_form_page_section_layout();
                                if ($layout->load_by_name($section['form_page_section_layout_id'])){
                                    $model_section = new model_form_page_section();
                                    $model_section->form_page_section_layout_id = $layout->form_page_section_layout_id;
                                    $model_section->bundle_name = $section['bundle_name'];
                                    $model_section->custom_view = $section['custom_view'];
                                    $model_section->priority = $section['priority'];
                                    $model_section->repeatable = $section['repeatable'];
                                    $model_section->min_occurances = $section['min_occurances'];
                                    $model_section->max_occurances = $section['max_occurances'];
                                    $model_section->occurs_until = $section['occurs_until'];
                                    $model_section->title = $section['title'];
                                    $model_section->description = $section['description'];
                                    $model_section->repeated_title = $section['repeated_title'];
                                    $model_section->repeated_description = $section['repeated_description'];
                                    
                                    foreach($section['buttons'] as $button){
                                        $style = new model_form_button_style();
                                        if ($style->load_by_name($button['form_button_style_id'])){
                                            $model_button = new model_form_page_section_button();
                                            $model_button->bundle_name = $button['bundle_name'];
                                            $model_button->custom_view = $button['custom_view'];
                                            $model_button->priority = $button['priority'];
                                            $model_button->form_button_style_id = $style->form_button_style_id;
                                            $model_button->label = $button['label'];
                                            $model_button->icon_name = $button['icon_name'];
                                            $model_button->icon_class = $button['icon_class'];
                                            $model_button->action = $button['action'];
                                            $model_button->custom_action = $button['custom_action'];
                                            
                                            $model_section->add($model_button);
                                        }
                                    }
                                    
                                    //Conditions to be added afterwards
                                    
                                    foreach($section['groups'] as $group){
                                        $layout = new model_form_page_section_group_layout();
                                        if ($layout->load_by_name($group['form_page_section_group_layout_id']) || isset($group['custom_view'])){
                                            $model_group = new model_form_page_section_group();
                                            $model_group->bundle_name = $group['bundle_name'];
                                            $model_group->form_page_section_group_layout_id = $layout->form_page_section_group_layout_id;
                                            $model_group->custom_view = $group['custom_view'];
                                            $model_group->priority = $group['priority'];
                                            $model_group->label = $group['label'];
                                            $model_group->description = $group['description'];
                                            
                                            foreach($group['buttons'] as $button){
                                                $style = new model_form_button_style();
                                                if ($style->load_by_name($button['form_button_style_id'])){
                                                    $model_button = new model_form_page_section_group_button();
                                                    $model_button->bundle_name = $button['bundle_name'];
                                                    $model_button->custom_view = $button['custom_view'];
                                                    $model_button->priority = $button['priority'];
                                                    $model_button->form_button_style_id = $style->form_button_style_id;
                                                    $model_button->label = $button['label'];
                                                    $model_button->icon_name = $button['icon_name'];
                                                    $model_button->icon_class = $button['icon_class'];
                                                    $model_button->action = $button['action'];
                                                    $model_button->custom_action = $button['custom_action'];
                                                    
                                                    $model_group->add($model_button);
                                                }
                                            }
                                            
                                            //Conditions to be added afterwards
                                            if (count($group['conditions'])){
                                                foreach($group['conditions'] as $condition){
                                                    $model_condition = new model_form_page_section_group_condition();
                                                    $model_condition->depends_on_field_name = $condition['depends_on_form_page_section_group_field_id'];
                                                    $model_condition->operator = $condition['operator'];
                                                    $model_condition->value = $condition['value'];
                                                    $model_condition->form_name = $form['name'];
                                                    $model_group->add($model_condition);
                                                }
                                            }
                                            
                                            
                                            foreach($group['fields'] as $field){
                                                $type = new model_form_field_type();
                                                if ($type->load_by_name($field['form_field_type_id'])){
                                                    $model_field = new model_form_page_section_group_field();
                                                    $model_field->bundle_name = $field['bundle_name'];
                                                    $model_field->custom_view = $field['custom_view'];
                                                    $model_field->priority = $field['priority'];
                                                    $model_field->data_type_id = $this->data_source->get_data_type_id($field['data_type']);
                                                    $model_field->form_field_type_id = $type->form_field_type_id;
                                                    $model_field->name = $field['name'];
                                                    $model_field->label = $field['label'];
                                                    $model_field->description = $field['description'];
                                                    $model_field->placeholder_label = $field['placeholder_label'];
                                                    $model_field->default_value = $field['default_value'];
                                                    $model_field->lookup_table = $field['lookup_table'];
                                                    $model_field->allowed_values = $field['allowed_values'];
                                                    $model_field->max_length = $field['max_length'];
                                                    $model_field->mandatory = $field['mandatory'];
                                                    $model_field->mandatory_group = $field['mandatory_group'];
                                                    
                                                    foreach($field['addons'] as $addon){
                                                        $model_addon = new model_form_page_section_group_field_addon();
                                                        $model_addon->type = $addon['type'];
                                                        $model_addon->position = $addon['position'];
                                                        $model_addon->label = $addon['label'];
                                                        $model_addon->name = $addon['name'];
                                                        $model_addon->default_value = $addon['default_value'];
                                                        $model_addon->lookup_table = $addon['lookup_table'];
                                                        $model_addon->allowed_values = $addon['allowed_values'];
                                                        $model_addon->icon_name = $addon['icon_name'];
                                                        $model_addon->icon_class = $addon['icon_class'];
                                                        
                                                        $model_field->add($model_addon);
                                                    }
                                                    
                                                    $model_group->add($model_field);
                                                }
                                            }
                                            
                                            $model_section->add($model_group);
                                        }
                                    }
                                    
                                    $model_page->add($model_section);
                                }
                                
                            }
                            
                            $model_form->add($model_page);
                        }
                        
                        $model_form->save();
                    }
                }
            }
        }
        
        public function process_form_tag($bundle, $tag_data){
            if ($bundle instanceof \adapt\bundle && $tag_data instanceof \adapt\xml){
                
                $this->register_install_handler($this->name, $bundle->name, 'install_forms');
                
                $children = $tag_data->get();
                
                foreach($children as $child){
                    if ($child instanceof \adapt\xml){
                        if ($child->tag == 'form'){
                            
                            $form = array(
                                'bundle_name' => $bundle->name,
                                'custom_view' => $child->attr('custom-view'),
                                'submission_url' => $child->attr('submission-url'),
                                'method' => $child->attr('method'),
                                'actions' => $child->attr('actions'),
                                'name' => $child->attr('name'),
                                'title' => $child->attr('title'),
                                'description' => $child->attr('description'),
                                'show_steps' => $child->attr('show-steps'),
                                'show_processing_page' => $child->attr('show_processing_page'),
                                'pages' => array()
                            );
                            
                            
                            $form_children = $child->get();
                            
                            foreach($form_children as $form_child){
                                if ($form_child instanceof \adapt\xml && $form_child->tag == "page"){
                                    $page = array(
                                        'bundle_name' => $bundle->name,
                                        'priority' => count($form['pages']) + 1,
                                        'custom_view' => $form_child->attr('custom-view'),
                                        'title' => $form_child->attr('title'),
                                        'description' => $form_child->attr('description'),
                                        'step_title' => $form_child->attr('step-title'),
                                        'step_description' => $form_child->attr('step-description'),
                                        'step_custom_view' => $form_child->attr('step-custom-view'),
                                        'buttons' => array(),
                                        'conditions' => array(),
                                        'sections' => array()
                                    );
                                    
                                    $page_children = $form_child->get();
                                    
                                    foreach($page_children as $page_child){
                                        if ($page_child instanceof \adapt\xml){
                                            switch($page_child->tag){
                                            case "condition":
                                                $condition = array(
                                                    'bundle_name' => $bundle->name,
                                                    'depends_on_form_page_section_group_field_id' => $page_child->attr('where-field'),
                                                    'operator' => $page_child->attr('using-operator'),
                                                    'value' => $page_child->attr('has-value')
                                                );
                                                
                                                $page['conditions'][] = $condition;
                                                break;
                                            case "button":
                                                $button = array(
                                                    'bundle_name' => $bundle->name,
                                                    'custom_view' => $page_child->attr('custom-view'),
                                                    'priority' => count($page['buttons']) + 1,
                                                    'form_button_style_id' => $page_child->attr('style'),
                                                    'label' => $page_child->attr('label'),
                                                    'icon_name' => $page_child->attr('icon-name'),
                                                    'icon_class' => $page_child->attr('icon-class'),
                                                    'action' => $page_child->attr('action'),
                                                    'custom_action' => $page_child->attr('custom-action')
                                                );
                                                
                                                $page['buttons'][] = $button;
                                                break;
                                            case "section":
                                                $section = array(
                                                    'bundle_name' => $bundle->name,
                                                    'form_page_section_layout_id' => $page_child->attr('layout'),
                                                    'custom_view' => $page_child->attr('custom-view'),
                                                    'priority' => count($page['sections']) + 1,
                                                    'repeatable' => $page_child->attr('repeatable'),
                                                    'min_occurances' => $page_child->attr('min-occurs'),
                                                    'max_occurances' => $page_child->attr('max-occurs'),
                                                    'occurs_until' => $page_child->attr('occurs-until'),
                                                    'title' => $page_child->attr('title'),
                                                    'description' => $page_child->attr('description'),
                                                    'repeated_title' => $page_child->attr('repeated-title'),
                                                    'repeated_description' => $page_child->attr('repeated-description'),
                                                    'conditions' => array(),
                                                    'buttons' => array(),
                                                    'groups' => array()
                                                );
                                                
                                                $section_children = $page_child->get();
                                                
                                                foreach($section_children as $section_child){
                                                    if ($section_child instanceof \adapt\xml){
                                                        switch($section_child->tag){
                                                        case "condition":
                                                            $condition = array(
                                                                'bundle_name' => $bundle->name,
                                                                'depends_on_form_page_section_group_field_id' => $section_child->attr('where-field'),
                                                                'operator' => $section_child->attr('using-operator'),
                                                                'value' => $section_child->attr('has-value')
                                                            );
                                                            
                                                            $section['conditions'][] = $condition;
                                                            break;
                                                        case "button":
                                                            $button = array(
                                                                'bundle_name' => $bundle->name,
                                                                'custom_view' => $section_child->attr('custom-view'),
                                                                'priority' => count($section['buttons']) + 1,
                                                                'form_button_style_id' => $section_child->attr('style'),
                                                                'label' => $section_child->attr('label'),
                                                                'icon_name' => $section_child->attr('icon-name'),
                                                                'icon_class' => $section_child->attr('icon-class'),
                                                                'action' => $section_child->attr('action'),
                                                                'custom_action' => $section_child->attr('custom-action')
                                                            );
                                                            
                                                            $section['buttons'][] = $button;
                                                            break;
                                                        case "group":
                                                            $group = array(
                                                                'bundle_name' => $bundle->name,
                                                                'form_page_section_group_layout_id' => $section_child->attr('layout'),
                                                                'custom_view' => $section_child->attr('custom-view'),
                                                                'priority' => count($section['groups']) + 1,
                                                                'label' => $section_child->attr('label'),
                                                                'description' => $section_child->attr('description'),
                                                                'conditions' => array(),
                                                                'buttons' => array(),
                                                                'fields' => array()
                                                            );
                                                            
                                                            $group_children = $section_child->get();
                                                            
                                                            foreach($group_children as $group_child){
                                                                if ($group_child instanceof \adapt\xml){
                                                                    switch($group_child->tag){
                                                                    case "condition":
                                                                        $condition = array(
                                                                            'bundle_name' => $bundle->name,
                                                                            'depends_on_form_page_section_group_field_id' => $group_child->attr('where-field'),
                                                                            'operator' => $group_child->attr('using-operator'),
                                                                            'value' => $group_child->attr('has-value')
                                                                        );

                                                                        $group['conditions'][] = $condition;
                                                                        break;
                                                                    case "button":
                                                                        $button = array(
                                                                            'bundle_name' => $bundle->name,
                                                                            'custom_view' => $group_child->attr('custom-view'),
                                                                            'priority' => count($group['buttons']) + 1,
                                                                            'form_button_style_id' => $group_child->attr('style'),
                                                                            'label' => $group_child->attr('label'),
                                                                            'icon_name' => $group_child->attr('icon-name'),
                                                                            'icon_class' => $group_child->attr('icon-class'),
                                                                            'action' => $group_child->attr('action'),
                                                                            'custom_action' => $group_child->attr('custom-action')
                                                                        );
                                                                        
                                                                        $group['buttons'][] = $button;
                                                                        break;
                                                                    case "field":
                                                                        $field = array(
                                                                            'bundle_name' => $bundle->name,
                                                                            'custom_view' => $group_child->attr('custom-view'),
                                                                            'priority' => count($group['fields']) + 1,
                                                                            'form_field_type_id' => $group_child->attr('type'),
                                                                            'data_type' => $group_child->attr('data-type'),
                                                                            'name' => $group_child->attr('name'),
                                                                            'label' => $group_child->attr('label'),
                                                                            'description' => $group_child->attr('description'),
                                                                            'placeholder_label' => $group_child->attr('placeholder-label'),
                                                                            'default_value' => $group_child->attr('default-value'),
                                                                            'lookup_table' => $group_child->attr('lookup-table'),
                                                                            'lookup_endpoint' => $group_child->attr('lookup-endpoint'),
                                                                            'lookup_sql' => $group_child->attr('lookup-sql'),
                                                                            'allowed_values' => null,
                                                                            'max_length' => $group_child->attr('max-length'),
                                                                            'mandatory' => $group_child->attr('mandatory'),
                                                                            'mandatory_group' => $group_child->attr('mandatory-group'),
                                                                            'addons' => array()
                                                                        );
                                                                        
                                                                        $field_children = $group_child->get();
                                                                        
                                                                        foreach($field_children as $field_child){
                                                                            if ($field_child instanceof \adapt\xml){
                                                                                switch($field_child->tag){
                                                                                case "allowed_values":
                                                                                    $values = array();
                                                                                    $allowed_children = $field_child->get();
                                                                                    foreach($allowed_children as $allowed_child){
                                                                                        if ($allowed_child instanceof \adapt\xml && $allowed_child->tag == "value"){
                                                                                            $label = $allowed_child->attr('label');
                                                                                            $value = $allowed_child->get(0);
                                                                                            
                                                                                            if (is_null($label)){
                                                                                                $values[] = $value;
                                                                                            }else{
                                                                                                $values[$value] = $label;
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                    
                                                                                    $field['allowed_values'] = json_encode($values);
                                                                                    break;
                                                                                case "addon":
                                                                                    $addon = array(
                                                                                        'type' => $field_child->attr('type'),
                                                                                        'position' => $field_child->attr('position'),
                                                                                        'label' => $field_child->attr('label'),
                                                                                        'name' => $field_child->attr('name'),
                                                                                        'default_value' => $field_child->attr('default-value'),
                                                                                        'lookup_table' => $field_child->attr('lookup-table'),
                                                                                        'allowed_values' => null,
                                                                                        'icon_class' => $field_child->attr('icon-class'),
                                                                                        'icon-name' => $field_child->attr('icon-name')
                                                                                    );
                                                                                    
                                                                                    $addon_children = $field_child->get();
                                                                                    
                                                                                    foreach($addon_children as $addon_child){
                                                                                        if ($addon_child instanceof \adapt\xml && $addon_child->tag == 'allowed_values'){
                                                                                            $values = array();
                                                                                            $allowed_children = $addon_child->get();
                                                                                            foreach($allowed_children as $allowed_child){
                                                                                                if ($allowed_child instanceof \adapt\xml && $allowed_child->tag == "value"){
                                                                                                    $label = $allowed_child->attr('label');
                                                                                                    $value = $allowed_child->get(0);
                                                                                                    
                                                                                                    if (is_null($label)){
                                                                                                        $values[] = $value;
                                                                                                    }else{
                                                                                                        $values[$value] = $label;
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                            
                                                                                            $addon['allowed_values'] = json_encode($values);
                                                                                        }
                                                                                    }
                                                                                    
                                                                                    $field['addons'][] = $addon;
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }
                                                                        
                                                                        $group['fields'][] = $field;
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                            
                                                            $section['groups'][] = $group;
                                                            break;
                                                        }
                                                    }
                                                }
                                                
                                                $page['sections'][] = $section;
                                                break;
                                            }
                                        }
                                    }
                                    
                                    $form['pages'][] = $page;
                                }
                            }
                            
                            $this->_forms[] = $form;
                            
                            //print "<pre>Forms: " . print_r($this->_forms, true) . "</pre>";
                        }
                    }
                }
            }
        }
        
    }
    
    
}

?>