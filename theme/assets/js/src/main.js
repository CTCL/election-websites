window.ctcl = {
	submitContactForm: function( token ) {
		var tokenField = document.getElementById( 'recaptcha-token' );
		var contactForm = document.getElementById( 'contact-form' );
		if ( tokenField ) {
			tokenField.value = token;
		}
		if ( contactForm ) {
			contactForm.submit();
		}
	},

	handleAccordionClick: function( e ) {
		var clickedItem = e.target;

		clickedItem.classList.toggle( 'open' );
	}
};

document.addEventListener( 'DOMContentLoaded', function() {
	var headers = Array.from( document.querySelectorAll( '.accordion-section-header' ) );

	headers.forEach( function( item ) {
		item.addEventListener( 'click', window.ctcl.handleAccordionClick );
	} );
} );
