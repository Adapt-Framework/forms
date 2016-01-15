<?php

namespace adapt\forms{
        
    /*
     * Prevent direct access
     */
    defined('ADAPT_STARTED') or die;
    
    class controller_forms extends controller{
        
        public function __construct(){
            parent::__construct();
        }
        
        public function view_default(){
            $this->add_view('Hey there');
        }
        
        public function view_validators(){
            $this->content_type = 'text/javascript';
            
            $store = $this->store('adapt.sanitizer.data');
            
            $validators = array();
            
            if (isset($store['validators'])){
                foreach($store['validators'] as $name => $validator){
                    $validators[$name] = array(
                        'pattern' => $validator['pattern'],
                        'function' => $validator['js_function']
                    );
                }
            }
            
            $output = "var _forms_validators = " . json_encode($validators) . ";\n";
            
            $formatters = array();
            
            if (isset($store['formatters'])){
                foreach($store['formatters'] as $name => $formatter){
                    $formatters[$name] = array(
                        'pattern' => $formatter['pattern'],
                        'function' => $formatter['js_function']
                    );
                }
            }
            
            $output .= "var _forms_formatters = " . json_encode($formatters) . ";\n";
            
            $unformatters = array();
            
            if (isset($store['unformatters'])){
                foreach($store['unformatters'] as $name => $unformatter){
                    $unformatters[$name] = array(
                        'pattern' => $unformatter['pattern'],
                        'function' => $unformatter['js_function']
                    );
                }
            }
            
            $output .= "var _forms_unformatters = " . json_encode($unformatters) . ";\n";
            
            return $output;
        }
    }
}

?>