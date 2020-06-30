var el = wp.element.createElement;

wp.blocks.registerBlockType( 'ctcl-election-website/numbered-section-block', {
	title: 'Numbered Section',
	icon: 'editor-ol',
	category: 'common',

	edit: function( props ) {
		return el( 'section',
			{
				className: 'numbered-section-block-editor'
			},
			el( 'div',
				{
					className: 'numbered-section-block-editor-counter'
				}
			),
			el( 'div',
				{
					className: 'numbered-section-block-editor-content'
				},
				el( wp.blockEditor.InnerBlocks )
			)
		);
	},

	save: function( props ) {
		return el( 'section',
			{
				className: 'numbered-section'
			},
			el( 'div',
				{
					className: 'numbered-section-content'
				},
				el( wp.blockEditor.InnerBlocks.Content )
			)
		);
	}
} );
