const { registerBlockType } = wp.blocks;
const { serverSideRender: ServerSideRender } = wp;
const { Disabled } = wp.components;

registerBlockType( 'ctcl-election-website/contact-form', {
	title: 'Contact Form',
	icon: 'email',
	category: 'election-blocks',

	edit: function( props ) {
		return (
			<Disabled>
				<ServerSideRender
					block="ctcl-election-website/contact-form"
					attributes={ props.attributes }
				/>
			</Disabled>
		);
	}
} );
