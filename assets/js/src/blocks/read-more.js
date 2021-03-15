const { registerBlockType } = wp.blocks;

const { RichText } = wp.blockEditor;

registerBlockType( 'ctcl-election-website/read-more-block', {
	title: 'Read More',
	icon: 'editor-insertmore',
	category: 'election-blocks',
	attributes: {
		preview: {
			type: 'array',
			source: 'children',
			selector: '.read-more-preview-content',
		},
		remaining: {
			type: 'array',
			source: 'children',
			selector: '.read-more-remaining',
		},
	},

	edit( props ) {
		return (
			<div className="read-more-block-editor">
				<RichText
					className="read-more-preview-content"
					onChange={ ( val ) =>
						props.setAttributes( { preview: val } )
					}
					value={ props.attributes.preview }
					placeholder="Enter preview text here…"
				></RichText>
				<div className="read-more-divider">Read More</div>
				<RichText
					className="read-more-remaining"
					onChange={ ( val ) =>
						props.setAttributes( { remaining: val } )
					}
					value={ props.attributes.remaining }
					placeholder="Enter full text here…"
				></RichText>
			</div>
		);
	},

	save( props ) {
		return (
			<div className="read-more-block less">
				<p className="read-more-preview">
					<span className="read-more-preview-content">
						{ props.attributes.preview }
					</span>
					<span>&nbsp;&nbsp;</span>
					<span className="read-more-link">Read More</span>
				</p>
				<p className="read-more-remaining">
					{ props.attributes.remaining }
				</p>
				<p className="read-less-link-wrapper">
					<span className="read-less-link">Read Less</span>
				</p>
			</div>
		);
	},
} );
