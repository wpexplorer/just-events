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
		src: <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V9h14v10zm0-12H5V5h14v2zM7 11h5v5H7z"/></svg>
	},
	/**
	 * @see ./edit.js
	 */
	edit: Edit,
	transforms: {
		from: [ {
			type: 'shortcode',
			tag: 'je_event_date',
			attributes: {
				show_time: {
					type: 'boolean',
					shortcode: ( { named: { show_time } } ) => {
						return 'true' === show_time;
					},
				},
				separator: {
					type: 'string',
					shortcode: ( { named: { separator } } ) => {
						return separator;
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
