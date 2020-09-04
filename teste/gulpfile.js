'use strict';

let gulp        = require('gulp');
let jshint      = require('gulp-jshint');
let clean       = require('gulp-clean');
let concat      = require('gulp-concat');
let babel       = require('gulp-babel');
let uglify      = require('gulp-uglify');
let htmlmin     = require('gulp-htmlmin');
let imagemin    = require('gulp-imagemin');
let sass        = require('gulp-sass');
let livereload  = require('gulp-livereload');
let runSequence = require('run-sequence');
let pump        = require('pump');
let csso        = require('gulp-csso');
let sourcemaps  = require('gulp-sourcemaps');

let path_maps = '../maps/';

livereload({start: true});

gulp.task('clean', function (cb) {
    pump([
             gulp.src('public/'),
             clean()
         ],
         cb
    );
});

gulp.task('lint', function (cb) {
    pump([
             gulp.src('assets/js/**/*.js'),
             jshint({esversion: '6'}),
             jshint.reporter('default')
         ],
         cb
    );
});

gulp.task('compress', function (cb) {
    pump([
             gulp.src('assets/js/*.js'),
             sourcemaps.init(),
             //concat('scripts.js'),
             babel({presets: ['env'], plugins: ['transform-runtime']}),
             uglify(),
             sourcemaps.write(path_maps + 'js'),
             //concat('all.min.js'),
             gulp.dest('public/js'),
             livereload()
         ],
         cb
    );
});

gulp.task('compress-includes', function (cb) {
    pump([
             gulp.src('assets/js/includes/*.js'),
             sourcemaps.init(),
             concat('main.min.js'),
             babel({presets: ['env'], plugins: ['transform-runtime']}),
             uglify(),
             sourcemaps.write(path_maps + 'js'),
             gulp.dest('public/js'),
             livereload()
         ],
         cb
    );
});

gulp.task('htmlmin', function (cb) {
    pump([
             gulp.src('assets/views/**/*.html'),
             htmlmin({collapseWhitespace: true}),
             gulp.dest('public/views'),
             livereload()
         ],
         cb
    );
});

gulp.task('imagemin', function (cb) {
    pump([
             gulp.src('assets/images/**/*'),
             imagemin(),
             gulp.dest('public/images'),
             livereload()
         ],
         cb
    );
});

gulp.task('template', function (cb) {
    pump([
             gulp.src('assets/templates/**/*'),
             gulp.dest('public/templates'),
             livereload()
         ],
         cb
    );
});

gulp.task('sass', function (cb) {
    pump([
             gulp.src('assets/sass/*.scss'),
             sourcemaps.init(),
             sass(),
             csso({
                      restructure: false,
                      sourceMap  : false,
                      debug      : false
                  }),
             sourcemaps.write(path_maps + 'css'),
             gulp.dest('public/css'),
             livereload()
         ], cb
    );
});

gulp.task('watch', function () {
    livereload.listen();
    
    gulp.watch('assets/views/*.html', ['htmlmin']);
    gulp.watch('assets/views/**/*.html', ['htmlmin']);
    gulp.watch('assets/images/**/*', ['imagemin']);
    gulp.watch('assets/js/*.js', ['compress']);
    gulp.watch('assets/js/includes/*.js', ['compress-includes']);
    gulp.watch('assets/sass/*.scss', ['sass']);
    gulp.watch('assets/sass/**/*.scss', ['sass']);
    gulp.watch('assets/sass/beauty/**/*.scss', ['sass']);
    gulp.watch('assets/templates/**/*', ['template']);
});

gulp.task('default', function (cb) {
    return runSequence('clean', ['lint', 'compress', 'compress-includes', 'htmlmin', 'imagemin', 'sass', 'template', 'watch'], cb)
});