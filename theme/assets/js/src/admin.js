document.addEventListener( 'DOMContentLoaded', function() {
	var addButton, removeButtons;

	if ( 'elections_page_topics' !== window.pagenow ) {
		return;
	}

	addButton = document.querySelector( '.button.add' );
	if ( addButton ) {
		addButton.addEventListener( 'click', addTopic );
	}

	removeButtons = document.querySelectorAll( '.button.remove' );
	if ( removeButtons ) {
		removeButtons.forEach( function( b ) {
			b.addEventListener( 'click', removeTopic );
		} );
	}

	function addTopic( e ) {
		var lastRow = e.target.parentNode;
		var lastAddButton = lastRow.querySelector( '.button' );
		var newRemoveButton = document.createElement( 'input' );
		var newRow = lastRow.cloneNode( true );

		newRemoveButton.setAttribute( 'type', 'button' );
		newRemoveButton.setAttribute( 'class', 'button remove' );
		newRemoveButton.setAttribute( 'value', 'Remove' );
		newRemoveButton.addEventListener( 'click', removeTopic );

		newRow.querySelector( '.button.add' ).addEventListener( 'click', addTopic );
		newRow.querySelector( '.multitext' ).value = '';
		lastRow.appendChild( newRemoveButton );
		lastAddButton.remove();
		lastRow.parentNode.appendChild( newRow );
	}

	function removeTopic( e ) {
		e.target.parentNode.remove();
	}

} );

