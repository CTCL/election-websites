const { registerBlockType } = wp.blocks;
const { createElement } = wp.element;

const { InspectorControls, InnerBlocks, RichText } = wp.blockEditor;
const { PanelBody, PanelRow, SelectControl } = wp.components;

const PARENT_BLOCK = 'ctcl-election-website/accordion-group-block';
const CHILD_BLOCK = 'ctcl-election-website/accordion-section-block';

const DEFAULT_HEADER_TAG = 'h3';
const TEMPLATE = [ [
	CHILD_BLOCK,
	{
		headerTag: DEFAULT_HEADER_TAG
	}
] ];

const AccordionBlockContext = wp.element.createContext( DEFAULT_HEADER_TAG );

const hasIconTag = ( attributes ) => {
	const { headerTag } = attributes;
	return 'h3' === headerTag;
};

const getIconEl = ( attributes ) => {
	const { icon } = attributes;
	if ( hasIconTag( attributes ) && icon ) {
		const iconUrl = `${blockEditorVars.baseUrl}/${icon}.svg`;
		return createElement( 'img', {
			width: 50,
			height: 50,
			src: iconUrl
		} );
	}
	return null;
};

registerBlockType( CHILD_BLOCK, {
	title: 'Section',
	icon: 'book',
	category: 'election-blocks',
	parent: [ PARENT_BLOCK ],
	attributes: {
		headerTag: {
			type: 'string',
			default: DEFAULT_HEADER_TAG
		},
		heading: { type: 'string' },
		icon: { type: 'string' }
	},
	edit: function( props ) {
		return <div className="accordion-section-editor">
			<AccordionBlockContext.Consumer>
				{
					( value ) => {
						props.setAttributes( {headerTag: value} );
					}
				}
			</AccordionBlockContext.Consumer>
			{ hasIconTag( props.attributes ) ?
				<InspectorControls>
					<PanelBody
						title="Specify section settings"
						initialOpen={true}>
						<PanelRow>
							<SelectControl
								label="Icon"
								value={props.attributes.icon}
								options={[
									{ value: null, label: 'Select an Icon' },
									...blockEditorVars.iconOptions.map( option => ( {
										value: option, label: option
									} ) )
								]}
								onChange={( val ) => props.setAttributes( { icon: val } )}
							></SelectControl>
						</PanelRow>
					</PanelBody>
				</InspectorControls> :
				<></>
			}
			<div class="header-wrapper">
				{ getIconEl( props.attributes ) }
				<RichText
					className="accordion-section-header"
					tagName={props.attributes.headerTag}
					onChange={( val ) => props.setAttributes( { heading: val } ) }
					value={props.attributes.heading}
					placeholder="Enter header here...">
				</RichText>
			</div>
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
				getIconEl( props.attributes ),
				createElement( 'span', null, props.attributes.heading )
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
								{ label: 'H2 headers', value: 'h2' },
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
