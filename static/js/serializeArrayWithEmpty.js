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

                    // always eval checkboxes and radios
                    if(type === 'checkbox'){
                        return true;
                    }

                    return this.name && !jQuery( this ).is( ":disabled" ) &&
                        rsubmittable.test( this.nodeName ) && !rsubmitterTypes.test( type ) &&
                        ( this.checked || !rcheckableType.test( type ) );
                })
                .map(function( i, elem ) {

                    var currentType = this.type;
                    var val = jQuery( this ).val();

                    if(currentType === 'checkbox'){
                        if(jQuery(this).is(':checked')){
                            val = 'Yes';
                        } else {
                            val = 'No';
                        }
                    }

                    if(elem.name){
                        return { name: elem.name, value: val ? val : '' };
                    }

                }).get();
        }
    });

})(jQuery);