const { registerBlockType } = wp.blocks;
const { serverSideRender: ServerSideRender } = wp;

registerBlockType( 'ctcl-election-website/office-info', {
	title: 'Office Info',
	icon: 'building',
	category: 'election-blocks',

	edit: function( props ) {
		return (
			<ServerSideRender
				block="ctcl-election-website/office-info"
				attributes={ props.attributes }
			/>
		);
	}
} );
