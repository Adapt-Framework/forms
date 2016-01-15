<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_form extends view{
        
        protected $_form;
        protected $_steps;
        //protected $_page_count = 0;
        
        public function __construct($form_data = array(), $user_data = array()){
            parent::__construct('form');
            
            ///* Add the attributes */
            $this->attr('action', $form_data['form']['submission_url']);
            $this->attr('method', $form_data['form']['method']);
            $this->attr('data-form-id', $form_data['form']['form_id']);
            $this->set_id();
            
            //$this->add(new html_pre(print_r($form_data, true)));
            
            /* Add the actions */
            parent::add(new html_input(array('type' => 'hidden', 'name' => 'actions', 'value' => $form_data['form']['actions'])));
            
            ///* Add the current url */
            list($path, $params) = explode('?', $_SERVER['REQUEST_URI'], 2);
            parent::add(new html_input(array('type' => 'hidden', 'name' => 'current_url', 'value' => $path)));
            //
            /* Add the title if required */
            if (isset($form_data['form']['title']) && trim($form_data['form']['title']) != ""){
                parent::add(new html_h1($form_data['form']['title'], array('class' => 'title')));
            }
            
            /* Add a description if required */
            if (isset($form_data['form']['description']) && trim($form_data['form']['description']) != ""){
                parent::add(new html_p($form_data['form']['description'], array('class' => 'lead')));
            }
            //
            /* Add the steps if required */
            if (isset($form_data['form']['show_steps']) && strtolower($form_data['form']['show_steps']) == "yes"){
                parent::add(new html_div(array('class' => 'steps')));
            }
            
            /* Add the processing screen if required */
            if (isset($form_data['form']['show_processing_page']) && strtolower($form_data['form']['show_processing_page']) == "yes"){
               parent::add(new html_div(new html_span(array('class' => 'fa fa-circle-o-notch fa-spin fa-5x')), array('class' => 'processing text-center hidden')));
            }
            
            /* Hold section layout engines */
            $section_layout_engines = array();
            
            /* Hold group layout engines */
            $group_layout_engines = array();
            
            /* Load button styles */
            $button_styles = $this->data_source->sql
                ->select('*')
                ->from('form_button_style')
                ->where(
                    new \frameworks\adapt\sql_condition(
                        new \frameworks\adapt\sql('date_deleted'),
                        'is',
                        new \frameworks\adapt\sql('null')
                    )
                )
                ->execute(60 * 60 * 12)
                ->results();
            
            /* Load section layouts */
            $section_layouts = $this->data_source->sql
                ->select('*')
                ->from('form_page_section_layout')
                ->where(
                    new \frameworks\adapt\sql_condition(
                        new \frameworks\adapt\sql('date_deleted'),
                        'is',
                        new \frameworks\adapt\sql('null')
                    )
                )
                ->execute(60 * 60 * 12)
                ->results();
                
            /* Load group layouts */
            $group_layouts = $this->data_source->sql
                ->select('*')
                ->from('form_page_section_group_layout')
                ->where(
                    new \frameworks\adapt\sql_condition(
                        new \frameworks\adapt\sql('date_deleted'),
                        'is',
                        new \frameworks\adapt\sql('null')
                    )
                )
                ->execute(60 * 60 * 12)
                ->results();
                
            $field_types = $this->data_source->sql
                ->select('*')
                ->from('form_field_type')
                ->where(
                    new \frameworks\adapt\sql_condition(
                        new \frameworks\adapt\sql('date_deleted'),
                        'is',
                        new \frameworks\adapt\sql('null')
                    )
                )
                ->execute(60 * 60 * 12)
                ->results();
            
            //$this->add(new html_pre(print_r($field_types, true)));
            
            /* Lets add the pages */
            foreach($form_data['form_page'] as $page){
                $div = new html_div(array('class' => 'form-page', 'data-form-page-id' => $page['form_page_id']));
                $this->add($div);
                
                /* Add the step */
                if (strtolower($form_data['form']['show_steps']) == 'yes'){
                    if (isset($page['step_custom_view']) && trim($page['step_custom_view']) != ""){
                        $class = $page['step_custom_view'];
                        if (class_exists($class)){
                            $view = new $class($page['step_title'], $page['step_description']);
                            $this->find('.steps')->append($view);
                        }else{
                            $view = new view_form_step($page['step_title'], $page['step_description']);
                            $this->find('.steps')->append($view);
                        }
                    }else{
                        $view = new view_form_step($page['step_title'], $page['step_description']);
                        $this->find('.steps')->append($view);
                    }
                }
                
                /* Add the title */
                if (isset($page['title']) && trim($page['title']) != ""){
                    $div->add(new html_h2($page['title'], array('class' => 'title')));
                }
                
                /* Add the description */
                if (isset($page['description']) && trim($page['description']) != ""){
                    $div->add(new html_p($page['description'], array('class' => 'description')));
                }
                
                /* Add a container for children */
                $div->add(new html_div(array('class' => 'container-fluid')));
                
                //parent::add(new html_pre(print_r($page, true)));
            }
            
            /* Lets add the button pages */
            foreach($form_data['form_page_button'] as $button){
                $page = $this->find("[data-form-page-id='{$button['form_page_id']}']");
                if ($page && $page->size()){
                    if ($page->find('.controls')->size() == 0){
                        $page->append(new html_div(array('class' => 'controls')));
                    }
                    
                    $controls = $page->find('.controls');
                    
                    $view = null;
                    $label = array();
                    
                    if (isset($button['icon_name']) && isset($button['icon_class'])
                        && trim($button['icon_name']) != "" && trim($button['icon_class']) != ""){
                        $class = $button['icon_class'];
                        if (class_exists($class)){
                            $label[] = new $class($button['icon_name']);
                        }
                    }
                    
                    if (isset($button['label']) && trim($button['label']) != ""){
                        $label[] = $button['label'];
                    }
                    
                    if (isset($button['custom_view']) && trim($button['custom_view']) != ""){
                        $class = $button['custom_view'];
                        if (class_exists($button)){
                            $view = new $class($label);
                        }else{
                            $view = new html_button($label);
                            foreach($button_styles as $style){
                                if ($style['form_button_style_id'] == $button['form_button_style_id']){
                                    $view->add_class($style['classes']);
                                }
                            }
                        }
                    }else{
                        $view = new html_button($label);
                        foreach($button_styles as $style){
                            if ($style['form_button_style_id'] == $button['form_button_style_id']){
                                $view->add_class($style['classes']);
                            }
                        }
                    }
                    
                    $view->add_class('control');
                    
                    switch($button['action']){
                    case "Submit":
                    case "Next page":
                        $view->add_class('next');
                        break;
                    case "Previous page":
                        $view->add_class('previous');
                        break;
                    case "Reset":
                        $view->add_class('reset');
                        break;
                    case "Custom...":
                        $view->attr('onclick', $button['custom_action']);
                        break;
                    }
                    
                    $controls->append($view);
                }
            }
            
            /* Lets add conditions to the page */
            foreach($form_data['form_page_conditions'] as $condition){
                $page = $this->find("[data-form-page-id='{$condition['form_page_id']}']");
                if ($page && $page->size()){
                    
                    $cond = new html_div(array(
                        'class' => 'condition hidden',
                        'data-form-page-condition-id' => $condition['form_page_condition_id'],
                        'data-target-form-page-section-group-field-id' => $condition['depends_on_form_page_section_group_field_id'],
                        'data-operator' => $condition['operator'],
                        'data-value' => $condition['value']
                    ));
                    $page->prepend($cond);
                }
            }
            
            
            /* Lets add sections */
            foreach($form_data['form_page_section'] as $section){
                $page = $this->find("[data-form-page-id='{$section['form_page_id']}']");
                if ($page && $page->size()){
                    $view = null;
                    
                    if (isset($section['custom_view']) && trim($section['custom_view']) == ""){
                        $class = $section['custom_view'];
                        if (class_exists($class)){
                            $view = new $class(array('class' => 'form-page-section', 'data-form-page-section-id' => $section['form_page_section_id']));
                        }
                    }
                    
                    if (is_null($view)) $view = new html_div(array('class' => 'form-page-section', 'data-form-page-section-id' => $section['form_page_section_id']));
                    
                    /* Get the layout engine */
                    $layout_engine = null;
                    foreach($section_layouts as $section_layout){
                        if ($section_layout['form_page_section_layout_id'] == $section['form_page_section_layout_id']){
                            $view->attr('data-form-page-section-layout-id', $section['form_page_section_layout_id']);
                            $class = $section_layout['custom_view'];
                            if (class_exists($class)){
                                $layout_engine = new $class();
                                break;
                            }
                        }
                    }
                    
                    if ($layout_engine && is_object($layout_engine)){
                        $section_layout_engines['section-' . $section['form_page_section_id']] = $layout_engine;
                    }
                    
                    /* Handle repeat sections */
                    if ($section['repeatable'] == 'Yes'){
                        $view->attr('data-repeatable', 'Yes');
                        $view->attr('data-min-occurances', $section['min_occurances']);
                        $view->attr('data-max-occurances', $section['max_occurances']);
                        $view->attr('data-occurs-until', $section['occurs_until']);
                        $view->attr('data-repeated-title', $section['repeated_title']);
                        $view->attr('data-repeated-description', $section['repeated_description']);
                    }
                    
                    if (isset($section['title']) && trim($section['title']) != ""){
                        $view->add(new html_h3($section['title'], array('class' => 'title')));
                    }
                    
                    if (isset($section['description']) && trim($section['description']) != ""){
                        $view->add(new html_p($section['description'], array('class' => 'description')));
                    }
                    
                    $view->add($layout_engine);
                    
                    $page->find('.container')->append($view);
                }
            }
            
            /* Add section buttons */
            foreach($form_data['form_page_section_button'] as $button){
                $section = $this->find("[data-form-page-section-id='{$button['form_page_section_id']}']");
                if ($section && $section->size()){
                    if ($section->find('.controls')->size() == 0){
                        $section->append(new html_div(array('class' => 'controls')));
                    }
                    
                    $controls = $section->find('.controls');
                    
                    $view = null;
                    $label = array();
                    
                    if (isset($button['icon_name']) && isset($button['icon_class'])
                        && trim($button['icon_name']) != "" && trim($button['icon_class']) != ""){
                        $class = $button['icon_class'];
                        if (class_exists($class)){
                            $label[] = new $class($button['icon_name']);
                        }
                    }
                    
                    if (isset($button['label']) && trim($button['label']) != ""){
                        $label[] = $button['label'];
                    }
                    
                    if (isset($button['custom_view']) && trim($button['custom_view']) != ""){
                        $class = $button['custom_view'];
                        if (class_exists($button)){
                            $view = new $class($label);
                        }else{
                            $view = new html_button($label);
                            foreach($button_styles as $style){
                                if ($style['form_button_style_id'] == $button['form_button_style_id']){
                                    $view->add_class($style['classes']);
                                }
                            }
                        }
                    }else{
                        $view = new html_button($label);
                        foreach($button_styles as $style){
                            if ($style['form_button_style_id'] == $button['form_button_style_id']){
                                $view->add_class($style['classes']);
                            }
                        }
                    }
                    
                    $view->add_class('control');
                    
                    switch($button['action']){
                    case "Add section":
                        $view->add_class('add');
                        break;
                    case "Remove section":
                        $view->add_class('remove');
                        break;
                    case "Custom...":
                        $view->attr('onclick', $button['custom_action']);
                        break;
                    }
                    
                    $controls->append($view);
                }
            }
            
            /* Lets add conditions to the section */
            foreach($form_data['form_page_section_conditions'] as $condition){
                $section = $this->find("[data-form-page-section-id='{$condition['form_page_section_id']}']");
                if ($section && $section->size()){
                    
                    $cond = new html_div(array(
                        'class' => 'condition hidden',
                        'data-form-page-section-condition-id' => $condition['form_page_condition_id'],
                        'data-target-form-page-section-group-field-id' => $condition['depends_on_form_page_section_group_field_id'],
                        'data-operator' => $condition['operator'],
                        'data-value' => $condition['value']
                    ));
                    $section->prepend($cond);
                }
            }
            
            
            /* Lets create the groups */
            $temp_group_container = new html_div(); //To hold them during build so we can add them to the section complete.
            
            foreach($form_data['form_page_section_group'] as $group){
                $view = new html_div(array('class' => 'form-page-section-group', 'data-form-page-section-group-id' => $group['form_page_section_group_id']));
                
                /* Add the section id so we have a reference to append with later */
                $view->attr('data-form-page-section-id', $group['form_page_section_id']);
                
                /* Add the view to the temp container */
                $temp_group_container->add($view);
                
                /* Get the layout engine */
                $layout_engine = null;
                foreach($group_layouts as $group_layout){
                    if ($group_layout['form_page_section_group_layout_id'] == $group['form_page_section_group_layout_id']){
                        $view->attr('data-form-page-section-group-layout-id', $group['form_page_section_group_layout_id']);
                        $class = $group_layout['custom_view'];
                        if (class_exists($class)){
                            $layout_engine = new $class();
                            break;
                        }
                    }
                }
                
                if ($layout_engine && is_object($layout_engine)){
                    $group_layout_engines['group-' . $group['form_page_section_group_id']] = $layout_engine;
                }
                
                $view->attr('data-form-page-section-group-layout-id', $group['form_page_section_group_layout_id']);
                
                if (isset($group['label']) && trim($group['label']) != ""){
                    $view->add(new html_label($group['label'], array('class' => 'label')));
                }
                
                if (isset($group['description']) && trim($group['description']) != ""){
                    $view->add(new html_p($group['description'], array('class' => 'description')));
                }
                
                $view->add($layout_engine);
            }
            
            
            /* Lets add groups to the sections */
            //foreach($form_data['form_page_section_group'] as $group){
            //    $section = $section_layout_engines['section-' . $group['form_page_section_id']];
            //    if ($section){
            //        
            //        $view = new html_div(array('class' => 'form-page-section-group', 'data-form-page-section-group-id' => $group['form_page_section_group_id']));
            //        
            //        /* Get the layout engine */
            //        $layout_engine = null;
            //        foreach($group_layouts as $group_layout){
            //            if ($group_layout['form_page_section_group_layout_id'] == $group['form_page_section_group_layout_id']){
            //                $view->attr('data-form-page-section-group-layout-id', $group['form_page_section_group_layout_id']);
            //                $class = $group_layout['custom_view'];
            //                if (class_exists($class)){
            //                    $layout_engine = new $class();
            //                    break;
            //                }
            //            }
            //        }
            //        
            //        if ($layout_engine && is_object($layout_engine)){
            //            $group_layout_engines['group-' . $group['form_page_section_group_id']] = $layout_engine;
            //        }
            //        
            //        $view->attr('data-form-page-section-group-layout-id', $group['form_page_section_group_layout_id']);
            //        
            //        if (isset($group['label']) && trim($group['label']) != ""){
            //            $view->add(new html_label($group['label'], array('class' => 'label')));
            //        }
            //        
            //        if (isset($group['description']) && trim($group['description']) != ""){
            //            $view->add(new html_p($group['description'], array('class' => 'description')));
            //        }
            //        
            //        $view->add($layout_engine);
            //        
            //        $section->add($view);
            //    }
            //}
            
            /* Add group buttons */
            foreach($form_data['form_page_section_group_button'] as $button){
                $group = $temp_group_container->find("[data-form-page-section-group-id='{$button['form_page_section_group_id']}']");
                if ($group && $group->size()){
                    if ($group->find('.controls')->size() == 0){
                        $sgroup->append(new html_div(array('class' => 'controls')));
                    }
                    
                    $controls = $group->find('.controls');
                    
                    $view = null;
                    $label = array();
                    
                    if (isset($button['icon_name']) && isset($button['icon_class'])
                        && trim($button['icon_name']) != "" && trim($button['icon_class']) != ""){
                        $class = $button['icon_class'];
                        if (class_exists($class)){
                            $label[] = new $class($button['icon_name']);
                        }
                    }
                    
                    if (isset($button['label']) && trim($button['label']) != ""){
                        $label[] = $button['label'];
                    }
                    
                    if (isset($button['custom_view']) && trim($button['custom_view']) != ""){
                        $class = $button['custom_view'];
                        if (class_exists($button)){
                            $view = new $class($label);
                        }else{
                            $view = new html_button($label);
                            foreach($button_styles as $style){
                                if ($style['form_button_style_id'] == $button['form_button_style_id']){
                                    $view->add_class($style['classes']);
                                }
                            }
                        }
                    }else{
                        $view = new html_button($label);
                        foreach($button_styles as $style){
                            if ($style['form_button_style_id'] == $button['form_button_style_id']){
                                $view->add_class($style['classes']);
                            }
                        }
                    }
                    
                    $view->add_class('control');
                    
                    switch($button['action']){
                    case "Custom...":
                        $view->attr('onclick', $button['custom_action']);
                        break;
                    }
                    
                    $controls->append($view);
                }
            }
            
            
            /* Lets add conditions to the group */
            foreach($form_data['form_page_section_group_conditions'] as $condition){
                $group = $this->find("[data-form-page-section-group-id='{$condition['form_page_section_group_id']}']");
                if ($group && $group->size()){
                    
                    $cond = new html_div(array(
                        'class' => 'condition hidden',
                        'data-form-page-section-group-condition-id' => $condition['form_page_condition_id'],
                        'data-target-form-page-section-group-field-id' => $condition['depends_on_form_page_section_group_field_id'],
                        'data-operator' => $condition['operator'],
                        'data-value' => $condition['value']
                    ));
                    $group->prepend($cond);
                }
            }
            
            
            /* Lets add the fields to the groups */
            foreach($form_data['form_page_section_group_field'] as $field){
                $group = $group_layout_engines['group-' . $field['form_page_section_group_id']];
                
                if ($group){
                    /* Convert allowed values */
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
                                        new \frameworks\adapt\sql_condition(
                                            $this->data_source->sql('date_deleted'),
                                            'is',
                                            $this->data_source->sql('null')
                                        )
                                    );
                                }
                                
                                if ($label_field == 'label'){
                                    $sql->order_by('label');
                                }
                                
                                $field['allowed_values'] = \frameworks\adapt\view_select::sql_result_to_assoc($sql->execute()->results());
                            }
                            
                            
                            
                            //$group->add(new html_pre(print_r($struct, true)));
                        }
                    }
                    
                    /* Do we have a custom view? */
                    $view = null;
                    $field_type = null;
                    
                    if ($field['custom_view'] && trim($field['custom_view']) != ""){
                        $class = $field['custom_view'];
                        if (class_exists($class)){
                            $view = new $class($field, $user_data);
                        }
                    }
                    
                    if (is_null($view)){
                        foreach($field_types as $type){
                            if ($type['form_field_type_id'] == $field['form_field_type_id']){
                                $field_type = $type;
                                //$group->add(new html_pre("Field type: " . print_r($field_type, true)));
                                if (class_exists($type['view'])){
                                    
                                    $class = $type['view'];
                                    $view = new $class($field, $user_data);
                                    
                                }
                                break;
                            }
                        }
                    }
                    
                    if ($view){
                        if ($field_type['name'] == 'Hidden'){
                            //$group->add(new html_pre(print_r($field_type, true)));
                            //$group->add_class('hidden');
                            //$a = new \frameworks\adapt\aquery($group);
                            //$a->parent()->add_class('hidden');
                        }
                        $group->add($view);
                    }
                    
                    
                    //$group->add(new html_pre(print_r($field, true)));
                }
                
                
            }
            
            /* Lets add the field addons */
            foreach($form_data['form_page_section_group_field_addon'] as $addon){
                $group = $this->find("[data-form-page-section-group-field-id='{$addon['form_page_section_group_field_id']}']");
                print new html_pre(print_r($addon, true));
            }
            
        }
        
        
        /*public function add($items){
            $new_id = 'page-' . ($this->_page_count + 1);
            
            if (is_object($items) && $items instanceof \frameworks\adapt\html){
                //if ($items->has_class('form-page')){
                //    $items->set_id($new_id);
                //    $this->_page_count++;
                //}
                if ($items->attr('data-step-label') && $items->attr('data-step-description')){
                    $step = new view_form_step($items->attr('data-step-label'), $items->attr('data-step-description'));
                    $step->attr('data-form-page-id', $items->attr('id'));
                    
                    if ($this->find('.steps')->children()->size() == 0){
                        $step->add_class('selected');
                    }else{
                        $items->add_class('hidden');
                    }
                    $this->find('.steps')->append($step);
                }
            }
            parent::add($items);
        }*/
        
    }
    
}

?>