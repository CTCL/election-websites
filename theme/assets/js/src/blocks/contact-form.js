const { registerBlockType } = wp.blocks;
const { serverSideRender: ServerSideRender } = wp;

registerBlockType( 'ctcl-election-website/contact-form', {
	title: 'Contact Form',
	icon: 'email',
	category: 'election-blocks',

	edit: function( props ) {
		return (
			<ServerSideRender
				block="ctcl-election-website/contact-form"
				attributes={ props.attributes }
			/>
		);
	}
} );
