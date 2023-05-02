module.exports = function( grunt ) {

	var sass = require( 'node-sass' );
	var inliner = require( 'sass-inline-svg' );

	// Load all grunt tasks
	require( 'matchdep' ).filterDev( 'grunt-*' ).forEach( grunt.loadNpmTasks );
	grunt.loadNpmTasks( '@lodder/grunt-postcss' );

	// Project configuration
	grunt.initConfig( {
		pkg: grunt.file.readJSON( 'package.json' ),

		browserify: {
			dist: {
				files: {
					'assets/js/blocks.src.js': [ 'assets/js/src/blocks/*.js' ]
				},
				options: {
					transform: [
						[
							'babelify', {
								presets: [ '@babel/preset-env', '@babel/preset-react' ],
								compact: false
							}
						]
					],
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
				src: [ 'assets/js/src/main.js' ],
				dest: 'assets/js/main.src.js'
			},
			admin: {
				src: [ 'assets/js/src/admin.js' ],
				dest: 'assets/js/admin.src.js'
			}
		},

		uglify: {
			all: {
				files: {
					'assets/js/admin.min.js': [ 'assets/js/admin.src.js' ],
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
				fix: true
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
					sourceMap: true,
					functions: {
						svg: inliner( './', { encodingFormat: 'uri' } )
					}
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
					{
						name: 'preset-default',
						params: {
							overrides: {
								removeViewBox: false,
								removeUnknownsAndDefaults: false // otherwise mask-type is removed; see https://github.com/svg/svgo/issues/1120
							}
						}
					}
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
				tasks: [ 'phplint', 'phpcbf' ]
			},

			css: {
				files: [ 'assets/css/src/**/*.scss' ],
				tasks: [ 'css' ],
				options: {
					debounceDelay: 500
				}
			},

			scripts: {
				files: [ 'assets/js/src/**/*.js' ],
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
		},

		phpcbf: {
			options: {
				bin: 'vendor/bin/phpcbf',
				noPatch: false
			},
			files: {
				src: [ '*.php', 'template-parts/**/*.php', 'includes/**/*.php' ]
			}
		},

		wp_readme_to_markdown: {
			theme: {
				files: {
					'README.md': 'readme.txt'
				},
				options: {
					screenshot_url: 'screenshot.png'
				}
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

	// Readme
	grunt.registerTask( 'readme', [ 'wp_readme_to_markdown' ] );

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
