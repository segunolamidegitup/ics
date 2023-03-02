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


	 $( window ).load(function() {
	 
		testmode_toggle();
		threed_toggle();

		$('#woocommerce_ics_test_mode').change(function(){
			testmode_toggle();
		});//Change

		$('#woocommerce_ics_enabled3d').change(function(){
			threed_toggle();

		});//Change

		
		function testmode_toggle(){

			if($('#woocommerce_ics_test_mode').is(":checked")){ //test mode  
			//Hide Live	 
			// jQuery(".form-table tbody tr").eq(8).hide();
			jQuery(".form-table tbody tr").eq(9).hide();
			jQuery(".form-table tbody tr").eq(10).hide();
			jQuery(".form-table tbody tr").eq(11).hide();

			//show Test mode
			jQuery(".form-table tbody tr").eq(12).show();
			jQuery(".form-table tbody tr").eq(13).show();
			jQuery(".form-table tbody tr").eq(14).show();
			jQuery(".form-table tbody tr").eq(15).show();
			
			}else{
				//$(".answer").hide();

				//Hide Live	 
			// jQuery(".form-table tbody tr").eq(8).show();
			jQuery(".form-table tbody tr").eq(9).show();
			jQuery(".form-table tbody tr").eq(10).show();
			jQuery(".form-table tbody tr").eq(11).show();

			//show Test mode
			jQuery(".form-table tbody tr").eq(12).hide();
			jQuery(".form-table tbody tr").eq(13).hide();
			jQuery(".form-table tbody tr").eq(14).hide();
			jQuery(".form-table tbody tr").eq(15).hide();
			}

		}
 

		function threed_toggle(){

			if($('#woocommerce_ics_enabled3d').is(":checked")){ //test mode  
			//Hide 	 
			jQuery(".form-table tbody tr").eq(5).hide();
			jQuery(".form-table tbody tr").eq(6).hide();
			jQuery(".form-table tbody tr").eq(7).hide();
		

			
			}else{
			 
			//show Test mode
			jQuery(".form-table tbody tr").eq(5).show();
			jQuery(".form-table tbody tr").eq(6).show();
			jQuery(".form-table tbody tr").eq(7).show();
			}

		}




	});

})( jQuery );
