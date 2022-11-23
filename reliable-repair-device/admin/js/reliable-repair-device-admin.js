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

$(document).ready(function(){
    $("#acf-field_60247c006442a").change(function(){
        $(this).find("option:selected").each(function(){
            var optionValue = $(this).attr("value");
            // alert(optionValue);
            jQuery.ajax({
		        type: "POST",
		        url: "admin-ajax.php",
		        data: {
		            action: 'show_hide_que_feci',
		            // add your parameters here
		            course_id: optionValue
		        },
		        success: function (output) {
		        	var obj = JSON.parse(output);
                $('#acf-group_601f3aa4bdd4c').css("display", obj.fac_array);
                $('#acf-group_5ff107dc9e442').css("display", obj.pro_array);
                $('#acf-group_601f37528d56f').css("display", obj.lead_array);
		         
		        },
				  error: function(errorThrown){
				      alert(errorThrown);
				  } 
		        });
        });
    }).change();
});

})( jQuery );
