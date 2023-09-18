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
	const { textAlign, startEnd, format, separator, prefix } = attributes;

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
					<SelectControl
						label= { __( 'Display', 'just-events' ) }
						options= {
							[
								{ label: __( 'Start & End Time', 'just-events' ), value: '' },
								{ label: __( 'Start Time Only', 'just-events' ), value: 'start' },
								{ label: __( 'End Time Only', 'just-events' ), value: 'end' },
							]
						}
						value= { startEnd }
						onChange= { startEnd => { setAttributes( { startEnd } ) } }
					/>
				</PanelRow>
				<TextControl
					label={ __( 'Prefix', 'just-events' ) }
					onChange={ ( prefix ) => setAttributes( { prefix } ) }
					value={ prefix }
					help={ __( 'Custom text to display before the date.', 'just-events' ) }
				/>
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
				<TextControl
					label={ __( 'Custom Separator', 'just-events' ) }
					onChange={ ( separator ) => setAttributes( { separator } ) }
					value={ separator }
					placeholder= ' - '
					help={ __( 'Separator used in the formatted event date between the start and end dates (include empty spaces if needed). Enter <br> to place the start and end dates on different lines.', 'just-events' ) }
				/>
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
				block="just-events/event-time"
				attributes={ attributes }
				urlQueryArgs={ urlQueryArgs }
			/>
		</div>
		</>
	);
}
