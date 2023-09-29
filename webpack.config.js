const path = require( 'path' );

// Import the original config from the @wordpress/scripts package.
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

// Import the helper to find and generate the entry points in the src directory
const { getWebpackEntryPoints } = require( '@wordpress/scripts/utils/config' );

module.exports = {
	...defaultConfig,
    entry: {
        ...getWebpackEntryPoints(),
        'editor': path.resolve( process.cwd(), 'src/editor.js' )
    }
};
