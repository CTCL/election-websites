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
		var clickedItem = e.currentTarget;

		clickedItem.classList.toggle( 'open' );
	},

	handleMobileMenuClick: function( e ) {
		document.body.classList.toggle( 'menu' );

		// parent element is an <a> tag. Don't want to its click to fire.
		e.preventDefault();
	}
};

document.addEventListener( 'DOMContentLoaded', function() {
	var mobileMenu = document.querySelector( '.mobile-menu' );
	var accordionHeaders = document.querySelectorAll( '.accordion-section-header' );
	var links = document.querySelectorAll( 'a' );

	// Enable the collapsible sections.
	accordionHeaders.forEach( function( item ) {
		item.addEventListener( 'click', window.ctcl.handleAccordionClick, { capture: true } );
	} );

	// Enable the mobile (hamburger) menu.
	if ( mobileMenu ) {
		mobileMenu.addEventListener( 'click', window.ctcl.handleMobileMenuClick );
	}

	// Open PDFs in new tabs.
	links.forEach( function( link ) {
		if ( link.href.match( /\.pdf$/ ) ) {
			link.setAttribute( 'target', '_blank' );
			link.setAttribute( 'rel', 'noopener noreferrer' );
		}
	} );
} );
