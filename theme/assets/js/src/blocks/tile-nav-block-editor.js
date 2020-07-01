const { registerBlockType } = wp.blocks;
const { serverSideRender: ServerSideRender } = wp;
const { createElement } = wp.element;

registerBlockType( 'ctcl-election-website/tile-nav-section-block', {
	title: 'Tile Navigation Section',
	icon: 'screenoptions',
	category: 'election-blocks',
	edit: function( props ) {
		return createElement( 'div',
			{
				className: 'tile-nav-section-block-editor'
			},
			createElement( wp.blockEditor.InnerBlocks,
				{
					allowedBlocks: [ 'ctcl-election-website/tile-nav-block' ]
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

registerBlockType( 'ctcl-election-website/tile-nav-block', {
	title: 'Tile Navigation',
	icon: 'screenoptions',
	category: 'election-blocks',
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
						{ value: 'registration', label: 'registration' },
						{ value: 'vote-by-mail', label: 'vote-by-mail' },
						{ value: 'view-election-results', label: 'view-election-results' },
						{ value: 'whats-on-ballot', label: 'whats-on-ballot' },
						{ value: 'where-to-vote', label: 'where-to-vote' },
						{ value: 'become-poll-worker', label: 'become-poll-worker' },
						{ value: 'campaign-resources', label: 'campaign-resources' },
						{ value: 'news', label: 'news' }
					],
					label: 'Icon',
					value: props.attributes.icon
				}
			)
		);
	},

	save: function( props ) {
		return createElement( 'a',
			{
				className: 'tile',
				href: props.attributes.url
			},
			createElement( 'div',
				{
					className: 'bounding-box',
					id: props.attributes.icon
				}
			),
			createElement( 'span', null, props.attributes.label )
		);
	}
} );
