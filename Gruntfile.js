'use strict';
module.exports = function(grunt) {

    // load all grunt tasks matching the `grunt-*` pattern
    // Ref. https://npmjs.org/package/load-grunt-tasks
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        
        // Watch for hanges and trigger compass, jshint, uglify and livereload
        // Ref. https://npmjs.org/package/grunt-contrib-watch
        watch: {
            compass: {
                files: ['assets/scss/**/*.{scss,sass}'],
                tasks: ['compass']
            }
        },
        
        // SCSS and Compass
        // Ref. https://npmjs.org/package/grunt-contrib-compass
        compass: {
            dist: {
                options: {
                    config: 'config.rb',
                    force: true
                }
            }
        },
        
        // Fontello Icons
        // Ref. https://npmjs.org/package/grunt-fontello
        fontello: {
            dist: {
                options: {
                    config: 'assets/icon-font/config.json',
                    fonts: 'assets/icon-font/font',
                    styles: 'assets/icon-font/css',
                    scss: false,
                    force: true
                }
            }
        }
    });

    // Register Task
    grunt.registerTask('default', ['fontello', 'watch']);
};