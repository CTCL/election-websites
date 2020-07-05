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

//# sourceMappingURL=main.src.js.map