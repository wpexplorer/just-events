/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Internal dependencies
 */
import './editor.scss';
import metadata from './block.json';

export default function Edit( { context, attributes, setAttributes } ) {
	const blockProps = useBlockProps();

	// Provides fix for known issue: https://github.com/WordPress/gutenberg/issues/34882
	let urlQueryArgs = {};
	metadata.usesContext.forEach( contextName => {
		urlQueryArgs[ contextName ] = context[ contextName ] ?? null;
	} );

	return (
		<>
		<div { ...blockProps }>
			<ServerSideRender
				block="just-events/event-status"
				attributes={ attributes }
				urlQueryArgs={ urlQueryArgs }
			/>
		</div>
		</>
	);
}
