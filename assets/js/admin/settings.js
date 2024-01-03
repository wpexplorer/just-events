( function() {

	/**
	 * Enable/disable the hidden field used to flush rewrites when certain fields are altered.
	 */
	const maybeFlushRewrites = () => {
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

		document.querySelector( '.just-events-admin-options-form' ).addEventListener( 'submit', () => {
			if ( defaultFieldVals !== getFieldVals() ) {
				flushRewriteField.value = '1';
			}
		} );
	}

	/**
	 * Admin tabs.
	 */
	const tabs = () => {
		document.addEventListener( 'click', event => {
			const target = event.target;
			
			if ( ! target.closest( '.just-events-admin-tabs a' ) ) {
				return;
			}
			
			event.preventDefault();
			
			document.querySelectorAll( '.just-events-admin-tabs a' ).forEach( ( tablink ) => {
				tablink.classList.remove( 'nav-tab-active' );
			} );
			
			target.classList.add( 'nav-tab-active' );
			const targetTab = target.getAttribute( 'data-tab' );
			
			document.querySelectorAll( '.just-events-admin-options-form .just-events-admin-tab-item' ).forEach( item => {
				if ( item.classList.contains( `just-events-admin-tab-item--${targetTab}` ) ) {
					item.style.display = 'block';
				} else {
					item.style.display = 'none';
				}
			} );
		} );

		// Click first tab on page load to render initial settings.
		document.addEventListener( 'DOMContentLoaded', function () {
		//	document.querySelector( '.just-events-admin-tabs .nav-tab' ).click();
		}, false );

	};

	// Start things up.
	maybeFlushRewrites();
	tabs();
} )();
