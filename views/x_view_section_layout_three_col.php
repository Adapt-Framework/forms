<?php

namespace extensions\forms{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class view_section_layout_three_col extends view{
        
        //protected $_items = array();
        
        public function add($item){
            
            //$count = count($this->_items);
            //$mod = $count % 3;
            
            if ($item instanceof \frameworks\adapt\html){
                $item->add_class('col-xs-12 col-sm-4 column');
                //parent::add($item);
                //if ($mod == 0){
                //    $row = new html_div($item, array('class' => 'row'));
                    //parent::add($row);
                //}else{
                //    $this->find('.row')->last()->append($item);
                //}
                //print new html_pre($item->attr('class'));
                //if ($item->find('.group-layout-simple')->find('.hidden')->size() > 0){
                //    $item->add_class('hidden');
                //}else{
                //if (!$item->has_class('hidden')){
                    $this->_items[] = $item;
                //}
                //}
                
            }
            
            $row = $this->find('.row')->last();
            if ($row->size()){
                $row->append($item);
            }else{
                $row = new html_div($item, array('class' => 'row'));
                parent::add($row);
            }
            
            //parent::add($item);
            //$count = count($this->_items);
            //$mod = $count % 3;
            
            //if ($mod == 0){
            //    $this->find('.row')->last()->append(new html_div(array('class' => 'clearfix')));
            //}
            
        }
        
        public function render(){
            return parent::render();
            $items = $this->find('.column')->detach();
            $items = $items->get();
            for($i = 0; $i < count($items); $i++){
                //print new html_pre('i=' . $i);
                //$items[$i]->add(new html_p($i));
                //$items[$i]->add($i);
                if ($i % 3 == 0){
                    //print new html_pre('ix=' . $i);
                    if (!$i){
                        $row = new html_div($items[$i], array('class' => 'row'));
                        parent::add($row);
                    }else{
                        $row = $this->find('.row')->last();
                        if ($row->size()){
                            $row->append($items[$i]);
                        }
                        $row = new html_div(array('class' => 'row'));
                        parent::add($row);
                    }
                    //if ($i > 0){
                    //    $this->find('.row')->last()->append(new html_div(array('class' => 'clearfix')));
                    //}
                    
                    
                    //$row->add($items[$i]);
                }else{
                    $row = $this->find('.row')->last();
                    if ($row->size()){
                        $row->append($items[$i]);
                    }else{
                        $row = new html_div(array('class' => 'row'));
                        parent::add($row);
                        $row->add($items[$i]);
                    }
                }
            }
            return parent::render();
        }
        
    }
    
}

?>