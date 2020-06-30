function submitContactForm( token ) {
	document.getElementById( 'recaptcha-token' ).value = token;
	document.getElementById( 'contact-form' ).submit();
}
