/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, BlockControls, AlignmentToolbar } from '@wordpress/block-editor';
import { SelectControl, TextControl, FormToggle, PanelBody, PanelRow, ExternalLink } from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Internal dependencies
 */
import './editor.scss';
import metadata from './block.json';

export default function Edit( { context, attributes, setAttributes } ) {
	const { textAlign, showTime, startEnd, format, separator, prefix } = attributes;

	const blockProps = useBlockProps( {
		className: classnames( {
			[ `has-text-align-${ textAlign }` ]: textAlign,
		} ),
	} );

	// Provides fix for known issue: https://github.com/WordPress/gutenberg/issues/34882
	let urlQueryArgs = {};
	metadata.usesContext.forEach( contextName => {
		urlQueryArgs[ contextName ] = context[ contextName ] ?? null;
	} );

	return (
		<>
		<InspectorControls>
			<PanelBody>
				<PanelRow>
					<label
						htmlFor="just-events__event-date[showTime]"
					>
						{ __( 'Show Time', 'just-events' ) }
					</label>
					<FormToggle
						id="just-events__event-date[showTime]"
						checked={ showTime }
						onChange={() => setAttributes({ showTime: !showTime })}
					/>
				</PanelRow>
				<PanelRow>
					<SelectControl
						label= { __( 'Display', 'just-events' ) }
						options= {
							[
								{ label: __( 'Start & End Dates', 'just-events' ), value: '' },
								{ label: __( 'Start Date Only', 'just-events' ), value: 'start' },
								{ label: __( 'End Date Only', 'just-events' ), value: 'end' },
							]
						}
						value= { startEnd }
						onChange= { startEnd => { setAttributes( { startEnd } ) } }
					/>
				</PanelRow>
				<PanelRow>
					<TextControl
						label={ __( 'Prefix', 'just-events' ) }
						onChange={ ( prefix ) => setAttributes( { prefix } ) }
						value={ prefix }
						help={ __( 'Custom text to display before the date.', 'just-events' ) }
					/>
				</PanelRow>
				<PanelRow>
					<TextControl
						label={ __( 'Custom Format', 'just-events' ) }
						onChange={ ( format ) => setAttributes( { format } ) }
						value={ format }
						help={
							<>
								<ExternalLink
									href={ __(
										'https://wordpress.org/documentation/article/customize-date-and-time-format/'
									) }
								>
									{ __( 'Documentation', 'just-events' ) }
								</ExternalLink>
							</>
						}
					/>
				</PanelRow>
				<PanelRow>
					<TextControl
						label={ __( 'Custom Separator', 'just-events' ) }
						onChange={ ( separator ) => setAttributes( { separator } ) }
						value={ separator }
						placeholder= ' - '
						help={ __( 'Separator used in the formatted event date between the start and end dates (include empty spaces if needed). Enter <br> to place the start and end dates on different lines.', 'just-events' ) }
					/>
				</PanelRow>
			</PanelBody>
		</InspectorControls>

		<div { ...blockProps }>
			<BlockControls>
				<AlignmentToolbar
					value={ textAlign }
					onChange={ ( newAlign ) =>
						setAttributes( { textAlign: newAlign } )
					}
				/>
			</BlockControls>
			<ServerSideRender
				block="just-events/event-date"
				attributes={ attributes }
				urlQueryArgs={ urlQueryArgs }
			/>
		</div>
		</>
	);
}
