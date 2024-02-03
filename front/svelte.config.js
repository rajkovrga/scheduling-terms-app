import adapter from '@sveltejs/adapter-node';

import { vitePreprocess } from '@sveltejs/kit/vite';



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
			assets: '',
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
