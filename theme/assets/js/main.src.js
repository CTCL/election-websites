function submitContactForm( token ) {
	var tokenField = document.getElementById( 'recaptcha-token' );
	var contactForm = document.getElementById( 'contact-form' );
	if ( tokenField ) {
		tokenField.value = token;
	}
	if ( contactForm ) {
		contactForm.submit();
	}
}

( function( $ ) {
	$( document ).ready( function() {
		$( '.accordion-section-header' ).click( function( e ) {
			$target = $( e.target );
			if ( $target.hasClass( 'open' ) ) {
				$target.removeClass( 'open' );
			} else {
				$target.addClass( 'open' );
			}
		} );
	} );

} ( jQuery ) );

//# sourceMappingURL=main.src.js.map