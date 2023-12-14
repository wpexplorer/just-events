/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, BlockControls, AlignmentToolbar } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Internal dependencies
 */
import './editor.scss';
import metadata from './block.json';

export default function Edit( { context, attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	const { textAlign } = attributes;

	// Provides fix for known issue: https://github.com/WordPress/gutenberg/issues/34882
	let urlQueryArgs = {};
	metadata.usesContext.forEach( contextName => {
		urlQueryArgs[ contextName ] = context[ contextName ] ?? null;
	} );

	return (
		<>
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
				block="just-events/event-status"
				attributes={ attributes }
				urlQueryArgs={ urlQueryArgs }
			/>
		</div>
		</>
	);
}
