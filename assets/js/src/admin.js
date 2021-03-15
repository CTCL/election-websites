window.ctcl = {
	addTopic( e ) {
		const lastRow = e.target.parentNode;
		const lastAddButton = lastRow.querySelector( '.button' );
		const newRemoveButton = document.createElement( 'input' );
		const newRow = lastRow.cloneNode( true );

		newRemoveButton.setAttribute( 'type', 'button' );
		newRemoveButton.setAttribute( 'class', 'button remove' );
		newRemoveButton.setAttribute( 'value', 'Remove' );
		newRemoveButton.addEventListener( 'click', window.ctcl.removeTopic );

		newRow
			.querySelector( '.button.add' )
			.addEventListener( 'click', window.ctcl.addTopic );
		newRow.querySelector( '.multitext' ).value = '';
		lastRow.appendChild( newRemoveButton );
		lastAddButton.remove();
		lastRow.parentNode.appendChild( newRow );
	},

	removeTopic( e ) {
		e.target.parentNode.remove();
	},

	handleTopics() {
		const addButton = document.querySelector( '.button.add' );
		if ( addButton ) {
			addButton.addEventListener( 'click', window.ctcl.addTopic );
		}

		const removeButtons = document.querySelectorAll( '.button.remove' );
		if ( removeButtons ) {
			removeButtons.forEach( function ( b ) {
				b.addEventListener( 'click', window.ctcl.removeTopic );
			} );
		}
	},

	handleImage() {
		const uploadButton = document.querySelector( '.button.upload' );
		const removeButton = document.querySelector( '.button.remove' );

		if ( uploadButton ) {
			uploadButton.addEventListener(
				'click',
				window.ctcl.handleImageUpload
			);
		}

		if ( removeButton ) {
			removeButton.addEventListener(
				'click',
				window.ctcl.removeImageThumbnail
			);
		}
	},

	removeImageThumbnail( e ) {
		const imageId = document.querySelector( '.imageid' );
		const imageTag = e.target.parentNode.querySelector( 'img' );

		e.target.setAttribute( 'disabled', 'disabled' );

		if ( imageId ) {
			imageId.value = '';
		}

		if ( imageTag ) {
			imageTag.remove();
		}
	},

	handleImageUpload( e ) {
		e.preventDefault();
		if ( window.ctcl.mediaUploader ) {
			window.ctcl.mediaUploader.open();
			return;
		}

		window.ctcl.mediaUploader = wp.media.frames.file_frame = wp.media( {
			title: 'Choose Image',
			button: {
				text: 'Choose Image',
			},
			multiple: false,
		} );

		window.ctcl.mediaUploader.on( 'select', function () {
			const imgTag = e.target.parentNode.querySelector( 'img' );
			const removeButton = e.target.parentNode.querySelector(
				'.button.remove'
			);
			const newImgTag = document.createElement( 'img' );
			const attachment = window.ctcl.mediaUploader
				.state()
				.get( 'selection' )
				.first()
				.toJSON();
			const imageId = document.querySelector( '.imageid' );

			if ( imageId ) {
				imageId.value = attachment.id;
			}

			if ( imgTag ) {
				imgTag.remove();
			}

			if ( removeButton ) {
				removeButton.removeAttribute( 'disabled' );
			}

			newImgTag.setAttribute( 'class', 'image-thumbnail' );
			newImgTag.setAttribute( 'alt', attachment.title );
			newImgTag.setAttribute( 'src', attachment.sizes.thumbnail.url );
			newImgTag.setAttribute( 'width', attachment.sizes.thumbnail.width );
			newImgTag.setAttribute(
				'heigt',
				attachment.sizes.thumbnail.height
			);

			e.target.parentNode.appendChild( newImgTag );
		} );

		window.ctcl.mediaUploader.open();
	},
};

// eslint-disable-next-line @wordpress/no-global-event-listener
document.addEventListener( 'DOMContentLoaded', function () {
	if ( 'elections_page_topics' === window.pagenow ) {
		window.ctcl.handleTopics();
	} else if ( document.querySelector( '.upload-wrapper' ) ) {
		window.ctcl.handleImage();
	}
} );
