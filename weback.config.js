import defaultConfig, { entry as _entry } from '@wordpress/scripts/config/webpack.config';

import { resolve } from 'path';

export default {
    ...defaultConfig,
    entry: {
        ..._entry,
        admin: resolve( process.cwd(), 'src', 'admin.js' ),
    }
};