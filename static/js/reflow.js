(function($){
    
    $.fn.reflow = function(){
        
        if (this.attr('data-can-reflow') == 'Yes'){
            var items_per_row = this.attr('data-items-per-row');
            var $temp = $(this.find('.row').children().detach());
            
            this.find('.row').detach();
            
            this.append("<div class='row'></div>");
            var current_row = 0;
            for (var i = 0; i < $temp.size(); i++){
                var $item = $($temp.get(i));
                this.find('.row').last().append($item);
                if (!$item.hasClass('hidden')){
                    current_row = current_row + 1;
                }
                if (current_row == items_per_row){
                    this.append("<div class='row'></div>");
                    current_row = 0;
                }
                
                //this.find('.row:empty').detach();
            }
            
        }
        
        return this;
    };
    
})(jQuery);