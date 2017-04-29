<?php

namespace adapt\forms{
    
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class view_field_static_textarea extends view_form_page_section_group_field{
        
        public function __construct($form_data, $data_type, &$user_data){
            parent::__construct($form_data, $data_type, $user_data);
            $this->add_class('field static-textarea');
            
            $unescaped_form_data = $form_data['description']; 
            
//            $unescaped_form_data = mb_ereg_replace("&amp;", "&", $unescaped_form_data, "g");
//            $unescaped_form_data = mb_ereg_replace("&apos;", "'", $unescaped_form_data, "g");
//            $unescaped_form_data = mb_ereg_replace("&lt;", "<", $unescaped_form_data, "g");
//            $unescaped_form_data = mb_ereg_replace("&gt;", ">", $unescaped_form_data, "g");
//            $unescaped_form_data = mb_ereg_replace("&quot;", "\"", $unescaped_form_data, "g");             
            
            $this->_add($unescaped_form_data, false);
           
        }
        
        public static function escape($string){
            return $string;
        }
        
        public static function unescape($string){
            return $string;
        }
        
    }
    
}

