/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Internal dependencies
 */
import Edit from './edit';
import metadata from './block.json';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType( metadata.name, {
	icon: {
		src: <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
	},
	/**
	 * @see ./edit.js
	 */
	edit: Edit,
	transforms: {
		from: [ {
			type: 'shortcode',
			tag: 'je_event_time',
			attributes: {
				startEnd: {
					type: 'string',
					shortcode: ( { named: { start_end } } ) => {
						return start_end;
					},
				},
				format: {
					type: 'string',
					shortcode: ( { format: { format } } ) => {
						return format;
					},
				},
				separator: {
					type: 'string',
					shortcode: ( { named: { separator } } ) => {
						return separator;
					},
				}
			}
		} ]
	}
} );
