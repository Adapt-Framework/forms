/**
 * A JQUERY extension that will return a full array based of the parent parsed
 *
 * This will also return blank strings for:
 * blank strings : nulls : undefined
 *
 */
(function($){

    $.fn.extend({
        serializeArrayWithEmpty: function() {
            return this.map(function() {
                    var elements = jQuery.prop( this, "elements" );
                    return elements ? jQuery.makeArray( elements ) : this;
                })
                .map(function( i, elem ) {
                    var val = jQuery( this ).val();

                    if(elem.name){
                        return { name: elem.name, value: val ? val : '' };
                    }

                }).get();
        }
    });

})(jQuery);