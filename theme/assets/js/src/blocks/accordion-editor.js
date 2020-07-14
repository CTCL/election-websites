const { registerBlockType } = wp.blocks;
const { createElement } = wp.element;

const { InspectorControls, InnerBlocks, RichText } = wp.blockEditor;
const { PanelBody, PanelRow, SelectControl } = wp.components;

const PARENT_BLOCK = 'ctcl-election-website/accordion-group-block';
const CHILD_BLOCK = 'ctcl-election-website/accordion-section-block';

const DEFAULT_HEADER_TAG = 'h1';
const TEMPLATE = [ [
	CHILD_BLOCK,
	{
		headerTag: DEFAULT_HEADER_TAG
	}
] ];

const AccordionBlockContext = wp.element.createContext( DEFAULT_HEADER_TAG );

registerBlockType( CHILD_BLOCK, {
	title: 'Collapsible Section',
	icon: 'book',
	category: 'election-blocks',
	parent: [ PARENT_BLOCK ],
	attributes: {
		headerTag: {
			type: 'string',
			default: DEFAULT_HEADER_TAG
		},
		heading: { type: 'array', source: 'children', selector: '.accordion-section-header' }
	},
	edit: function( props ) {

		function updateHeading( newdata ) {
			props.setAttributes( { heading: newdata } );
		}

		return <div className="accordion-section-editor">
			<AccordionBlockContext.Consumer>
				{
					( value ) => {
						props.setAttributes( {headerTag: value} );
					}
				}
			</AccordionBlockContext.Consumer>
			<InspectorControls>
				<PanelBody
					title="Specify section settings"
					initialOpen={true}></PanelBody>
				{/* for accordion: specify icon if with icon */}
			</InspectorControls>
			<RichText
				className="accordion-section-header"
				tagName={props.attributes.headerTag}
				onChange={updateHeading}
				value={props.attributes.heading}
				placeholder="Enter header here...">
			</RichText>
			<InnerBlocks
				className="accordion-section-content-editor" />
		</div>;
	},

	save: function( props ) {
		return <div className={`accordion-section-wrapper ${( 'h5' === props.attributes.headerTag ) ? 'subsection' : ''}`}>
			{ createElement( props.attributes.headerTag,
				{
					className: 'accordion-section-header'
				},
				props.attributes.heading
			) }
			<section className="accordion-section-content">
				<InnerBlocks.Content></InnerBlocks.Content>
			</section>
		</div>;
	}
} );


registerBlockType( PARENT_BLOCK, {
	title: 'Collapsible Group',
	icon: 'book',
	category: 'election-blocks',
	attributes: {
		headerTag: {
			type: 'string',
			default: DEFAULT_HEADER_TAG
		}
	},
	edit: function( props ) {
		const { clientId } = props;

		return <div className="accordion-group-editor">
			<InspectorControls>
				<PanelBody
					title="Collapsible Group Settings"
					initialOpen={true}>
					<PanelRow>
						<SelectControl
							label="Header Style"
							value={props.attributes.headerTag}
							options={[
								{ label: 'H1 headers', value: 'h1' },
								{ label: 'H3 headers with icon', value: 'h3' },
								{ label: 'H5 headers (subsections)', value: 'h5' }
							]}
							onChange={( val ) => props.setAttributes( { headerTag: val } )}
						></SelectControl>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<AccordionBlockContext.Provider value={props.attributes.headerTag}>
				<InnerBlocks
					className="accordion-group-wrapper"
					allowedBlocks={[ CHILD_BLOCK ]}
					template={TEMPLATE} />
			</AccordionBlockContext.Provider>
		</div>;

	},

	save: function( props ) {
		return <section className={`accordion-group ${( 'h5' === props.attributes.headerTag ) ? 'subsection' : ''}`}>
			<InnerBlocks.Content />
		</section>;
	}
} );
