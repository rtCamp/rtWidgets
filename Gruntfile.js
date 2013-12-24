'use strict';
module.exports = function(grunt) {

    // load all grunt tasks matching the `grunt-*` pattern
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({

        // watch for changes and trigger compass, jshint, uglify and livereload
        watch: {
            compass: {
                files: ['assets/**/*.{scss,sass}'],
                tasks: ['compass']
            },
            
            livereload: {
                options: { livereload: true },
                files: ['style.css', '*.html', '*.php']
            }
        },

        // compass and scss
        compass: {
            dist: {
                options: {
                    config: 'config.rb',
                    force: true
                }
            }
        }
    });

    // register task
    grunt.registerTask('default', ['watch']);
};