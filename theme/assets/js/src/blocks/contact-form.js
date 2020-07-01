const { registerBlockType } = wp.blocks;

registerBlockType( 'ctcl-election-website/contact-form', {
	title: 'Contact Form',
	icon: 'email',
	category: 'election-blocks',

	edit( { className } ) {
		return <p className={ className }>Hello World, step 2 (from the editor, in green).</p>;
	},

	save() {
		return <p>Hello World, step 2 (from the frontend, in red).</p>;
	}
} );
