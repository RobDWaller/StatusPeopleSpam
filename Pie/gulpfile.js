var gulp = require('gulp');
var less = require('gulp-less');
var minify = require('gulp-minify-css');
var uglify = require('gulp-uglify');
var concat = require('gulp-concat');

gulp.task('fonts', function(){
	return gulp.src([
		'./Crust/Template/fonts/*'
	])
	.pipe(gulp.dest('./Public/fonts'))
});

gulp.task('js', function(){
    return gulp.src([
		'./node_modules/jquery/dist/jquery.min.js',
		'./Crust/Template/js/highcharts/js/highcharts.js',
		'./Crust/Template/js/libs/jquery.cookie.js'
    	])
	.pipe(concat('fakers.js'))
	.pipe(gulp.dest('./Public/Assets/js'));
});

gulp.task('less', function(){
    return gulp.src('./Crust/Template/less/style.less')
        .pipe(less())
        .pipe(minify())
        .pipe(gulp.dest('./Crust/Template/css'))
        .pipe(gulp.dest('./Public/Assets/css'));
});

gulp.task('default', ['less', 'js', 'fonts']);
