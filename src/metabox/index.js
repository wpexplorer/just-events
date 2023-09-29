/**
 * Registers a plugin for adding items to the Gutenberg Toolbar.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/slotfills/plugin-sidebar/
 */
import { registerPlugin } from '@wordpress/plugins';

/**
 * Internal dependencies.
 */
import MetaBox from './render';

registerPlugin( 'just-events-meta-box', {
    render: MetaBox
} );
