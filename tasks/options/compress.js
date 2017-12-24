module.exports = {
	main: {
		options: {
			mode: 'zip',
			archive: './release/wpdm.<%= pkg.version %>.zip'
		},
		expand: true,
		cwd: 'release/<%= pkg.version %>/',
		src: ['**/*'],
		dest: 'wpdm/'
	}
};