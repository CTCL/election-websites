const { registerBlockType } = wp.blocks;
const { serverSideRender: ServerSideRender } = wp;
const { createElement } = wp.element;

wp.blocks.registerBlockType( 'ctcl-election-website/numbered-section-block', {
	title: 'Numbered Section',
	icon: 'editor-ol',
	category: 'election-blocks',

	edit: function( props ) {
		return createElement( 'section',
			{
				className: 'numbered-section-block-editor'
			},
			createElement( 'div',
				{
					className: 'numbered-section-block-editor-counter'
				}
			),
			createElement( 'div',
				{
					className: 'numbered-section-block-editor-content'
				},
				createElement( wp.blockEditor.InnerBlocks )
			)
		);
	},

	save: function( props ) {
		return createElement( 'section',
			{
				className: 'numbered-section'
			},
			createElement( 'div',
				{
					className: 'numbered-section-content'
				},
				createElement( wp.blockEditor.InnerBlocks.Content )
			)
		);
	}
} );
