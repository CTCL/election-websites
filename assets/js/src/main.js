window.ctcl = {
	submitContactForm( token ) {
		const tokenField = document.getElementById( 'recaptcha-token' );
		const contactForm = document.getElementById( 'contact-form' );
		if ( tokenField ) {
			tokenField.value = token;
		}
		if ( contactForm ) {
			contactForm.submit();
		}
	},

	handleSpaceOrEnter( e ) {
		const clickedItem = e.currentTarget;

		// space or enter triggers a click
		if ( -1 !== [ 13, 32 ].indexOf( e.keyCode ) ) {
			clickedItem.click();

			// prevent spacebar from paginating
			e.preventDefault();
		}
	},

	handleAccordionClick( e ) {
		const clickedItem = e.currentTarget;
		const section = clickedItem.nextSibling;

		if ( clickedItem.classList.contains( 'open' ) ) {
			clickedItem.classList.remove( 'open' );
			section.setAttribute( 'aria-hidden', true );
		} else {
			clickedItem.classList.add( 'open' );
			section.setAttribute( 'aria-hidden', false );
		}
	},

	handleMobileMenuClick( e ) {
		const mobileMenu = document.querySelector( '.mobile-menu' );
		if ( document.body.classList.contains( 'menu' ) ) {
			document.body.classList.remove( 'menu' );
			mobileMenu.setAttribute( 'aria-expanded', false );
		} else {
			document.body.classList.add( 'menu' );
			mobileMenu.setAttribute( 'aria-expanded', true );
		}

		// parent element is an <a> tag. Don't want its click to fire.
		e.preventDefault();
	},

	handleReadMoreClick( e ) {
		const parent = e.currentTarget.closest( '.read-more-block' );
		if ( parent ) {
			parent.classList.toggle( 'less' );
		}
	},

	setupAccordion() {
		const hasAccordion = document.querySelector( '.accordion-group' );

		if ( ! hasAccordion ) {
			return;
		}

		const autoExpandFirstItem = document.querySelector( '.page-results' );
		const hasSubsection = document.querySelector(
			'.accordion-group.subsection'
		);
		let accordionHeaders;
		let accordionTopLevelHeaders;
		let accordionSections;

		// expand all of the top level headers by default
		// add tabindex to subsection headers
		if ( hasSubsection ) {
			accordionHeaders = document.querySelectorAll(
				'.accordion-section-wrapper.subsection .accordion-section-header'
			);
			accordionSections = document.querySelectorAll(
				'.accordion-section-wrapper.subsection .accordion-section-content'
			);

			accordionTopLevelHeaders = document.querySelectorAll(
				'.accordion-section-wrapper:not(.subsection) > .accordion-section-header'
			);

			accordionTopLevelHeaders.forEach( function ( item ) {
				item.classList.add( 'open', 'disabled' );
			} );

			// add tabindex to top level headers
		} else {
			accordionHeaders = document.querySelectorAll(
				'.accordion-section-header'
			);
			accordionSections = document.querySelectorAll(
				'.accordion-section-content'
			);
		}

		accordionHeaders.forEach( function ( item, index ) {
			item.addEventListener( 'click', window.ctcl.handleAccordionClick, {
				capture: true,
			} );
			item.addEventListener( 'keydown', window.ctcl.handleSpaceOrEnter, {
				capture: true,
			} );
			item.setAttribute( 'tabindex', index + 1 );
		} );

		accordionSections.forEach( function ( item ) {
			item.setAttribute( 'aria-hidden', true );
		} );

		if ( autoExpandFirstItem && accordionHeaders[ 0 ] ) {
			accordionHeaders[ 0 ].classList.add( 'open', 'disabled' );
		}
	},

	setupAdminBar() {
		const editButton = document.getElementById( 'wp-admin-bar-edit' );
		if ( editButton ) {
			return;
		}

		const postEditLink = document.querySelector( '.post-edit-link' );
		if ( ! postEditLink ) {
			return;
		}

		const adminBarRoot = document.getElementById(
			'wp-admin-bar-root-default'
		);
		if ( ! adminBarRoot ) {
			return;
		}

		const a = document.createElement( 'a' );
		a.className = 'ab-item';
		a.href = postEditLink.href;
		a.textContent = 'Edit Page';

		const li = document.createElement( 'li' );
		li.id = 'wp-admin-bar-edit';

		postEditLink.remove();
		li.appendChild( a );
		adminBarRoot.appendChild( li );
	},
};

// eslint-disable-next-line @wordpress/no-global-event-listener
document.addEventListener( 'DOMContentLoaded', function () {
	const mobileMenu = document.querySelector( '.mobile-menu' );
	const links = document.querySelectorAll( 'a' );
	const readMoreLinks = document.querySelectorAll(
		'.read-more-link,.read-less-link'
	);
	let header;
	const hasContactForm = document.getElementById( 'contact-form' );

	// Enable the read more links
	readMoreLinks.forEach( function ( item ) {
		item.addEventListener( 'click', window.ctcl.handleReadMoreClick, {
			capture: true,
		} );
	} );

	// Enable the mobile (hamburger) menu.
	if ( mobileMenu ) {
		mobileMenu.addEventListener(
			'click',
			window.ctcl.handleMobileMenuClick
		);
		mobileMenu.addEventListener(
			'keydown',
			window.ctcl.handleSpaceOrEnter
		);
	}

	window.ctcl.setupAccordion();

	// Open PDFs in new tabs.
	links.forEach( function ( link ) {
		if ( link.href.match( /\.pdf$/ ) ) {
			link.setAttribute( 'target', '_blank' );
			link.setAttribute( 'rel', 'noopener noreferrer' );
		}
	} );

	// scroll down if errors are present
	if ( hasContactForm ) {
		window.ctclSubmitContactForm = window.ctcl.submitContactForm; // ReCAPTCHA callback needs to be in the global namespace

		if ( document.querySelector( '.contact-form .error' ) ) {
			header = document.querySelector( 'header' );

			window.location = '#contact-form';
			setTimeout( function () {
				window.scrollBy( {
					left: 0,
					top: -2 * ( header ? header.offsetHeight : 100 ),
					behavior: 'smooth',
				} );
			}, 500 );
		}
	}

	// enable Edit Page in admin bar on WPCOM
	window.ctcl.setupAdminBar();
} );
