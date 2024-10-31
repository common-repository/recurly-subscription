(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
    $(function () {
        $('#rs_recurring').click(function () {
            if ($(this).is(":checked"))
            {
                $("#rs_plan").removeAttr("disabled");
                $("#rs_recurring_label").removeAttr("disabled");
            }
            else {
                $("#rs_plan").val("");
                $("#rs_plan").attr("disabled", "disabled");
                $("#rs_recurring_label").val('');
                $("#rs_recurring_label").attr("disabled", "disabled");
            }
        });

        $('#rs_form').submit(function () {
            // Validate Stuff
            if ($('#rs_subdomain').val() == '' || $('#rs_pri_key').val() == '' || $('#rs_pub_key').val() == '' || $('#rs_currency').val() == ''|| $('#rs_subs_amt').val() == '') {
                $('#rs_error').html('Please enter Recurly Sub Domain , Private Key, Public Key , Subscription Amount & Select Coutry');
                return false;
            } else if ($('#rs_recurring').is(":checked")) {
                if ($("#rs_plan").val() == '' || $("#rs_recurring_label").val()=='') {
                    $('#rs_error').html('Please enter Recurring Label & Recurly Plan Code');
                    return false;
                } else {
                    $('#rs_error').html('');
                }
            } else {
                $('#rs_error').html('');
            }


            return true;
        });
    });
})( jQuery );
