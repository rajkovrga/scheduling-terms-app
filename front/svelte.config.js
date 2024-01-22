import adapter from '@sveltejs/adapter-node';

import { vitePreprocess } from '@sveltejs/kit/vite';

let assertUrl = '';

if (process.env.NODE_ENV === 'production') {
	assertUrl = process.env.ASSETS_URL;
}

/** @type {import('@sveltejs/kit').Config} */
const config = {
	preprocess: vitePreprocess(),

	kit: {
		adapter: adapter({
			polyfill: false,
			precompress: true,
			out: 'build'
		}),
		csrf: {
			checkOrigin: true
		},
		embedded: false,
		output: {
			preloadStrategy: 'preload-mjs'
		},
		paths: {
			assets: assertUrl,
			relative: false
		}
	},
	compilerOptions: {
		legacy: false,
		errorMode: 'throw',
		format: 'esm'
	}
};

export default config;
