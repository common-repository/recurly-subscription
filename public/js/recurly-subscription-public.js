(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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

    $('#rs_subsc_form').validate({
        highlight: function(input) {
            $(input).addClass('error');
        },
        errorPlacement: function(error, element){},
        submitHandler: function (form) {
             $('#rs_loading').show();
             var subscriberForm = $('#rs_subsc_form').serializeArray();
             var subscriberFormObject = {action : "subscriber_form_callback"};
             $.each(subscriberForm,
                       function(i, v) {
                       subscriberFormObject[v.name] = v.value;
                       });
               //console.log(subscriberFormObject);
        $.ajax({
            type: "POST",
            url: rs_public_script.rs_ajax_url,
            dataType: 'json',
            data:subscriberFormObject,
            success:function(res){
                //console.log(res);
                if(res.code==201){
                   $('#rs_loading').hide();
                 document.getElementById("rs_subsc_form").reset();
                 $('#rs_msg').html('<p style="color:#17e846;">Subscribed Successfully.!</p>');
                }else{
                   $('#rs_loading').hide();
                 $('#rs_msg').html('<p style="color:#ff0000;">Error: '+res.message+'.!</p>');
                }
               
            }
        });

            return false;
        },
   });

$('.cc_field').keyup(function(){
        if (this.value.length == this.maxLength) {
            var card_no =   $('#ccn_1').val()+''+$('#ccn_2').val()+''+$('#ccn_3').val()+''+$('#ccn_4').val();
            var ccn_type = GetCardType(card_no);
            $('#cc_type').val(ccn_type);
            var logo_name   =   ccn_type ? ccn_type : 'default';
            $('#selected_card_logo').attr('src',rs_public_script.pluginsUrl+'/images/cards/'+logo_name+'.svg');
            $(this).next('.cc_field').focus();
        }
    });
function GetCardType(number){
    // visa
    var re = new RegExp("^4");
    if (number.match(re) != null)
        return "V";//"Visa";

    // Mastercard
    re = new RegExp("^5[1-5]");
    if (number.match(re) != null)
        return "M";//"Mastercard";

    // AMEX
    re = new RegExp("^3[47]");
    if (number.match(re) != null)
        return "AMEX";

    // Discover
    re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
    if (number.match(re) != null)
        return "D";//"Discover";

    // Diners
    re = new RegExp("^36");
    if (number.match(re) != null)
        return "N";//"Diners";

    // Diners - Carte Blanche
    re = new RegExp("^30[0-5]");
    if (number.match(re) != null)
        return "Diners - Carte Blanche";

    // JCB
    re = new RegExp("^35(2[89]|[3-8][0-9])");
    if (number.match(re) != null)
        return "J";//"JCB";

    // Visa Electron
    re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
    if (number.match(re) != null)
        return "E";//"Visa Electron";

    return "";
}
});
})( jQuery );
