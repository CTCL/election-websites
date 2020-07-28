const { registerBlockType } = wp.blocks;
const { createElement } = wp.element;

const PARENT_BLOCK = 'ctcl-election-website/tile-nav-section-block';
const CHILD_BLOCK = 'ctcl-election-website/tile-nav-block';

registerBlockType( PARENT_BLOCK, {
	title: 'Tile Navigation',
	icon: 'screenoptions',
	category: 'election-blocks',
	edit: function( props ) {
		return createElement( 'div',
			{
				className: 'tile-nav-section-block-editor'
			},
			createElement( wp.blockEditor.InnerBlocks,
				{
					allowedBlocks: [ CHILD_BLOCK ]
				}
			)
		);
	},

	save: function( props ) {
		return createElement( 'nav',
			{
				className: 'tile-wrapper'
			},
			createElement( wp.blockEditor.InnerBlocks.Content )
		);
	}
} );

registerBlockType( CHILD_BLOCK, {
	title: 'Tile',
	icon: 'screenoptions',
	category: 'election-blocks',
	parent: [ PARENT_BLOCK ],
	attributes: {
		icon: {
			type: 'string'
		},
		label: {
			type: 'string',
			default: ''
		},
		url: {
			type: 'string'
		}
	},

	edit: function( props ) {

		function updateLabel( value ) {
			props.setAttributes( { label: value } );
		}

		function updateLink( url, post ) {
			props.setAttributes( { url: url } );
			if ( post ) {
				props.setAttributes( { label: post.title } );
			}
		}

		function updateIcon( value ) {
			props.setAttributes( { icon: value } );
		}

		return createElement( 'div',
			{
				className: 'tile-nav-block-editor'
			},
			createElement( wp.blockEditor.URLInput,
				{
					onChange: updateLink,
					value: props.attributes.url,
					label: 'Link'
				}
			),
			createElement( wp.components.TextControl,
				{
					label: 'Label',
					placeholder: 'Enter label',
					onChange: updateLabel,
					value: props.attributes.label
				}
			),
			createElement( wp.components.SelectControl,
				{
					onChange: updateIcon,
					options: [
						{ value: '', label: 'Select an Icon' },
						...blockEditorVars.iconOptions.map( option => ( {
							value: option, label: option
						} ) )
					],
					label: 'Icon',
					value: props.attributes.icon
				}
			)
		);
	},

	save: function( props ) {
		return <a className="tile" href={props.attributes.url}>
			<div className="bounding-box" id={props.attributes.icon}/>
			<span>{props.attributes.label}</span>
		</a>;
	}
} );
