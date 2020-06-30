var el = wp.element.createElement;

wp.blocks.registerBlockType( 'ctcl-election-website/contact-form', {
	title: 'Contact Form',
	icon: 'email',
	category: 'election-blocks',

	edit: function( props ) {
		return el( 'div', {}, 'Hello world' );
	},

	save: function( props ) {
		return el( 'a', { href: url }, 'something else' );
	}
} );
