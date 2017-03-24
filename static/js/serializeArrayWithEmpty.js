/**
 * A JQUERY extension that will return a full array based of the parent parsed
 *
 * This will also return blank strings for:
 * blank strings : nulls : undefined
 *
 */
(function($){
    var rcheckableType = (/^(?:checkbox|radio)$/i);
    var rsubmitterTypes = /^(?:submit|button|image|reset|file)$/i,
        rsubmittable = /^(?:input|select|textarea|keygen)/i;

    $.fn.extend({
        serializeArrayWithEmpty: function() {
            return this.map(function() {
                    var elements = jQuery.prop( this, "elements" );
                    return elements ? jQuery.makeArray( elements ) : this;
                })
                .filter(function() {
                    var type = this.type;

                    // Use .is( ":disabled" ) so that fieldset[disabled] works
                    return this.name && !jQuery( this ).is( ":disabled" ) &&
                        rsubmittable.test( this.nodeName ) && !rsubmitterTypes.test( type ) &&
                        ( this.checked || !rcheckableType.test( type ) );
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