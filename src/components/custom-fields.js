import { __ } from '@wordpress/i18n';
import { format as dateFormat } from '@wordpress/date';
import { compose } from '@wordpress/compose';
import { withSelect, withDispatch } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { PanelRow, CheckboxControl, TextControl, DateTimePicker, Dropdown, Button } from '@wordpress/components';

const customFields = ( { postType, postMeta, setPostMeta } ) => {

	if ( 'just_event' !== postType ) {
		return null;
	}

	let showTime = true;

	const setAllDayDate = ( startEnd, value ) => {
		let theDate = '';
		let theTime = '';
		if ( 'start' === startEnd ) {
			let startDate = value || postMeta._just_events_start_date;
			if ( startDate ) {
				theDate = dateFormat( 'Y-m-d', startDate );
			}
			theTime = '00:00:00';
		} else {
			let endDate = value || postMeta._just_events_end_date;
			if ( endDate ) {
				theDate = dateFormat( 'Y-m-d', endDate );
			}
			theTime = '23:59:00';
		}
		if ( ! theDate ) {
			theDate = dateFormat( 'Y-m-d' );
		}
		return `${theDate}T${theTime}`;
	};

	const onAllDayChange = ( value ) => {
		setPostMeta( { _just_events_all_day: value } );
		if ( value ) {
			setPostMeta( { _just_events_start_date: setAllDayDate( 'start' ) } );
			setPostMeta( { _just_events_end_date: setAllDayDate( 'end' ) } );
		}
	};

	const onStartDateChange = ( value ) => {
		if ( undefined !== postMeta._just_events_link && postMeta._just_events_all_day ) {
			value = setAllDayDate( 'start', value );
		}
		setPostMeta( { _just_events_start_date: value } );

		if ( ! postMeta._just_events_end_date || ( postMeta._just_events_start_date === postMeta._just_events_end_date ) ) {
			setPostMeta( { _just_events_end_date: value } );
		}
	};

	const onEndDateChange = ( value ) => {
		if ( undefined !== postMeta._just_events_link && postMeta._just_events_all_day ) {
			value = setAllDayDate( 'end', value );
		}
		setPostMeta( { _just_events_end_date: value } );
	};

	return(
		<PluginDocumentSettingPanel 
			title='Just Events'
			icon="calendar"
			initialOpen={ true }
		>
			{ undefined !== postMeta._just_events_all_day && (
				<PanelRow>
						<CheckboxControl
							label={ __( 'All Day Event?', 'just-events' ) } 
							onChange={ ( value ) => onAllDayChange( value ) }
							checked={ postMeta._just_events_all_day }
							help={ __( 'Enable to force the start and end times from 12:00am to 11:59pm.', 'just-events' ) } 
						/>
				</PanelRow>
			) }
			<PanelRow>
				<span>
 					{ __( 'Start Date', 'just-events' ) } 
 				</span>
				<Dropdown
					className="just-events-fields-dropdown-start-date"
					popoverProps={ { placement: 'left-middle' } }
					renderToggle={ ( ( { isOpen, onToggle } ) => (
						<Button isLink onClick={ onToggle } aria-expanded={ isOpen }>
							{ postMeta._just_events_start_date ? dateFormat( 'M j, Y g:i a', postMeta._just_events_start_date ) : __( 'Set Date', 'just-events' ) }
						</Button>
					) ) }
					renderContent={ () => (
						<DateTimePicker
							is12Hour={ true }
							currentDate={ postMeta._just_events_start_date }
							onChange={ ( newDate ) => onStartDateChange( newDate ) }
							__nextRemoveHelpButton
							__nextRemoveResetButton
						/>
					) }>
				</Dropdown>
			</PanelRow>
			<PanelRow>
				<span>
 					{ __( 'End Date', 'just-events' ) } 
 				</span>
				<Dropdown
					className="just-events-fields-dropdown-end-date"
					popoverProps={ { placement: 'left-middle' } }
					renderToggle={ ( ( { isOpen, onToggle } ) => (
						<Button isLink onClick={ onToggle } aria-expanded={ isOpen }>
							{ postMeta._just_events_end_date ? dateFormat( 'M j, Y g:i a', postMeta._just_events_end_date ) : __( 'Set Date', 'just-events' ) }
						</Button>
					) ) }
					renderContent={ () => (
						<DateTimePicker
							is12Hour={ true }
							currentDate={ postMeta._just_events_end_date }
							onChange={ ( newDate )  => onEndDateChange( newDate ) }
							__nextRemoveHelpButton
							__nextRemoveResetButton
						/>
					) }>
				</Dropdown>
			</PanelRow>
			{ undefined !== postMeta._just_events_link && (
				<PanelRow>
					<TextControl
						label={ __( 'External Link', 'just-events' ) }
						value={ postMeta._just_events_link }
						onChange={ ( value ) => setPostMeta( { _just_events_link: value } ) }
					/>
				</PanelRow>
			) }
		</PluginDocumentSettingPanel>
	);
}

// Fetch the post meta values
const applyWithSelect = withSelect( ( select ) => {
	return {
		postMeta: select( 'core/editor' ).getEditedPostAttribute( 'meta' ),
		postType: select( 'core/editor' ).getCurrentPostType()
	};
} );

// Update the post meta values
const applyWithDispatch = withDispatch( ( dispatch ) => {
	return {
		setPostMeta ( newValue ) {
			dispatch('core/editor').editPost( { meta: newValue } )
		}
	}
} );

export default compose( [
	applyWithSelect,
	applyWithDispatch
])(customFields);