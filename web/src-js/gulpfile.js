const gulp = require('gulp');
const sourcemaps  = require('gulp-sourcemaps');
const gulpif = require('gulp-if');
const uglify = require('gulp-uglify');
const connect = require('gulp-connect');
const watch = require('gulp-watch');
const cleanCSS = require('gulp-clean-css');
const rename = require('gulp-rename');

const browserify = require('browserify');
const babelify = require('babelify');
const source = require('vinyl-source-stream');
const buffer = require('vinyl-buffer');

const env = 'dev';
const version = '1.0.0';

gulp.task('compile-css', function () {
  return gulp.src('src/lib.css')
  //.pipe(cleanCSS({}))
  .pipe(rename('dnext-engine.min.css'))
  .pipe(gulp.dest('build/lib'));
});

gulp.task('compile-sandbox', function () {
  let bundler = browserify('build/app/app.js', {
        basedir: __dirname,
        debug: true,
        cache: {}, // required for watchify
        packageCache: {}, // required for watchify
        fullPaths: true,
  });

  bundler.transform("babelify", {presets: ['es2015', 'es2016']});

  bundler.bundle()
    .on('error', function (err) {
        console.error(err);
    })
    .pipe(source('app.js'))
    .pipe(buffer())
    .pipe(sourcemaps.init({loadMaps: true}))
    .pipe(gulpif(false, uglify()))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('build'));
});

gulp.task('compile', function() {
  let bundler = browserify('src/lib.js', {
        basedir: __dirname,
        debug: env !== 'prod',
        cache: {}, // required for watchify
        packageCache: {}, // required for watchify
        fullPaths: env !== 'prod',
  });

  bundler.transform("babelify", {presets: ['es2015', 'es2016']});

  bundler.bundle()
    .on('error', function (err) {
        console.error(err);
    })
    .pipe(source('dnext-engine.js'))
    .pipe(buffer())
    .pipe(sourcemaps.init({loadMaps: true}))
    .pipe(gulpif(env === 'prod', uglify()))
    .pipe(sourcemaps.write('./'))
    .pipe(gulp.dest('build/lib'));
});

gulp.task('watch', function () {
    return watch(['src/**/*.js', 'src/**/*.css'], () => gulp.start('default'));
});

gulp.task('sandbox', function() {
  connect.server({
    root: 'build',
    livereload: true,
    port: 8888
  });
});

gulp.task('default', ['compile', 'compile-css']);
