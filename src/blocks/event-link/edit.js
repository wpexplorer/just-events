/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, BlockControls, AlignmentToolbar } from '@wordpress/block-editor';
import {
	PanelRow,
	TextControl,
	PanelBody,
	FormToggle,
	__experimentalToggleGroupControl as ToggleGroupControl,
    __experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Internal dependencies
 */
import './editor.scss';
import metadata from './block.json';

export default function Edit( { context, attributes, setAttributes } ) {
	const { text, design, targetBlank } = attributes;

	const blockProps = useBlockProps();

	// Provides fix for known issue: https://github.com/WordPress/gutenberg/issues/34882
	let urlQueryArgs = {};
	metadata.usesContext.forEach( contextName => {
		urlQueryArgs[ contextName ] = context[ contextName ] ?? null;
	} );

	return (
		<>
		<InspectorControls>
			<PanelBody>
				<ToggleGroupControl
					label={ __( 'Design', 'just-events' ) }
					value={ design }
					onChange={ design => { setAttributes( { design } ) } }
					isBlock
				>
					<ToggleGroupControlOption
						value="none"
						label={ __( 'Default', 'just-events' ) }
					/>
					<ToggleGroupControlOption
						value="button"
						label={ __( 'Button', 'just-events' ) }
					/>
				</ToggleGroupControl>
				<PanelRow>
					<label
						htmlFor="just-events-link-block-target-blank"
					>
						{ __( 'Open in New Tab', 'just-events' ) }
					</label>
					<FormToggle
						id="just-events-link-block-target-blank"
						checked={ targetBlank }
						onChange={() => setAttributes({ targetBlank: !targetBlank })}
					/>
				</PanelRow>
				<PanelRow>
					<TextControl
						label={ __( 'Custom Text', 'just-events' ) }
						onChange={ ( text ) => setAttributes( { text } ) }
						value={ text }
					/>
				</PanelRow>
			</PanelBody>
		</InspectorControls>

		<div { ...blockProps }>
			<ServerSideRender
				block="just-events/event-link"
				attributes={ attributes }
				urlQueryArgs={ urlQueryArgs }
			/>
		</div>
		</>
	);
}
