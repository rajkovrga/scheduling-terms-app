/** @type {import('tailwindcss').Config} */

module.exports = {
	content: [
		'./src/**/*.{html,js,svelte,ts}',
		'./node_modules/flowbite-svelte/**/*.{html,js,svelte,ts}',
		'./node_modules/flowbite/**/*.js'
	],
	theme: {
		extend: {}
	},
	plugins: [require('@tailwindcss/forms'), require('flowbite/plugin')],
	darkMode: 'class'
};
