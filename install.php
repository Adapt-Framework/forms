<?php

namespace frameworks\adapt;
/*
 * Prevent direct access
 */
defined('ADAPT_STARTED') or die;


$adapt = $GLOBALS['adapt'];
$sql = $adapt->data_source->sql;

//$adapt->data_source->on('adapt.error', function($e){
//    print "<h3>Data source error</h3>";
//    print "<pre>" . $e['event_data']['error'] . "</pre>";
//});

$sql->on('adapt.error', function($e){
    print "<h3>SQL error</h3>";
    print "<pre>" . $e['event_data']['error'] . "</pre>";
});

if ($sql && $sql instanceof \frameworks\adapt\sql){
    
    $sql->create_table('form')
        ->add('form_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('custom_view', 'varchar(256)')
        ->add('submission_url', 'varchar(256)')
        ->add('actions', 'varchar(256)')
        ->add('method', "enum('get', 'post', 'ajax')", false, 'post')
        ->add('name', 'varchar(128)')
        ->add('title', 'varchar(128)')
        ->add('description', 'text')
        //->add('style', "enum('Standard', 'Inline', 'Horizontal')", false, 'Standard')
        ->add('show_steps', "enum('Yes', 'No')", false, 'Yes')
        ->add('show_processing_page', "enum('Yes', 'No')", false, 'Yes')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_id')
        ->execute();
    
    $sql->create_table('form_page')
        ->add('form_page_id', 'bigint')
        ->add('form_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('priority', 'int')
        ->add('custom_view', 'varchar(256)')
        ->add('title', 'varchar(256)')
        ->add('description', 'text')
        ->add('step_title', 'varchar(12)')
        ->add('step_description', 'varchar(32)')
        ->add('step_custom_view', 'varchar(256)')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_id')
        ->foreign_key('form_id', 'form', 'form_id')
        ->execute();
    
    $sql->create_table('form_button_style')
        ->add('form_button_style_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('name', 'varchar(64)', false)
        ->add('classes', 'varchar(256)')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_button_style_id')
        ->execute();
        
    $styles = array(
        array(
            'bundle_name' => 'forms',
            'name' => 'Standard',
            'classes' => 'btn btn-default',
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Primary',
            'classes' => 'btn btn-primary',
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Success',
            'classes' => 'btn btn-success',
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Info',
            'classes' => 'btn btn-info',
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Warning',
            'classes' => 'btn btn-warning',
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Danger',
            'classes' => 'btn btn-danger',
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Link',
            'classes' => 'btn btn-link',
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        )
    );
    
    $sql->insert_into('form_button_style', array_keys($styles[0]));
    for($i = 0; $i < count($styles); $i++){
        $sql->values(array_values($styles[$i]));
    }
    $sql->execute();
    
    
    $sql->create_table('form_page_button')
        ->add('form_page_button_id', 'bigint')
        ->add('form_page_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('custom_view', 'varchar(256)')
        ->add('priority', 'int')
        ->add('form_button_style_id', 'bigint')
        ->add('label', 'varchar(64)')
        ->add('icon_name', 'varchar(256)')
        ->add('icon_class', 'varchar(256)')
        ->add('action', "enum('Submit', 'Reset', 'Next page', 'Previous page', 'Custom...')")
        ->add('custom_action', 'varchar(128)')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_button_id')
        ->foreign_key('form_page_id', 'form_page', 'form_page_id')
        ->foreign_key('form_button_style_id', 'form_button_style', 'form_button_style_id')
        ->execute();
    
    $sql->create_table('form_page_section_layout')
        ->add('form_page_section_layout_id', 'bigint')
        ->add('bundle_name', 'varchar(128)', false)
        ->add('custom_view', 'varchar(256)', false)
        ->add('name', 'varchar(128)')
        ->add('label', 'varchar(128)')
        ->add('description', 'text')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_section_layout_id')
        ->execute();
    
    /* Add the standard form layouts */
    $model = new model_form_page_section_layout();
    $model->bundle_name = 'forms';
    $model->custom_view = '\\extensions\\forms\\view_section_layout_standard';
    $model->name = 'standard';
    $model->label = 'Standard';
    $model->description = "Default layout for sections with one field per line.";
    $model->save();
    
    $model = new model_form_page_section_layout();
    $model->bundle_name = 'forms';
    $model->custom_view = '\\extensions\\forms\\view_section_layout_inline';
    $model->name = 'inline';
    $model->label = 'Inline';
    $model->description = "Makes a section inline and on one line.";
    $model->save();
    
    $model = new model_form_page_section_layout();
    $model->bundle_name = 'forms';
    $model->custom_view = '\\extensions\\forms\\view_section_layout_horizontal';
    $model->name = 'horizontal';
    $model->label = 'Horizontal';
    $model->description = "Lays out the section in the same way as bootstrap horizonal forms.";
    $model->save();
    
    $model = new model_form_page_section_layout();
    $model->bundle_name = 'forms';
    $model->custom_view = '\\extensions\\forms\\view_section_layout_two_col';
    $model->name = '2_col';
    $model->label = '2 Column layout';
    $model->description = "Uses Bootstrap grid to create two columns.";
    $model->save();
    
    $model = new model_form_page_section_layout();
    $model->bundle_name = 'forms';
    $model->custom_view = '\\extensions\\forms\\view_section_layout_three_col';
    $model->name = '3_col';
    $model->label = '3 Column layout';
    $model->description = "Uses Bootstrap grid to create three columns.";
    $model->save();
    
    $model = new model_form_page_section_layout();
    $model->bundle_name = 'forms';
    $model->custom_view = '\\extensions\\forms\\view_section_layout_four_col';
    $model->name = '4_col';
    $model->label = '4 Column layout';
    $model->description = "Uses Bootstrap grid to create four columns.";
    $model->save();
    
    
    $sql->create_table('form_page_section')
        ->add('form_page_section_id', 'bigint')
        ->add('form_page_id', 'bigint')
        ->add('form_page_section_layout_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('custom_view', 'varchar(256)')
        ->add('priority', 'int')
        ->add('repeatable', "enum('Yes', 'No')", false, 'No')
        ->add('min_occurances', 'int')
        ->add('max_occurances', 'int')
        ->add('occurs_until', 'varchar(256)') //Javascript condition (auto repeating without add button)
        ->add('title', 'varchar(256)')
        ->add('description', 'text')
        ->add('repeated_title', 'varchar(256)') //If omitted 'title' is used
        ->add('repeated_description', 'text')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_section_id')
        ->foreign_key('form_page_id', 'form_page', 'form_page_id')
        ->foreign_key('form_page_section_layout_id', 'form_page_section_layout', 'form_page_section_layout_id')
        ->execute();
    
    $sql->create_table('form_page_section_button')
        ->add('form_page_section_button_id', 'bigint')
        ->add('form_page_section_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('custom_view', 'varchar(256)')
        ->add('priority', 'int')
        ->add('form_button_style_id', 'bigint')
        ->add('label', 'varchar(64)')
        ->add('icon_name', 'varchar(256)')
        ->add('icon_class', 'varchar(256)')
        ->add('action', "enum('Add section', 'Remove section', 'Custom...')")
        ->add('custom_action', 'varchar(128)')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_section_button_id')
        ->foreign_key('form_page_section_id', 'form_page_section', 'form_page_section_id')
        ->foreign_key('form_button_style_id', 'form_button_style', 'form_button_style_id')
        ->execute();
    
    $sql->create_table('form_field_type')
        ->add('form_field_type_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('name', 'varchar(64)')
        ->add('view', 'varchar(256)')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_field_type_id')
        ->execute();
    
    $field_types = array(
        array(
            'bundle_name' => 'forms',
            'name' => 'Hidden',
            'view' => "\\extensions\\forms\\view_field_hidden",
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Static',
            'view' => "\\extensions\\forms\\view_field_static",
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Text',
            'view' => "\\extensions\\forms\\view_field_input",
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Password',
            'view' => "\\extensions\\forms\\view_field_password",
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Select',
            'view' => "\\extensions\\forms\\view_field_select",
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Checkbox',
            'view' => "\\extensions\\forms\\view_field_checkbox",
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Radio',
            'view' => "\\extensions\\forms\\view_field_radio",
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'Text area',
            'view' => "\\extensions\\forms\\view_field_textarea",
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        ),
        array(
            'bundle_name' => 'forms',
            'name' => 'File',
            'view' => "\\extensions\\forms\\view_field_file",
            'date_created' => new \frameworks\adapt\sql('now()'),
            'date_modified' => new \frameworks\adapt\sql('now()')
        )
    );
    
    $sql->insert_into('form_field_type', array_keys($field_types[0]));
    for($i = 0; $i < count($field_types); $i++){
        $sql->values(array_values($field_types[$i]));
    }
    $sql->execute();
    
    $sql->create_table('form_page_section_group_layout')
        ->add('form_page_section_group_layout_id', 'bigint')
        ->add('bundle_name', 'varchar(128)', false)
        ->add('custom_view', 'varchar(256)', false)
        ->add('name', 'varchar(128)')
        ->add('label', 'varchar(128)')
        ->add('description', 'text')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_section_group_layout_id')
        ->execute();
    
    $model = new model_form_page_section_group_layout();
    $model->bundle_name = 'forms';
    $model->custom_view = '\\extensions\\forms\\view_group_layout_simple';
    $model->name = 'simple';
    $model->label = 'Simple';
    $model->description = 'Simple layout allowing a single field';
    $model->save();
    
    /*$model = new model_form_page_section_group_layout();
    $model->bundle_name = 'forms';
    $model->custom_view = '\\extensions\\forms\\view_group_layout_multiple';
    $model->name = 'multiple';
    $model->label = 'Multiple fields';
    $model->description = 'Complex layout allowing multiple fields';
    $model->save();*/
    
    $model = new model_form_page_section_group_layout();
    $model->bundle_name = 'forms';
    $model->custom_view = '\\extensions\\forms\\view_group_layout_split';
    $model->name = 'split';
    $model->label = 'Split';
    $model->description = 'Simple layout with two fields side-by-side';
    $model->save();
    
    
    $sql->create_table('form_page_section_group')
        ->add('form_page_section_group_id', 'bigint')
        ->add('form_page_section_id', 'bigint')
        ->add('form_page_section_group_layout_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('custom_view', 'varchar(256)')
        ->add('priority', 'int')
        ->add('label', 'varchar(256)')
        ->add('description', 'text')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_section_group_id')
        ->foreign_key('form_page_section_id', 'form_page_section', 'form_page_section_id')
        ->foreign_key('form_page_section_group_layout_id', 'form_page_section_group_layout', 'form_page_section_group_layout_id')
        ->execute();
        
    
    $sql->create_table('form_page_section_group_field')
        ->add('form_page_section_group_field_id', 'bigint')
        ->add('form_page_section_group_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('custom_view', 'varchar(256)')
        ->add('priority', 'int')
        ->add('form_field_type_id', 'bigint')
        ->add('name', 'varchar(256)')
        ->add('description', 'text')
        ->add('data_type_id', 'bigint')
        ->add('label', 'varchar(256)')
        //->add('icon_view', 'varchar(256)')
        //->add('icon_class', 'varchar(256)')
        ->add('placeholder_label', 'varchar(256)')
        ->add('default_value', 'text')
        ->add('lookup_table', 'varchar(64)')
        ->add('allowed_values', 'text')
        ->add('max_length', 'int')
        ->add('mandatory', "enum('Yes', 'No')", false, 'No')
        ->add('mandatory_group', 'varchar(32)')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_section_group_field_id')
        ->foreign_key('form_page_section_group_id', 'form_page_section_group', 'form_page_section_group_id')
        ->foreign_key('form_field_type_id', 'form_field_type', 'form_field_type_id')
        ->foreign_key('data_type_id', 'data_type', 'data_type_id')
        ->execute();
        
    $sql->create_table('form_page_section_group_field_addon')
        ->add('form_page_section_group_field_addon_id', 'bigint')
        ->add('form_page_section_group_field_id', 'bigint')
        ->add('type', "enum('Text', 'Button', 'Icon', 'Radio', 'Checkbox', 'Select')", false, 'Text')
        ->add('position', "enum('Before', 'After')", false, 'Before')
        ->add('priority', 'int')
        ->add('name', 'varchar(256)') //Field name (Radio, Checkbox, Select)
        ->add('default_value', 'varchar(64)') //Radio, checkbox, select
        ->add('allowed_values', 'text') //Radio, checkbox, select
        ->add('lookup_table', 'varchar(64)') //Select
        ->add('label', 'varchar(16)') //Text, Button
        ->add('icon_name', 'varchar(128)') //Icon, Button
        ->add('icon_class', 'varchar(256)') //Icon, Button
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_section_group_field_addon_id')
        ->foreign_key('form_page_section_group_field_id', 'form_page_section_group_field', 'form_page_section_group_field_id')
        ->execute();
    
    
    
    $sql->create_table('form_page_section_group_button')
        ->add('form_page_section_group_button_id', 'bigint')
        ->add('form_page_section_group_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('custom_view', 'varchar(256)')
        ->add('priority', 'int')
        ->add('form_button_style_id', 'bigint')
        ->add('label', 'varchar(64)')
        ->add('icon_name', 'varchar(256)')
        ->add('icon_class', 'varchar(256)')
        ->add('action', "enum('Custom...')")
        ->add('custom_action', 'varchar(128)')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_section_group_button_id')
        ->foreign_key('form_page_section_group_id', 'form_page_section_group', 'form_page_section_group_id')
        ->foreign_key('form_button_style_id', 'form_button_style', 'form_button_style_id')
        ->execute();
    
    $sql->create_table('form_page_condition')
        ->add('form_page_condition_id', 'bigint')
        ->add('form_page_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('depends_on_form_page_section_group_field_id', 'bigint')
        ->add('operator', "enum('Equal to', 'Less than', 'Less than or equal to', 'Greater than', 'Greater than or equal to', 'One of', 'Javascript function')", false, 'Equal to')
        ->add('value', 'text')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_condition_id')
        ->foreign_key('form_page_id', 'form_page', 'form_page_id')
        ->foreign_key('depends_on_form_page_section_group_field_id', 'form_page_section_group_field', 'form_page_section_group_field_id')
        ->execute();
        
    $sql->create_table('form_page_section_condition')
        ->add('form_page_section_condition_id', 'bigint')
        ->add('form_page_section_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('depends_on_form_page_section_group_field_id', 'bigint')
        ->add('operator', "enum('Equal to', 'Less than', 'Less than or equal to', 'Greater than', 'Greater than or equal to', 'One of', 'Javascript function')", false, 'Equal to')
        ->add('value', 'text')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_section_condition_id')
        ->foreign_key('form_page_section_id', 'form_page_section', 'form_page_section_id')
        ->foreign_key('depends_on_form_page_section_group_field_id', 'form_page_section_group_field', 'form_page_section_group_field_id')
        ->execute();
    
    $sql->create_table('form_page_section_group_condition')
        ->add('form_page_section_group_condition_id', 'bigint')
        ->add('form_page_section_group_id', 'bigint')
        ->add('bundle_name', 'varchar(128)')
        ->add('depends_on_form_page_section_group_field_id', 'bigint')
        ->add('operator', "enum('Equal to', 'Less than', 'Less than or equal to', 'Greater than', 'Greater than or equal to', 'One of', 'Javascript function')", false, 'Equal to')
        ->add('value', 'text')
        ->add('date_created', 'datetime')
        ->add('date_modified', 'timestamp')
        ->add('date_deleted', 'datetime')
        ->primary_key('form_page_section_group_condition_id')
        ->foreign_key('form_page_section_group_id', 'form_page_section_group', 'form_page_section_group_id')
        ->foreign_key('depends_on_form_page_section_group_field_id', 'form_page_section_group_field', 'form_page_section_group_field_id')
        ->execute();
    
    
}

?>