const { registerBlockType } = wp.blocks;
const { createElement } = wp.element;

const { createHigherOrderComponent } = wp.compose;

const { InspectorControls, InnerBlocks, RichText, URLInput } = wp.blockEditor;
const { PanelBody, PanelRow, SelectControl, TextControl } = wp.components;

registerBlockType( 'ctcl-election-website/read-more-block', {
	title: 'Read More',
	icon: 'editor-insertmore',
	category: 'election-blocks',
	attributes: {
		preview: {
			type: 'array',
			source: 'children',
			selector: '.read-more-preview-content'
		},
		remaining: {
			type: 'array',
			source: 'children',
			selector: '.read-more-remaining'
		}
	},

	edit: function( props ) {
		return <div className="read-more-block-editor">
			<RichText
				className="read-more-preview-content"
				onChange={( val ) => props.setAttributes( { preview: val } ) }
				value={props.attributes.preview}
				placeholder="Enter preview text here…">
			</RichText>
			<div class="read-more-divider">Read More</div>
			<RichText
				className="read-more-remaining"
				onChange={( val ) => props.setAttributes( { remaining: val } ) }
				value={props.attributes.remaining}
				placeholder="Enter full text here…">
			</RichText>
		</div>;
	},

	save: function( props ) {
		return <div className="read-more-block less">
			<p className="read-more-preview">
				<span className="read-more-preview-content">{ props.attributes.preview }</span>
				<span>&nbsp;&nbsp;</span>
				<a className="read-more-link">Read More</a>
			</p>
			<p className="read-more-remaining">{ props.attributes.remaining }</p>
			<p className="read-less-link-wrapper"><a className="read-less-link">Read Less</a></p>
		</div>;
	}
} );
