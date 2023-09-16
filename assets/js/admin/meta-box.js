( function() {

	const onDocClick = ( event ) => {
		const allDayCheckbox = event.target.closest( '#just-events-all_day' );
		if ( ! allDayCheckbox ) {
			return;
		}

		document.querySelectorAll( '#just-events-start_time,#just-events-end_time' ).forEach( ( input ) => {
			if ( allDayCheckbox.checked ) {
				input.closest( 'tr' ).classList.add( 'hidden' );
			} else {
				input.closest( 'tr' ).classList.remove( 'hidden' );
			}
		} );

	};

	document.addEventListener( 'click', onDocClick );
} )();
