( function() {
	const flushRewriteField = document.querySelector( 'input[name="just_events_admin_flush_rewrite_rules"]' );

	const getFieldVals = () => {
		let fieldVals = '';
		document.querySelectorAll( '[name="just_events[post_type_has_archive]"],[name="just_events[post_type_archive_slug]"],[name="just_events[post_type_rewrite_slug]"]' ).forEach( field => {
			if ( 'checkbox' === field.getAttribute( 'type' ) ) {
				fieldVals += field.checked ? 'on' : 'off';
			} else {
				fieldVals += field.value;
			}
		} );
		return fieldVals;
	};

	const defaultFieldVals = getFieldVals();

	document.querySelector( '.just-events-admin-options-form' ).addEventListener( 'submit', function() {
		if ( defaultFieldVals !== getFieldVals() ) {
			flushRewriteField.value = '1';
		}
	} );
	
} )();
