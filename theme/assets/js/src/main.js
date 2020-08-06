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

	handleSpaceOrEnter: function( e ) {
		var clickedItem = e.currentTarget;

		// space or enter triggers a click
		if ( -1 !== [ 13, 32 ].indexOf( e.keyCode ) ) {
			clickedItem.click();

			// prevent spacebar from paginating
			e.preventDefault();
		}
	},

	handleAccordionClick: function( e ) {
		var clickedItem = e.currentTarget;
		var section = clickedItem.nextSibling;

		if ( clickedItem.classList.contains( 'open' ) ) {
			clickedItem.classList.remove( 'open' );
			section.setAttribute( 'aria-hidden', true );
		} else {
			clickedItem.classList.add( 'open' );
			section.setAttribute( 'aria-hidden', false );
		}
	},

	handleMobileMenuClick: function( e ) {
		document.body.classList.toggle( 'menu' );

		// parent element is an <a> tag. Don't want its click to fire.
		e.preventDefault();
	},

	handleReadMoreClick: function( e ) {
		var parent = e.currentTarget.closest( '.read-more-block' );
		if ( parent ) {
			parent.classList.toggle( 'less' );
		}
	},

	handleReadLessClick: function( e ) {
		var parent = e.currentTarget.closest( '.read-more-block' );
		if ( parent ) {
			parent.classList.toggle( 'less' );
		}
	}
};

document.addEventListener( 'DOMContentLoaded', function() {
	var mobileMenu = document.querySelector( '.mobile-menu' );
	var accordionHeaders = document.querySelectorAll( '.accordion-section-header' );
	var links = document.querySelectorAll( 'a' );
	var readMoreLinks = document.querySelectorAll( '.read-more-link' );
	var readLessLinks = document.querySelectorAll( '.read-less-link' );
	var header;
	var accordionTopLevelHeaders;
	var accordionSections;

	if ( accordionHeaders ) {
		accordionTopLevelHeaders = document.querySelectorAll( '.accordion-section-wrapper:not(.subsection) > .accordion-section-header' );
		accordionSections = document.querySelectorAll( '.accordion-section-content' );

		// Enable the collapsible sections.
		accordionHeaders.forEach( function( item, index ) {
			item.addEventListener( 'click', window.ctcl.handleAccordionClick, { capture: true } );
			item.setAttribute( 'tabindex', index );
			item.addEventListener( 'keydown', window.ctcl.handleSpaceOrEnter, { capture: true } );
		} );

		accordionTopLevelHeaders.forEach( function( item, index ) {
			item.setAttribute( 'tabindex', index + 1 );
		} );

		accordionSections.forEach( function( item ) {
			item.setAttribute( 'aria-hidden', true );
		} );
	}

	// Enable the read more links
	readMoreLinks.forEach( function( item ) {
		item.addEventListener( 'click', window.ctcl.handleReadMoreClick, { capture: true } );
	} );

	// Enable the read more link
	readLessLinks.forEach( function( item ) {
		item.addEventListener( 'click', window.ctcl.handleReadLessClick, { capture: true } );
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

	// scroll down if errors are present
	if ( document.querySelector( '.error' ) && document.getElementById( 'contact-form' ) ) {
		header = document.querySelector( 'header' );

		window.location = '#contact-form';
		setTimeout( function() {
			window.scrollBy( { left: 0, top: -2 * ( header ? header.offsetHeight : 100 ), behavior: 'smooth' } );
		}, 500 );
	}
} );
