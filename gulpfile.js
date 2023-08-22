'use strict';

var gulp = require('gulp'),
    all = require('gulp-all'),
    order = require("gulp-order"),
    addsrc = require('gulp-add-src'),
    plumber = require('gulp-plumber'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    header = require('gulp-header'),
    eslint = require('gulp-eslint'),
    del = require('del');


// Build: Sourcemap, Concat, Uglify, Header
var bundles = [
    {target: 'app.js', src: ['assets/js/**/*.js']},
];

function build(bundle) {
    return gulp.src(bundle.src)
        .pipe(order())
        .pipe(addsrc.prepend('assets/js/head.js'))
        .pipe(addsrc.append('assets/js/tail.js'))
        // .pipe(sourcemaps.init())
        .pipe(plumber())
        .pipe(concat(bundle.target))
        // .pipe(uglify())
        .pipe(header('/** rxwod 2023 **/\n'))
        .pipe(plumber.stop())
        .pipe(gulp.dest(bundle.dest || 'public/js'));
}

gulp.task('build-js', function() {
    var result = bundles.map(build);
    return all(result);
});

// Watch
gulp.task('watch', function() {
    gulp.watch('resources/js/**/*.js', ['build-js']);
});
