module.exports = {
	dist: {
		options: {
			processors: [
				require('autoprefixer')({browsers: 'last 2 versions'})
			]
		},
		files: { 
			'assets/css/wordpress-domain-manager.css': [ 'assets/css/wordpress-domain-manager.css' ]
		}
	}
};