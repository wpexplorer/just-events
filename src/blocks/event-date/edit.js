import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { SelectControl, TextControl, FormToggle, PanelBody, PanelRow } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

import './editor.scss';
import metadata from './block.json';

export default function Edit( { context, attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	const { event, show_time, start_end } = attributes;
	attributes.event = context.postId ?? 0; // needed for Query Loop to work properly
	return (
		<>
		<InspectorControls>
			<PanelBody>
				<SelectControl
						label = { __( 'Display', 'just-events' ) }
						options = {
							[
								{ label: __( 'Start & End Dates', 'just-events' ), value: '' },
								{ label: __( 'Start Date Only', 'just-events' ), value: 'start' },
								{ label: __( 'End Date Only', 'just-events' ), value: 'end' },
							]
						}
						value = { start_end }
						onChange = { start_end => { setAttributes( { start_end } ) } }
					/>
				<PanelRow>
					<label
						htmlFor="just-events__event-date[show_time]"
					>
						{ __( 'Show Time', 'just-events' ) }
					</label>
					<FormToggle
						id="just-events__event-date[show_time]"
						checked={ show_time }
						onChange={() => setAttributes({ show_time: !show_time })}
					/>
				</PanelRow>
			</PanelBody>
		</InspectorControls>

		<div { ...blockProps }>
			<ServerSideRender
				block="just-events/event-date"
				attributes={ attributes }
			/>
		</div>
		</>
	);
}
