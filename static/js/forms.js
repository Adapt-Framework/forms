(function($){

    $.getScript("/adapt/bootstrap/bootstrap-3.3.2/static/js/bootstrap.min.js", function(){

        /*
         * NOTES
         * I need to clean up the selectors so they only deal with this form.
         * FIXED: Needs to be tested with multiple forms
         *
         * Also using /page-X with pop state means only one form can use
         * multiple pages on a page, because if there were two forms,
         * the pop state will change both of them :/
         * FIXED: Changed to use form-<ID>-page-<NO> Must be tested
         *
         * Need to implement hash selectors for browsers that don't
         * support popstate
         *
         */



        update_dependencies = function(){
            $('.condition').each(
                function(){
                    var $this = $(this);
                    var $conditions = $this.parent();
                    var $item = $conditions.parent();
                    var field_id = $this.attr('data-target-form-page-section-group-field-id');
                    var operator = $this.attr('data-operator');
                    var values = $this.attr('data-value');

                    operator = operator.trim();

                    var $field = $item.parents('form').find("[data-form-page-section-group-field-id='" + field_id + "'] .form-control");
                    var can_display = false;

                    //console.log($item);
                    console.log(field_id);
                    console.log($field.val());

                    switch(operator){
                        case "=":
                            if ($field.val() == values) {
                                can_display = true;
                            }
                            break;
                        case "<":
                            if ($field.val() < values) {
                                can_display = true;
                            }
                            break;
                        case "<=":
                            if ($field.val() <= values) {
                                can_display = true;
                            }
                            break;
                        case ">":
                            if ($field.val() > values) {
                                can_display = true;
                            }
                            break;
                        case ">=":
                            if ($field.val() >= values) {
                                can_display = true;
                            }
                            break;
                        case "in":
                            values = eval(values);
                            for(var i = 0; i < values.length; i++){
                                if ($field.val() == values[i]) {
                                    can_display = true;
                                }
                            }
                            break;
                        case "function":
                            var func = eval(values);
                            can_display = func();
                            break;
                    }

                    if (can_display == true) {
                        //$item.show();
                        $item.removeClass('out-of-scope');
                        if ($item.hasClass('hidden')){
                            $item.removeClass('hidden');
                            $item.parents('.form-page-section-layout').reflow();
                        }
                    }else{
                        //$item.hide();
                        $item.addClass('out-of-scope');
                        if (!$item.hasClass('hidden')){
                            $item.addClass('hidden');
                            $item.parents('.form-page-section-layout').reflow();
                        }
                    }
                }
            );
        };

        $(document).ready(function(){
            /*
             * Initialise tooltips for inline forms
             */

            $('.form-control[data-toggle="tooltip"]').tooltip();

            /*
             * Initialise downdown selects
             */
            $('.view.dropdown-select .dropdown-menu a').on(
                'click',
                function (event){
                    var value = $(this).parent().attr('data-value');
                    var label = $(this).text();

                    $(this).parents('.view.dropdown-select').find('.selected-label').empty().append(label + ' ');
                    $(this).parents('.view.dropdown-select').find('.selected-value').val(value);
                }
            );


            /*
             * Validate form and process dependencies (initilisation - also whenever an ajax update occurs)
             */
            //TODO: ^^^

            /*
             * Handle form dependencies
             */
            update_dependencies();


            /*
             * Handle back and forward browser buttons
             */
            if (window.history.pushState && 1 === 2) {

                /* PopState is supported :) */
                $(window).on(
                    'popstate',
                    function(event){
                        var pattern = /form-([0-9]+)-page-([0-9]+)$/;
                        var matches = pattern.exec(document.URL);
                        if (matches) {
                            console.log(matches);
                            var form_id = matches[1];
                            var page_number = matches[2];

                            $('.forms.view.form[data-form-id="' + form_id + '"] .steps .view.form-step').removeClass('selected').removeClass('complete').removeClass('error');
                            $('.forms.view.form[data-form-id="' + form_id + '"] .view.form-page').addClass('hidden');

                            for(var i = 0; i < page_number; i++){
                                if (i == page_number - 1){
                                    $($('.forms.view.form[data-form-id="' + form_id + '"] .view.form-page').get(i)).removeClass('hidden');
                                    $($('.forms.view.form[data-form-id="' + form_id + '"] .steps .view.form-step').get(i)).addClass('selected');
                                }else{
                                    $($('.forms.view.form[data-form-id="' + form_id + '"] .steps .view.form-step').get(i)).addClass('complete');
                                }
                            }
                        }else{
                            /* Reset the form to page 1 */
                            //$('.forms.view.form[data-form_id="' + form_id + '"] .steps .view.form-step').removeClass('selected').removeClass('complete').removeClass('error');
                            //$('.forms.view.form[data-form_id="' + form_id + '"] .steps .view.form-step').first().addClass('selected');
                            //$('.forms.view.form[data-form_id="' + form_id + '"] .view.form-page').addClass('hidden').first().removeClass('hidden');
                            $('.forms.view.form .steps .view.form-step').removeClass('selected').removeClass('complete').removeClass('error');
                            $('.forms.view.form .steps .view.form-step').first().addClass('selected');
                            $('.forms.view.form .view.form-page').addClass('hidden').first().removeClass('hidden');
                        }
                    }
                )
            }

            /*
             * Add events to the previous button
             */
            $(document).on(
                'click',
                'button.control.previous',
                function(event){
                    var $this = $(this);
                    var $page = $this.parents('.view.form-page');
                    var $previous_page = $page.prev('.view.form-page');

                    if ($previous_page.length){
                        $page.addClass('hidden');
                        $previous_page.removeClass('hidden');
                        $page.parents('.forms .view.form').find('.steps .view.form-step.selected').removeClass('selected').prev('.view.form-step').removeClass('.complete').addClass('selected');

                        /* We need to push the state into the history */
                        $page = $previous_page;
                        var $pages = $page.parents('.forms.view.form').find('.view.form-page');
                        var form_id = $page.parents('.forms.view.form').attr('data-form-id');
                        var page_number = 0;
                        var path = $page.parents('.forms.view.form').find('input[name="current_url"]').val();

                        for(var i = 0; i < $pages.length; i++){
                            if ($($pages.get(i)).attr('id') == $page.attr('id')){
                                page_number = i + 1;
                            }
                        }
                        var data = {id: $page.attr('id')};

                        var url = path;
                        if (page_number >= 2) {
                            url = url + '/form-' + form_id + '-page-' + page_number;
                        }



                        // window.history.pushState(data, 'Page ' + page_number, url);
                    }

                    return false;
                }
            );

            /*
             * Add events to the next button
             */
            $(document).on(
                'click',
                'button.control.next',
                function(event){
                    var $this = $(this);
                    var $page = $this.parents('.forms.view.form-page');

                    /*
                     * Remove any error messages
                     */
                    $page.find('.error-panel').empty();

                    /*
                     * Check mandatory fields, including groups
                     */
                    $page.parents('.forms.view.form').find('[data-mandatory="Yes"]:visible').each(
                        function(){
                            var $this = $(this);

                            if ($this.parents('.form-group').length) {
                                if ($this.val() == '' || $this.val() == '__NOT_SET__'){
                                    $this.parents('.form-group').addClass('has-error').addClass('has-feedback').find('input[type="text"]').after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                                }
                            }else if ($this.hasClass('field-radio') || $this.hasClass('field-checkbox')){
                                if ($this.find('input:checked').length == 0) {
                                    $this.addClass('has-error');
                                }
                            }

                        }
                    );

                    $page.parents('.forms.view.form').find('[data-mandatory="Group"]:visible').each(
                        function(){
                            var $this = $(this);
                            var group = $this.attr('data-mandatory-group');
                            var $group_members = $('.forms.view.form [data-mandatory-group="' + group + '"]:visible');
                            var valid = false;

                            for(var i = 0; i < $group_members.length; i++){
                                var $item = $($group_members.get(i));

                                if ($item.parents('.form-group').length){
                                    if ($item.val() != '' && $item.val() != '__NOT_SET__'){
                                        valid = true;
                                    }
                                }else if ($this.hasClass('field-radio') || $this.hasClass('field-checkbox')){
                                    if ($this.find('input:checked').length > 0){
                                        $valid = true;
                                    }
                                }

                                //if ($item.val() != '' && $item.val() != '__NOT_SET__') {
                                //    /*
                                //     * We don't need to care if the value is valid or
                                //     * not because the field will already be high-lighted
                                //     * as errored, and because only one in a group is required
                                //     * and the user has attempted to complete one, we are going
                                //     * to consider this as valid.
                                //     */
                                //    valid = true;
                                //}
                            }

                            if (valid == false) {
                                $group_members.parents('.form-group').addClass('has-error').addClass('has-feedback').find('input[type="text"]').after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                            }
                        }
                    );

                    /*
                     * Lets check if any fields have errors
                     */
                    var $error_fields = $('.forms.view.form .has-error');

                    if ($error_fields.length > 0) {
                        $page.parents('.forms.view.form').find('.steps .selected').removeClass('selected').addClass('error');

                        for(var i = 0; i < $error_fields.length; i++){
                            $field = $($error_fields.get(i)).find('.form-control');
                            if ($field.length == 0) {
                                $field = $($error_fields.get(i));
                            }

                            ////

                            if (
                                $field.attr('data-mandatory') == 'Yes'
                                &&
                                (
                                    (
                                        ($field.hasClass('field-radio') || $field.hasClass('field-checkbox'))
                                        &&
                                        $field.find('input:checked').length == 0
                                    )
                                    ||
                                    (
                                        $field.parents('.form-group').length > 0
                                        &&
                                        (
                                            $field.val() == '' || $field.val() == '__NOT_SET__'
                                        )
                                    )
                                )
                            ){


                                if ($field.hasClass('field-radio') || $field.hasClass('field-checkbox')){
                                    var $label = $field.find('label').first().clone();
                                    var $p = $('<p></p>').append($label).append(' is required');
                                    $p.find('sup,input').detach();
                                    $page.find('.error-panel').append($p);
                                }else if ($field.parents('.form-group').length){
                                    if ($field.val() == '' || ($field.val() == '__NOT_SET__')) {
                                        var $label = $field.parents('.form-group').find('label').clone();
                                        var $p = $('<p></p>').append($label).append(' is required');
                                        $p.find('sup,input').detach();
                                        $page.find('.error-panel').append($p);
                                    }
                                }
                            }else if ($field.attr('data-mandatory') == 'Group'){
                                var group = $field.attr('data-mandatory-group');
                                var $group_members = $field.parents('.forms.view.form').find('[data-mandatory-group="' + group + '"]');
                                if ($page.find('.error-panel p.' + group).length == 0){

                                    var valid = false;
                                    var $labels = [];
                                    for(var j = 0; j < $group_members.length; j++){
                                        var $member = $($group_members.get(j));
                                        if ($member.parents('.form-group').length) {
                                            $labels.push($member.parents('.form-group').find('label').clone());

                                            if ($member.val() != '' && $member.val() != '__NOT_SET__'){
                                                valid = true;
                                            }
                                        }else if ($member.hasClass('field-radio') || $member.hasClass('field-checkbox')){
                                            $labels.push($member.find('label').first().clone());

                                            if ($member.is('checked')){
                                                valid = true;
                                            }
                                        }

                                    }

                                    if (valid == false){
                                        var $p = $('<p class="' + group + '"></p>');
                                        for(var j = 0; j < $labels.length; j++){
                                            if (j == 0) {
                                                $p.append($labels[j]);
                                            }else if (j == $labels.length - 1) {
                                                $p.append(' or ');
                                                $p.append($labels[j]);
                                            }else{
                                                $p.append(', ');
                                                $p.append($labels[j]);
                                            }
                                        }

                                        $p.append(' is required');
                                        $p.find('sup,input').detach();
                                        $page.find('.error-panel').append($p);
                                    }
                                }
                            }else{
                                var $label = $field.parents('.form-group').find('label').clone();
                                var $p = $('<p></p>').append($label).append(' is not valid');
                                $p.find('sup,input').detach();
                                $page.find('.error-panel').append($p);
                            }

                            ////

                            //if ($field.attr('data-mandatory') == 'Yes' && ($field.val() == '' || ($field.val() == '__NOT_SET__'))){
                            //    var $label = $field.parents('.form-group').find('label').clone();
                            //    var $p = $('<p></p>').append($label).append(' is required');
                            //    $p.find('sup').detach();
                            //    $page.find('.error-panel').append($p);
                            //}else if ($field.attr('data-mandatory') == 'Group'){
                            //    var group = $field.attr('data-mandatory-group');
                            //    var $group_members = $field.parents('.forms.view.form').find('[data-mandatory-group="' + group + '"]');
                            //    if ($page.find('.error-panel p.' + group).length == 0){
                            //
                            //        var valid = false;
                            //        var $labels = [];
                            //        for(var j = 0; j < $group_members.length; j++){
                            //            var $member = $($group_members.get(j));
                            //            $labels.push($member.parents('.form-group').find('label').clone());
                            //
                            //            if ($member.val() != '' && $member.val() != '__NOT_SET__'){
                            //                valid = true;
                            //            }
                            //        }
                            //
                            //        if (valid == false){
                            //            var $p = $('<p class="' + group + '"></p>');
                            //            for(var j = 0; j < $labels.length; j++){
                            //                if (j == 0) {
                            //                    $p.append($labels[j]);
                            //                }else if (j == $labels.length - 1) {
                            //                    $p.append(' or ');
                            //                    $p.append($labels[j]);
                            //                }else{
                            //                    $p.append(', ');
                            //                    $p.append($labels[j]);
                            //                }
                            //            }
                            //
                            //            $p.append(' is required');
                            //            $p.find('sup').detach();
                            //            $page.find('.error-panel').append($p);
                            //        }
                            //    }
                            //}else{
                            //    var $label = $field.parents('.form-group').find('label').clone();
                            //    var $p = $('<p></p>').append($label).append(' is not valid');
                            //    $p.find('sup').detach();
                            //    $page.find('.error-panel').append($p);
                            //}

                        }
                    }else{
                        /*
                         * Progress the form
                         */
                        $page.find('.error-panel').empty();

                        if ($page.next().length >= 1) {
                            $page.parents('.view.form').find('.steps .selected,.steps .error').removeClass('selected').removeClass('error').addClass('complete').next().addClass('selected');
                            $page.addClass('hidden');
                            $page.next().removeClass('hidden');
                        }else{
                            /* Submit the form */
                            //TODO: Processing screen.
                            //TODO: The form should be submitted to an action
                            //      on the form view controller first to be validate,
                            //      if successful the next (the real one) action should be
                            //      called, on fail it should redirect here.
                            // checking if the form method is using the json type so it can be picked up by another library
                            if($page.parents('form').attr('method') !== 'json'){

                                if ($page.parents('form').find('.processing').size() > 0) {
                                    $page.addClass('hidden');
                                    $page.parents('form').find('.processing').removeClass('hidden');
                                }

                                $page.parents('form').submit();

                            } else {

                                // we have json, bind to an angular response
                                var answers = $page.parents('form').serializeArray();
                                // Send this over the window to be grabbed by angular
                                // TODO: this is relatively bad practise
                                window.adaptAnswers = answers;
                                angular.element('#submitFunctionAngular').triggerHandler('click');

                            }
                        }

                        //$('.forms.view.form .steps .selected, .forms.view.form .steps .error').removeClass('selected').removeClass('error').addClass('complete').next().addClass('selected');
                        //$page.addClass('hidden').next().removeClass('hidden');

                        /* Push the new state into the browser history */

                        /* We need to find out which page were are */
                        var $pages = $page.parents('.forms.view.form').find('.view.form-page');
                        var page_number = 0;
                        var form_id = $page.parents('.forms.view.form').attr('data-form-id');
                        var path = $page.parents('.forms.view.form').find('input[name="current_url"]').val();

                        for(var i = 0; i < $pages.length; i++){
                            if ($($pages.get(i)).attr('id') == $page.attr('id')){
                                page_number = i + 2;
                            }
                        }
                        var data = {id: $page.attr('id')};

                        // window.history.pushState(data, 'Page ' + page_number, path + '/form-' + form_id + '-page-' + page_number);
                    }

                    /* Return false to prevent the browser from submitting the form */
                    return false;
                }
            );

            /*
             * Add keydown event handler for fields
             * with data-max-length
             */
            $(document).on(
                'keydown',
                '[data-max-length]',
                function(event){
                    var $this = $(this);
                    var length = $this.val().length;

                    if (length >= $this.attr('data-max-length')){
                        if (event.currentTarget.selectionStart == event.currentTarget.selectionEnd){
                            if (event.keyCode != 8 && event.keyCode != 9 && event.keyCode != 37 && event.keyCode != 39){
                                event.preventDefault();
                            }
                        }
                    }
                }
            );


            /*
             * Add focus event handelers
             */
            $(document).on(
                'focus',
                'input[type="text"], input[type="password"], textarea',
                function(event){
                    var $this = $(this);

                    /* Remove validation classes */
                    $this.parents('.form-group').removeClass('has-success').removeClass('has-error').find('.form-control-feedback').detach();
                }
            );

            /*
             * Add blur event handelers
             */
            $(document).on(
                'blur',
                'input[type="text"], input[type="password"]',
                function(event){
                    var $this = $(this);
                    var value = $this.val();
                    var valid = false;

                    console.log('BLUR: ' + $this.attr('class'));

                    /* Only do this if we have a value */
                    if (value){
                        /* Do we have an unformatter? */
                        if ($this.attr('data-unformatter')){
                            var unformatter = $this.attr('data-unformatter');

                            value = adapt.sanitize.unformat(unformatter, value);
                        }

                        /* Do we have a validator? */
                        if ($this.attr('data-validator')){
                            var validator = $this.attr('data-validator');

                            valid = adapt.sanitize.validate(validator, value);

                            //if (_forms_validators[validator]) {
                            //    if (_forms_validators[validator]['function']) {
                            //        var func = _forms_validators[validator]['function'];
                            //        func = 'func = ' + func;
                            //        eval(func);
                            //        valid = func(value);
                            //    }else if (_forms_validators[validator]['pattern']){
                            //        var pattern = new RegExp(_forms_validators[validator]['pattern']);
                            //        if (value.match(pattern)) {
                            //            valid = true;
                            //        }
                            //    }
                            //}
                        }else{
                            /* Validation not required */
                            valid = true;
                        }

                        if (valid){
                            $this.parents('.form-group').addClass('has-success').addClass('has-feedback').find('input').after('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
                        }else{
                            $this.parents('.form-group').addClass('has-error').addClass('has-feedback').find('input').after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                            $this.parents('.forms.view.form').find('#' + $this.parents('.form-control').attr('data-form-page-id')).removeClass('selected').addClass('error');
                        }
                    }

                    update_dependencies();
                }
            );

            /*
             * Add on change handler for selects
             */
            $(document).on(
                'change',
                'select',
                function(event){
                    update_dependencies();
                }
            );
        });
    });
})(jQuery);