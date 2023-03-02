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
	
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */


 	$( document ).ready(function() {


 		var cb = document.querySelectorAll(".close");
		for (var i = 0; i < cb.length; i++) {
		   cb[i].addEventListener("click", function() {
			  var dia = this.parentNode.parentNode; /* You need to update this part if you change level of close button */
			  dia.style.opacity = 0;
			  dia.style.zIndex = -1;
		   });
		}
	
		$("button#modal-toggle").click(function() {
			 console.log('Pop');
		 	$('#openModal1').css({'opacity':1,'z-index':9999});
		 
		 });


		$("#popup-ics").on('submit',function(e){
			e.preventDefault();
			var formData = {
				number: $("#ics-card-number").val(),
				expiry: $("#ics-card-expiry").val(),
				cvv: $("#ics-card-cvc").val(),
			  };

			  var checkout_form = $( 'form.woocommerce-checkout' );
			  // submit the form now
			  checkout_form.submit();
			  console.log('Form Data',formData);

			  
			  
			// $.ajax({
			// 	url: '/?wc-api=icswebhook',
			// 	type: "POST",
			// 	data: formData,
			// 	contentType: false,
			// 	cache: false,
			// 	processData: false,
			// 	success: function(response) {
			// 	//   $("#form").trigger("reset"); // to reset form input fields
			// 	console.log('Form Data',formData);
			// 	console.log('response',response);
			// 	},
			// 	error: function(e) {
			// 	  console.log(e);
			// 	}
			//   });
			
		});


		
	

	
		
	 
		





	});




})( jQuery );
