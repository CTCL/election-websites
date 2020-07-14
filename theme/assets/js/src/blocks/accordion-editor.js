const { registerBlockType } = wp.blocks;
const { serverSideRender: ServerSideRender } = wp;
const { createElement } = wp.element;

const { InspectorControls, InnerBlocks } = wp.blockEditor;
const { PanelBody } = wp.components;
const { RichText } = wp.editor;

registerBlockType( 'ctcl-election-website/accordion-block', {
	title: 'Collapsible Section',
	icon: 'screenoptions',
	category: 'election-blocks',
	attributes: {
		heading: { type: 'array', source: 'children', selector: '.accordion-header' }
	},
	edit: function( props ) {
		function updateHeading( newdata ) {
			props.setAttributes( { heading: newdata } );
		}

		return <div className="accordion-editor">
			<InspectorControls>
				<PanelBody
					title="Specify section settings"
					initialOpen={true}></PanelBody>
				{/* specify type of section (h1, h3 with icon, h5 subsection) */}
				{/* for accordion: specify icon if with icon */}
			</InspectorControls>
			<RichText
				className="accordion-header"
				tagName="h1"
				onChange={updateHeading}
				value={props.attributes.heading}
				placeholder="Enter header here...">
			</RichText>
			<InnerBlocks
				className="accordion-content">
			</InnerBlocks>
		</div>;

	},

	save: function( props ) {
		return createElement( 'div',
			{
				className: 'accordion-wrapper'
			},
			createElement( true ? 'h1' : 'h2',
				{
					className: 'accordion-header'
				},
				props.attributes.heading
			),
			createElement( 'section',
				{
					className: 'accordion-content'
				},
				createElement( wp.blockEditor.InnerBlocks.Content )
			)
		);
	}
} );
