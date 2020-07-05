window.ctcl = {
	addTopic: function( e ) {
		var lastRow = e.target.parentNode;
		var lastAddButton = lastRow.querySelector( '.button' );
		var newRemoveButton = document.createElement( 'input' );
		var newRow = lastRow.cloneNode( true );

		newRemoveButton.setAttribute( 'type', 'button' );
		newRemoveButton.setAttribute( 'class', 'button remove' );
		newRemoveButton.setAttribute( 'value', 'Remove' );
		newRemoveButton.addEventListener( 'click', window.ctcl.removeTopic );

		newRow.querySelector( '.button.add' ).addEventListener( 'click', window.ctcl.addTopic );
		newRow.querySelector( '.multitext' ).value = '';
		lastRow.appendChild( newRemoveButton );
		lastAddButton.remove();
		lastRow.parentNode.appendChild( newRow );
	},

	removeTopic: function( e ) {
		e.target.parentNode.remove();
	},

	handleTopics: function() {
		var addButton, removeButtons;

		addButton = document.querySelector( '.button.add' );
		if ( addButton ) {
			addButton.addEventListener( 'click', window.ctcl.addTopic );
		}

		removeButtons = document.querySelectorAll( '.button.remove' );
		if ( removeButtons ) {
			removeButtons.forEach( function( b ) {
				b.addEventListener( 'click', window.ctcl.removeTopic );
			} );
		}
	},

	handleBanner: function() {
		var uploadButton = document.getElementById( 'upload_banner_image' );
		var removeButton = document.querySelector( '.button.remove' );

		if ( uploadButton ) {
			uploadButton.addEventListener( 'click', window.ctcl.handleBannerUpload );
		}

		if ( removeButton ) {
			removeButton.addEventListener( 'click', window.ctcl.removeBannerThumbnail );
		}
	},

	removeBannerThumbnail: function( e ) {
		var imageId = document.getElementById( 'banner_image' );
		var imageTag = e.target.parentNode.querySelector( 'img' );

		e.target.setAttribute( 'disabled', 'disabled' );

		if ( imageId ) {
			imageId.value = '';
		}

		if ( imageTag ) {
			imageTag.remove();
		}
	},

	handleBannerUpload: function( e ) {
		var mediaUploader;
		e.preventDefault();
		if ( mediaUploader ) {
			mediaUploader.open();
			return;
		}

		mediaUploader = wp.media.frames.file_frame = wp.media( {
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			}, multiple: false } );

		mediaUploader.on( 'select', function() {
			var imgTag = e.target.parentNode.querySelector( 'img' );
			var removeButton = e.target.parentNode.querySelector( '.button.remove' );
			var newImgTag = document.createElement( 'img' );
			var attachment = mediaUploader.state().get( 'selection' ).first().toJSON();
			var bannerImageId = document.getElementById( 'banner_image' );

			if ( bannerImageId ) {
				bannerImageId.value = attachment.id;
			}

			if ( imgTag ) {
				imgTag.remove();
			}

			if ( removeButton ) {
				removeButton.removeAttribute( 'disabled' );
			}

			newImgTag.setAttribute( 'id', 'banner_image_thumbnail' );
			newImgTag.setAttribute( 'alt', attachment.title );
			newImgTag.setAttribute( 'src', attachment.sizes.thumbnail.url );
			newImgTag.setAttribute( 'width', attachment.sizes.thumbnail.width );
			newImgTag.setAttribute( 'heigt', attachment.sizes.thumbnail.height );

			e.target.parentNode.appendChild( newImgTag );
		} );

		mediaUploader.open();
	}
};

document.addEventListener( 'DOMContentLoaded', function() {
	if ( 'elections_page_topics' === window.pagenow ) {
		window.ctcl.handleTopics();
	}	else if ( 'elections_page_banner' === window.pagenow ) {
		window.ctcl.handleBanner();
	}
} );
