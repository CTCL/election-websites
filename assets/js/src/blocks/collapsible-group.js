const { registerBlockType } = wp.blocks;
const { createElement } = wp.element;

const { InspectorControls, InnerBlocks, RichText } = wp.blockEditor;
const { PanelBody, PanelRow, SelectControl } = wp.components;

const PARENT_BLOCK = 'ctcl-election-website/accordion-group-block';
const CHILD_BLOCK = 'ctcl-election-website/accordion-section-block';
const NESTED_PARENT_BLOCK = 'ctcl-election-website/accordion-nested-group-block';

const AccordionBlockContext = wp.element.createContext( false );

const getIconEl = ( { icon } ) => {
	if ( icon ) {
		const iconUrl = `${blockEditorVars.baseUrl}/${icon}.svg`;
		return createElement( 'img', {
			height: 50,
			src: iconUrl
		} );
	}
	return null;
};

const getHeaderTag = ( { isNestedGroup } ) => {
	return isNestedGroup ? 'h3' : 'h2';
};

const getHeaderClasses = ( { icon } ) => {
	return `accordion-section-header ${icon ? 'with-icon' : ''}`;
};

registerBlockType( CHILD_BLOCK, {
	title: 'Section',
	icon: 'book',
	category: 'election-blocks',
	parent: [ PARENT_BLOCK ],
	attributes: {
		heading: { type: 'string' },
		icon: { type: 'string' },
		isNestedGroup: {
			type: 'boolean',
			default: false
		}
	},
	edit: function( props ) {
		const DISALLOWED_BLOCKS = [ PARENT_BLOCK, CHILD_BLOCK ];
		if ( props.attributes.isNestedGroup ) {
			DISALLOWED_BLOCKS.push( NESTED_PARENT_BLOCK );
		}
		const ALLOWED_BLOCKS = wp.blocks.getBlockTypes().map( block => block.name ).filter( blockName => ! DISALLOWED_BLOCKS.includes( blockName ) );

		return <div className="accordion-section-editor">
			<AccordionBlockContext.Consumer>
				{
					( value ) => {
						props.setAttributes( { isNestedGroup: value } );
					}
				}
			</AccordionBlockContext.Consumer>
			{ ( ! props.attributes.isNestedGroup ) ?
				<InspectorControls>
					<PanelBody
						title="Section"
						initialOpen={true}>
						<PanelRow>
							<SelectControl
								label="Icon"
								value={props.attributes.icon}
								options={[
									{ value: null, label: 'Select an Icon', key: '_placeholder' },
									...Object.entries( blockEditorVars.iconOptions ).map( ( [ value, label ] ) => ( {
										value: value, label: label, key: value
									} ) )
								]}
								onChange={( val ) => props.setAttributes( { icon: val } )}
							></SelectControl>
						</PanelRow>
					</PanelBody>
				</InspectorControls> :
				<></>
			}
			<div className="header-wrapper">
				{ getIconEl( props.attributes ) }
				<RichText
					className={getHeaderClasses( props.attributes )}
					tagName={getHeaderTag( props.attributes )}
					onChange={( val ) => props.setAttributes( { heading: val } ) }
					value={props.attributes.heading}
					placeholder="Enter header here…">
				</RichText>
			</div>
			<InnerBlocks
				className="accordion-section-content-editor" allowedBlocks={ALLOWED_BLOCKS}/>
		</div>;
	},

	save: function( props ) {
		return <div className={`accordion-section-wrapper ${props.attributes.isNestedGroup ? 'subsection' : ''}`}>
			{ createElement( getHeaderTag( props.attributes ),
				{
					className: getHeaderClasses( props.attributes )
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

const getParentEditTemplate = ( isNestedGroup ) => {
	return  <div className={`accordion-group-editor ${isNestedGroup ? 'subsection' : ''}`}>
		<AccordionBlockContext.Provider value={isNestedGroup}>
			<InnerBlocks
				className="accordion-group-wrapper"
				allowedBlocks={[ CHILD_BLOCK ]}
				template={[ [ CHILD_BLOCK ] ]} />
		</AccordionBlockContext.Provider>
	</div>;
};

registerBlockType( PARENT_BLOCK, {
	title: 'Collapsible Group',
	icon: 'book',
	category: 'election-blocks',
	edit: function() {
		return getParentEditTemplate( false );

	},

	save: function( props ) {
		return <section className="accordion-group">
			<InnerBlocks.Content />
		</section>;
	}
} );

registerBlockType( NESTED_PARENT_BLOCK, {
	title: 'Inner Collapsible Group',
	icon: 'book',
	category: 'election-blocks',
	parent: [ CHILD_BLOCK ],
	edit: function() {
		return getParentEditTemplate( true );
	},

	save: function( props ) {
		return <section className="accordion-group subsection">
			<InnerBlocks.Content />
		</section>;
	}
} );
