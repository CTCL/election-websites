module.exports = function( grunt ) {

	// All a variable to be passed, eg. --url=http://test.dev
	var localURL = grunt.option( 'url' );
	var sass = require( 'node-sass' );

	// Load all grunt tasks
	require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );
	grunt.loadNpmTasks( '@lodder/grunt-postcss' );

	// Project configuration
	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		browserify: {
			dist: {
				files: {
					'assets/js/contact-form.src.js': [ 'assets/js/src/contact-form.js' ],
					'assets/js/numbered-section-block-editor.src.js': [ 'assets/js/src/numbered-section-block-editor.js' ],
					'assets/js/tile-nav-block-editor.src.js': [ 'assets/js/src/tile-nav-block-editor.js' ]
				},
				options: {
					transform        : [ [ 'babelify', { presets: [ "@babel/preset-env", "@babel/preset-react" ] } ] ],
					browserifyOptions: {
						debug: false
					}
				}
			}
		},

		concat: {
			options: {
				stripBanners: true,
				sourceMap: true
			},
			main: {
				src: [
					'assets/js/src/main.js'
				],
				dest: 'assets/js/main.src.js'
			},
			block: {
				src: [
					'assets/js/contact-form.src.js',
					'assets/js/contact-form.src.js',
					'assets/js/contact-form.src.js'
				],
				dest: 'assets/js/blocks.src.js'
			}
		},

		uglify: {
			all: {
				files: {
					'assets/js/main.min.js': [ 'assets/js/main.src.js' ],
					'assets/js/blocks.min.js': [ 'assets/js/blocks.src.js' ]
				},
				options: {
					mangle: {
						reserved: [ 'jQuery' ]
					},
					sourceMap: false
				}
			}
		},

		eslint: {
			src: [ 'assets/js/src/**/*.js' ],
			options: {
				fix: true,
				configFile: '.eslintrc.json'
			}
		},

		stylelint: {
			src: [ 'assets/css/src/**/*.scss' ],
			options: {
				fix: true,
				configFile: '.stylelintrc.json'
			}
		},

		sass: {
			theme: {
				options: {
					implementation: sass,
					imagePath: 'assets/images',
					outputStyle: 'expanded',
					sourceMap: true
				},
				files: [ {
					expand: true,
					cwd: 'assets/css/src',
					src: [ '*.scss', '!_*.scss' ],
					dest: 'assets/css',
					ext: '.src.css'
				} ]
			}
		},

		/*
		 * Runs postcss plugins
		 */
		postcss: {

			/* Runs postcss + autoprefixer on the minified CSS. */
			theme: {
				options: {
					map: false,
					processors: [
						require( 'autoprefixer' )()
					]
				},
				files: [ {
					expand: true,
					cwd: 'assets/css',
					src: [ '*.src.css' ],
					dest: 'assets/css',
					ext: '.src.css'
				} ]
			}
		},

		cssmin: {
			theme: {
				files: [ {
					expand: true,
					cwd: 'assets/css',
					src: [ '*.src.css' ],
					dest: 'assets/css',
					ext: '.min.css'
				} ]
			}
		},

		svgmin: {
			options: {
				plugins: [
					{ removeViewBox: false }
				]
			},
			dist: {
				expand: true,
				src: [ 'assets/images/*.svg', 'assets/images/**/*.svg' ]
			}
		},

		watch: {
			php: {
				files: [ '*.php', 'template-parts/**/*.php', 'includes/**/*.php', '!vendor/**' ],
				tasks: [ 'phplint' ]
			},

			css: {
				files: [ 'assets/css/src/**/*.scss' ],
				tasks: [ 'css' ],
				options: {
					debounceDelay: 500
				}
			},

			scripts: {
				files: [ 'assets/js/src/**/*.js', 'assets/js/vendor/**/*.js' ],
				tasks: [ 'js' ],
				options: {
					debounceDelay: 500
				}
			}
		},

		phplint: {
			phpArgs: {
				'-lf': null
			},
			files: [ '*.php', 'template-parts/**/*.php', 'includes/**/*.php' ]
		},

		git_modified_files: {
			options: {
				diffFiltered: 'ACMRTUXB', // Optional: default is 'AMC',
				regexp: /\.php$/ // Optional: default is /.*/
			}
		},

		phpcs: {
			application: {
				src: '<%= gmf.filtered %>'
			},
			options: {
				bin: 'vendor/bin/phpcs'
			}
		}

	} );

	// SVG Only
	grunt.registerTask( 'svg', [ 'svgmin' ] );

	// Set a default, so if phpcs is run directly it scans everything
	grunt.config.set( 'gmf.filtered', [ '**/*.php', '!vendor/**', '!node_modules/**' ] );
	grunt.registerTask( 'precommit', [ 'git_modified_files', 'maybe-phpcs' ] );
	grunt.registerTask( 'maybe-phpcs', 'Only run phpcs if git_modified_files has found changes.', function() {

		// Check all, because there's no default set for all and we can see if we have files
		var allModified = grunt.config.get( 'gmf.all' );
		var matches = allModified.filter( function( str ) {
			return ( -1 !== str.search( /\.php$/ ) );
		} );

		if ( ! matches.length ) {
			grunt.log.writeln( 'No php files to sniff. Skipping phpcs.' );
		} else {
			grunt.task.run( 'phpcs' );
		}
	} );

	// PHP Only
	grunt.registerTask( 'php', [ 'phplint', 'phpcs' ] );

	// JS Only
	grunt.registerTask( 'js', [ 'eslint', 'browserify', 'concat', 'uglify' ] );

	// CSS Only
	grunt.registerTask( 'css', [ 'stylelint', 'sass', 'postcss', 'cssmin' ] );

	// CSS & JS Only
	grunt.registerTask( 'css-js', [ 'css', 'js' ] );

	// Default task.
	grunt.registerTask( 'default', [ 'js', 'css', 'php' ] );
};
